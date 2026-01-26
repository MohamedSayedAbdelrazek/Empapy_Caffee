#!/bin/bash
# optimize_icons.sh

echo "🔍 Searching for large icons (>500KB)..."

# Find all PNGs larger than 500KB
find public/icons -name "*.png" -size +500k | while read file; do
    echo "----------------------------------------"
    echo "🖼️  Processing: $file"
    
    # Get original size
    ORIG_SIZE=$(du -h "$file" | cut -f1)
    echo "📊 Original Size: $ORIG_SIZE"
    
    mv "$file" "${file}_orig.png"
    
    # Try ffmpeg first (usually better for PNG compression)
    if command -v ffmpeg &> /dev/null; then
        # -pred mixed: good for png
        # -compression_level 100: max compression (slow but effective)
        ffmpeg -hide_banner -loglevel error -y -i "${file}_orig.png" -compression_level 100 -pred mixed "$file"
    elif command -v convert &> /dev/null; then
        # ImageMagick fallback
        convert "${file}_orig.png" -strip -quality 95 -depth 8 "$file"
    else
        echo "❌ No optimization tool found (ffmpeg or imagemagick needed)."
        mv "${file}_orig.png" "$file"
        continue
    fi
    
    # Compare results
    if [ -f "$file" ]; then
        NEW_SIZE=$(du -h "$file" | cut -f1)
        
        # Check if actual bytes are smaller (du -h is approximate)
        BYTES_ORIG=$(stat -c%s "${file}_orig.png")
        BYTES_NEW=$(stat -c%s "$file")
        
        if [ $BYTES_NEW -lt $BYTES_ORIG ]; then
            echo "✅ Optimized! $ORIG_SIZE -> $NEW_SIZE"
            rm "${file}_orig.png"
        else
            echo "⚠️  No improvement. Reverting..."
            mv "${file}_orig.png" "$file"
        fi
    else
        echo "❌ Optimization failed. Reverting..."
        mv "${file}_orig.png" "$file"
    fi
done

echo "----------------------------------------"
echo "✨ Optimization Complete!"
