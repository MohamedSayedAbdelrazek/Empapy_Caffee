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
        string $src,
        string $alt = '',
        ?string $class = null,
        ?string $style = null,
        ?int $width = null,
        ?int $height = null,
        string $loading = 'lazy',
        string $decoding = 'async',
        ?string $fetchpriority = null
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->style = $style;
        $this->width = $width;
        $this->height = $height;
        $this->loading = $loading;
        $this->decoding = $decoding;
        $this->fetchpriority = $fetchpriority;

        // Check if WebP version exists
        $this->webpSrc = $this->getWebpPath($src);
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
