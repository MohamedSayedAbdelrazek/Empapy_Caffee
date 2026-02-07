{{-- Optimized Image Component with WebP Support --}}
@if($webpSrc)
    <picture>
        <source srcset="{{ $webpSrc }}" type="image/webp">
        <img 
            src="{{ $src }}" 
            alt="{{ $alt }}"
            @if($class) class="{{ $class }}" @endif
            @if($style) style="{{ $style }}" @endif
            @if($width) width="{{ $width }}" @endif
            @if($height) height="{{ $height }}" @endif
            loading="{{ $loading }}"
            decoding="{{ $decoding }}"
            @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        >
    </picture>
@else
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}"
        @if($class) class="{{ $class }}" @endif
        @if($style) style="{{ $style }}" @endif
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        loading="{{ $loading }}"
        decoding="{{ $decoding }}"
        @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
    >
@endif
