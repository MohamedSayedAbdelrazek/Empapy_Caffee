<?php

if (! function_exists('asset_version')) {
    /**
     * Cache-busted URL for a hand-written asset under public/ (QUAL-04).
     *
     * Appends ?v={filemtime} so browsers refetch a file only when it actually
     * changes — no manual version bumping, and no build step. Falls back to a
     * plain asset() URL if the file is missing (e.g. an external/CDN path).
     */
    function asset_version(string $path): string
    {
        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            return asset($path).'?v='.filemtime($fullPath);
        }

        return asset($path);
    }
}
