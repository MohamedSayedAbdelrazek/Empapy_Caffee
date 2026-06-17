<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Upload an image and convert it to WebP format for better performance
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The directory to store the image (relative to public/)
     * @param string|null $customName Optional custom name for the file
     * @return array ['original' => path, 'webp' => path] or ['path' => webp_path]
     */
    public static function uploadAndConvert(UploadedFile $file, string $directory, ?string $customName = null): array
    {
        $publicPath = public_path($directory);
        
        // Ensure directory exists
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        // Determine the extension from the SERVER-detected MIME type, never the
        // client-supplied extension (SEC-08). Controllers already validate that
        // the upload is an image; this maps the real content type to a safe
        // extension so a renamed/spoofed file can't dictate what we write.
        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        $extension = $mimeToExtension[$file->getMimeType()] ?? strtolower($file->extension() ?: 'jpg');

        // Use a non-predictable, random filename (SEC-08). An optional trusted
        // custom name is kept only as a readable prefix.
        $prefix = $customName ? Str::slug($customName) : '';
        $baseName = trim($prefix . '-' . Str::random(32), '-');
        
        // If already WebP, just save it
        if ($extension === 'webp') {
            $fileName = $baseName . '.webp';
            $file->move($publicPath, $fileName);
            return [
                'path' => '/' . $directory . '/' . $fileName,
                'webp' => '/' . $directory . '/' . $fileName,
            ];
        }

        // Save original file
        $originalName = $baseName . '.' . $extension;
        $file->move($publicPath, $originalName);
        $originalPath = $publicPath . '/' . $originalName;

        // Convert to WebP
        $webpName = $baseName . '.webp';
        $webpPath = $publicPath . '/' . $webpName;

        $converted = self::convertToWebp($originalPath, $webpPath);

        if ($converted) {
            // Return WebP as primary, keep original as fallback
            return [
                'path' => '/' . $directory . '/' . $webpName,
                'webp' => '/' . $directory . '/' . $webpName,
                'original' => '/' . $directory . '/' . $originalName,
            ];
        }

        // Fallback to original if conversion failed
        return [
            'path' => '/' . $directory . '/' . $originalName,
            'original' => '/' . $directory . '/' . $originalName,
        ];
    }

    /**
     * Convert image to WebP format
     *
     * @param string $sourcePath Full path to source image
     * @param string $destPath Full path to destination WebP file
     * @param int $quality WebP quality (0-100)
     * @return bool Success status
     */
    public static function convertToWebp(string $sourcePath, string $destPath, int $quality = 85): bool
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

        try {
            // Use GD Library (available in most PHP installations)
            if (extension_loaded('gd')) {
                return self::convertWithGD($sourcePath, $destPath, $extension, $quality);
            }

            // Fallback to ImageMagick if available
            if (extension_loaded('imagick')) {
                return self::convertWithImagick($sourcePath, $destPath, $quality);
            }

            // Fallback to shell command
            return self::convertWithShell($sourcePath, $destPath, $quality);
        } catch (\Exception $e) {
            \Log::error('WebP conversion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Convert using GD Library
     */
    private static function convertWithGD(string $source, string $dest, string $extension, int $quality): bool
    {
        $image = null;

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'png':
                $image = imagecreatefrompng($source);
                // Preserve transparency
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'gif':
                $image = imagecreatefromgif($source);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Check if WebP support is available
        if (!function_exists('imagewebp')) {
            imagedestroy($image);
            return false;
        }

        $result = imagewebp($image, $dest, $quality);
        imagedestroy($image);

        return $result;
    }

    /**
     * Convert using ImageMagick
     */
    private static function convertWithImagick(string $source, string $dest, int $quality): bool
    {
        $imagick = new \Imagick($source);
        $imagick->setImageFormat('webp');
        $imagick->setImageCompressionQuality($quality);
        $result = $imagick->writeImage($dest);
        $imagick->destroy();

        return $result;
    }

    /**
     * Convert using shell command (ImageMagick or cwebp)
     */
    private static function convertWithShell(string $source, string $dest, int $quality): bool
    {
        // Try ImageMagick convert command
        $convertCmd = "convert " . escapeshellarg($source) . " -quality $quality " . escapeshellarg($dest) . " 2>&1";
        exec($convertCmd, $output, $returnCode);

        if ($returnCode === 0 && file_exists($dest)) {
            return true;
        }

        // Try cwebp command
        $cwebpCmd = "cwebp -q $quality " . escapeshellarg($source) . " -o " . escapeshellarg($dest) . " 2>&1";
        exec($cwebpCmd, $output, $returnCode);

        return $returnCode === 0 && file_exists($dest);
    }

    /**
     * Get WebP version of an image path (for templates)
     *
     * @param string $imagePath Original image path
     * @return string|null WebP path if exists, null otherwise
     */
    public static function getWebpPath(string $imagePath): ?string
    {
        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $imagePath);
        
        if ($webpPath !== $imagePath && file_exists(public_path($webpPath))) {
            return $webpPath;
        }

        return null;
    }

    /**
     * Delete image and its WebP version
     *
     * @param string $imagePath Image path relative to public
     * @return void
     */
    public static function delete(string $imagePath): void
    {
        $fullPath = public_path($imagePath);
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Also delete WebP version if exists
        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $fullPath);
        if ($webpPath !== $fullPath && file_exists($webpPath)) {
            unlink($webpPath);
        }
    }
}
