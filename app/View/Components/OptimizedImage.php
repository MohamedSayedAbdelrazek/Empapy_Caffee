<?php

namespace App\View\Components;

use App\Services\ImageService;
use Illuminate\View\Component;

class OptimizedImage extends Component
{
    public string $src;
    public ?string $webpSrc;
    public string $alt;
    public ?string $class;
    public ?string $style;
    public ?int $width;
    public ?int $height;
    public string $loading;
    public string $decoding;
    public ?string $fetchpriority;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $src = null,
        string $alt = '',
        ?string $class = null,
        ?string $style = null,
        ?int $width = null,
        ?int $height = null,
        string $loading = 'lazy',
        string $decoding = 'async',
        ?string $fetchpriority = null
    ) {
        // Handle null/empty src with a simple gray placeholder
        $placeholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300"%3E%3Crect fill="%23f0f0f0" width="300" height="300"/%3E%3Ctext fill="%23999" font-family="Arial" font-size="16" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle"%3ENo Image%3C/text%3E%3C/svg%3E';
        $this->src = $src ?: $placeholder;
        $this->alt = $alt;
        $this->class = $class;
        $this->style = $style;
        $this->width = $width;
        $this->height = $height;
        $this->loading = $loading;
        $this->decoding = $decoding;
        $this->fetchpriority = $fetchpriority;

        // Check if WebP version exists (only if we have a real src)
        $this->webpSrc = $src ? $this->getWebpPath($src) : null;
    }

    /**
     * Get WebP path if exists
     */
    private function getWebpPath(string $src): ?string
    {
        // If already WebP, return null (no need for fallback)
        if (str_ends_with(strtolower($src), '.webp')) {
            return null;
        }

        // Generate WebP path
        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $src);

        // Check if file exists
        $publicPath = public_path(ltrim($webpPath, '/'));

        if (file_exists($publicPath)) {
            return $webpPath;
        }

        return null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.optimized-image');
    }
}
