<?php

$base = __DIR__ . '/../resources/views/admin';
$viPath = __DIR__ . '/../lang/vi/admin.php';
$enPath = __DIR__ . '/../lang/en/admin.php';

$vi = include $viPath;
$en = include $enPath;

if (!isset($vi['auto']) || !is_array($vi['auto'])) $vi['auto'] = [];
if (!isset($en['auto']) || !is_array($en['auto'])) $en['auto'] = [];

$textToKey = [];
foreach ($vi['auto'] as $k => $v) {
    if (is_string($v)) $textToKey[$v] = $k;
}

function key_for_text(string $text, array &$vi, array &$en, array &$textToKey): string {
    $t = trim(preg_replace('/\s+/u', ' ', $text));
    if ($t === '') return '';
    if (isset($textToKey[$t])) return $textToKey[$t];
    $key = 'k_' . substr(md5($t), 0, 12);
    $i = 0;
    while (isset($vi['auto'][$key])) {
        $i++;
        $key = 'k_' . substr(md5($t . '_' . $i), 0, 12);
    }
    $vi['auto'][$key] = $t;
    $en['auto'][$key] = $t;
    $textToKey[$t] = $key;
    return $key;
}

function should_translate_text(string $txt): bool {
    $t = trim($txt);
    if ($t === '') return false;
    if (str_contains($t, '{{') || str_contains($t, '}}') || str_contains($t, '@')) return false;
    if (preg_match('/^[-+*\/(){}\[\],.:;#%0-9\s]+$/u', $t)) return false;
    if (!preg_match('/[\p{L}]/u', $t)) return false;
    return true;
}

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
$changedFiles = 0;
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    if (!str_ends_with($file->getFilename(), '.blade.php')) continue;

    $path = $file->getPathname();
    $content = file_get_contents($path);
    $original = $content;

    $content = preg_replace_callback('/\\b(title|label|placeholder|message)="([^"]*)"/u', function ($m) use (&$vi, &$en, &$textToKey) {
        $attr = $m[1];
        $val = $m[2];
        if ($val === '' || str_contains($val, '{{') || str_contains($val, "__('admin.")) return $m[0];
        if (!preg_match('/[\p{L}]/u', $val)) return $m[0];
        $key = key_for_text($val, $vi, $en, $textToKey);
        return ':' . $attr . '="__(\'admin.auto.' . $key . '\')"';
    }, $content);

    $content = preg_replace_callback('/>([^<]+)</u', function ($m) use (&$vi, &$en, &$textToKey) {
        $txt = $m[1];
        if (!should_translate_text($txt)) return $m[0];
        $key = key_for_text($txt, $vi, $en, $textToKey);
        preg_match('/^(\s*).*(\s*)$/us', $txt, $ws);
        $leading = $ws[1] ?? '';
        $trailing = $ws[2] ?? '';
        return '>' . $leading . '{{ __(\'admin.auto.' . $key . '\') }}' . $trailing . '<';
    }, $content);

    if ($content !== $original) {
        file_put_contents($path, $content);
        $changedFiles++;
    }
}

ksort($vi['auto']);
ksort($en['auto']);

$export = function(array $arr): string {
    return "<?php\n\nreturn " . var_export($arr, true) . ";\n";
};

file_put_contents($viPath, $export($vi));
file_put_contents($enPath, $export($en));

echo "Changed files: {$changedFiles}\n";
echo "Auto keys vi: " . count($vi['auto']) . "\n";
