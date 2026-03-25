<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

class UrlRedirectController extends Controller
{
    /**
     * Redirect to the original URL.
     *
     * @param  string  $code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(string $code)
    {
        $url = Url::where('short_url', $code)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment click count (simple way, can use a Job for scale)
        $url->increment('click_count');

        return redirect()->away($url->original_url);
    }
}
