<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class TextAuditVn extends Command
{
    protected $signature = 'text:audit-vn {--scope=admin : Scope to audit (currently supports: admin)}';

    protected $description = 'Audit Vietnamese text quality: UTF-8 BOM, mojibake patterns, and no-accent UI phrases.';

    private const FILE_PATTERNS = ['*.php', '*.blade.php', '*.js', '*.ts', '*.json', '*.md'];

    private const NO_ACCENT_PHRASE_PATTERNS = [
        '/\bKhong\b/u',
        '/\bVui long\b/u',
        '/\bSan pham\b/u',
        '/\bHinh anh\b/u',
        '/\bDanh muc\b/u',
        '/\bThuong hieu\b/u',
        '/\bDon hang\b/u',
        '/\bNguoi dung\b/u',
        '/\bThem\b/u',
        '/\bSua\b/u',
        '/\bXoa\b/u',
    ];

    private const MOJIBAKE_PATTERNS = [
        '/CÃ/u',
        '/Ã¡/u',
        '/Ã³/u',
        '/áº/u',
        '/á»/u',
    ];

    public function handle(): int
    {
        $scope = (string) $this->option('scope');
        if ($scope !== 'admin') {
            $this->error("Unsupported scope [{$scope}]. Use --scope=admin.");

            return self::FAILURE;
        }

        $paths = [
            base_path('app/Http/Controllers/Admin'),
            base_path('app/Http/View/Composers'),
            base_path('resources/views/admin'),
            base_path('resources/views/components/admin'),
            base_path('config/admin'),
            base_path('lang/vi'),
            base_path('lang/en'),
        ];

        $files = $this->collectFiles($paths);
        $findings = [];

        foreach ($files as $filePath) {
            $content = @file_get_contents($filePath);
            if ($content === false) {
                continue;
            }

            if ($this->hasBom($content)) {
                $findings[] = [$filePath, 1, 'bom', 'UTF-8 BOM detected'];
            }

            $lines = preg_split('/\R/u', $content) ?: [];

            foreach ($lines as $index => $line) {
                $lineNo = $index + 1;
                $trimmed = trim($line);

                if ($trimmed === '') {
                    continue;
                }

                foreach (self::MOJIBAKE_PATTERNS as $pattern) {
                    if (preg_match($pattern, $line) === 1) {
                        $findings[] = [$filePath, $lineNo, 'mojibake', Str::limit($trimmed, 140)];
                        break;
                    }
                }

                foreach (self::NO_ACCENT_PHRASE_PATTERNS as $pattern) {
                    if (preg_match($pattern, $line) === 1) {
                        $findings[] = [$filePath, $lineNo, 'no_accent', Str::limit($trimmed, 140)];
                        break;
                    }
                }
            }
        }

        if (empty($findings)) {
            $this->info('Text audit passed: no BOM, mojibake, or no-accent phrases found in admin scope.');

            return self::SUCCESS;
        }

        $this->error('Text audit failed. Findings:');
        $this->table(['File', 'Line', 'Rule', 'Snippet'], $findings);

        return self::FAILURE;
    }

    /**
     * @param array<int, string> $paths
     * @return array<int, string>
     */
    private function collectFiles(array $paths): array
    {
        $finder = new Finder();
        $finder->files()->name(self::FILE_PATTERNS);

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $finder->in($path);
            }
        }

        $results = [];
        foreach ($finder as $file) {
            $results[] = $file->getRealPath() ?: $file->getPathname();
        }

        sort($results);

        return $results;
    }

    private function hasBom(string $content): bool
    {
        return str_starts_with($content, "\xEF\xBB\xBF");
    }
}

