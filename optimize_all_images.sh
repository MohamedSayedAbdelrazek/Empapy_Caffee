#!/bin/bash
# ===========================================
# Empapy Caffe - Image Optimization Script
# ===========================================
# This script converts all images to WebP format
# and optimizes quality for better page speed

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}   Empapy Caffe - Image Optimization Script     ${NC}"
echo -e "${BLUE}================================================${NC}"

# Check if required tools are installed
check_tools() {
    echo -e "${YELLOW}Checking required tools...${NC}"
    
    # Check for ImageMagick
    if command -v convert &> /dev/null; then
        echo -e "${GREEN}✅ ImageMagick (convert) is installed${NC}"
        CONVERTER="imagemagick"
    elif command -v cwebp &> /dev/null; then
        echo -e "${GREEN}✅ cwebp is installed${NC}"
        CONVERTER="cwebp"
    else
        echo -e "${RED}❌ No image converter found!${NC}"
        echo -e "${YELLOW}Please install either ImageMagick or webp tools:${NC}"
        echo ""
        echo -e "  For Ubuntu/Debian:"
        echo -e "    ${GREEN}sudo apt update && sudo apt install -y imagemagick webp${NC}"
        echo ""
        echo -e "  For CentOS/RHEL:"
        echo -e "    ${GREEN}sudo yum install -y ImageMagick libwebp-tools${NC}"
        echo ""
        echo -e "  For Alpine:"
        echo -e "    ${GREEN}apk add imagemagick libwebp-tools${NC}"
        echo ""
        exit 1
    fi
}

# Function to convert image to WebP using ImageMagick
convert_to_webp_imagemagick() {
    local input_file="$1"
    local output_file="${input_file%.*}.webp"
    local quality="${2:-85}"
    
    if [ -f "$output_file" ]; then
        echo -e "${YELLOW}⏭️  Skipping (already exists): $output_file${NC}"
        return 0
    fi
    
    convert "$input_file" -quality "$quality" "$output_file" 2>/dev/null
    if [ $? -eq 0 ]; then
        local original_size=$(stat -c%s "$input_file" 2>/dev/null || stat -f%z "$input_file")
        local new_size=$(stat -c%s "$output_file" 2>/dev/null || stat -f%z "$output_file")
        local savings=$((original_size - new_size))
        local percent=$((savings * 100 / original_size))
        echo -e "${GREEN}✅ Converted: $(basename "$input_file") → $(basename "$output_file") (saved ${percent}%)${NC}"
        return 0
    else
        echo -e "${RED}❌ Failed: $input_file${NC}"
        return 1
    fi
}

# Function to convert image to WebP using cwebp
convert_to_webp_cwebp() {
    local input_file="$1"
    local output_file="${input_file%.*}.webp"
    local quality="${2:-85}"
    
    if [ -f "$output_file" ]; then
        echo -e "${YELLOW}⏭️  Skipping (already exists): $output_file${NC}"
        return 0
    fi
    
    cwebp -q "$quality" "$input_file" -o "$output_file" 2>/dev/null
    if [ $? -eq 0 ]; then
        local original_size=$(stat -c%s "$input_file" 2>/dev/null || stat -f%z "$input_file")
        local new_size=$(stat -c%s "$output_file" 2>/dev/null || stat -f%z "$output_file")
        local savings=$((original_size - new_size))
        local percent=$((savings * 100 / original_size))
        echo -e "${GREEN}✅ Converted: $(basename "$input_file") → $(basename "$output_file") (saved ${percent}%)${NC}"
        return 0
    else
        echo -e "${RED}❌ Failed: $input_file${NC}"
        return 1
    fi
}

# Main conversion function
convert_image() {
    local input_file="$1"
    local quality="${2:-85}"
    
    if [ "$CONVERTER" == "imagemagick" ]; then
        convert_to_webp_imagemagick "$input_file" "$quality"
    else
        convert_to_webp_cwebp "$input_file" "$quality"
    fi
}

# Get the base directory
BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PUBLIC_DIR="$BASE_DIR/public"

echo -e "${BLUE}Base directory: $BASE_DIR${NC}"
echo -e "${BLUE}Public directory: $PUBLIC_DIR${NC}"
echo ""

check_tools

# Counters
total_converted=0
total_skipped=0
total_failed=0
total_savings=0

echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}   Converting Images in /public/images          ${NC}"
echo -e "${BLUE}================================================${NC}"

# Convert images in /public/images
if [ -d "$PUBLIC_DIR/images" ]; then
    find "$PUBLIC_DIR/images" -maxdepth 1 -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) | while read img; do
        convert_image "$img" 85
        ((total_converted++)) || true
    done
fi

echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}   Converting Category Images                   ${NC}"
echo -e "${BLUE}================================================${NC}"

# Convert images in /public/uploads/categories
if [ -d "$PUBLIC_DIR/uploads/categories" ]; then
    find "$PUBLIC_DIR/uploads/categories" -maxdepth 1 -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) | while read img; do
        convert_image "$img" 85
        ((total_converted++)) || true
    done
fi

echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}   Converting Product Images                    ${NC}"
echo -e "${BLUE}================================================${NC}"

# Convert images in /public/uploads/products
if [ -d "$PUBLIC_DIR/uploads/products" ]; then
    find "$PUBLIC_DIR/uploads/products" -maxdepth 1 -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) | while read img; do
        convert_image "$img" 85
        ((total_converted++)) || true
    done
fi

echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}   Converting Icon Images (lower quality OK)    ${NC}"
echo -e "${BLUE}================================================${NC}"

# Convert icons with slightly higher compression
for dir in "$PUBLIC_DIR/icons/android" "$PUBLIC_DIR/icons/ios" "$PUBLIC_DIR/icons/windows11" "$PUBLIC_DIR/icons/maskable"; do
    if [ -d "$dir" ]; then
        find "$dir" -maxdepth 1 -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" \) | while read img; do
            convert_image "$img" 90
            ((total_converted++)) || true
        done
    fi
done

echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}   Summary                                      ${NC}"
echo -e "${BLUE}================================================${NC}"

# Calculate total space saved
before_size=$(find "$PUBLIC_DIR" -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) -exec stat -c%s {} + 2>/dev/null | awk '{s+=$1} END {print s}')
after_size=$(find "$PUBLIC_DIR" -type f -name "*.webp" -exec stat -c%s {} + 2>/dev/null | awk '{s+=$1} END {print s}')

before_mb=$(echo "scale=2; ${before_size:-0} / 1048576" | bc)
after_mb=$(echo "scale=2; ${after_size:-0} / 1048576" | bc)
saved_mb=$(echo "scale=2; $before_mb - $after_mb" | bc)

echo -e "${GREEN}✅ Conversion complete!${NC}"
echo -e "${BLUE}Original images total: ${before_mb:-0} MB${NC}"
echo -e "${BLUE}WebP images total: ${after_mb:-0} MB${NC}"
echo -e "${GREEN}Space saved: ~${saved_mb:-0} MB${NC}"
echo ""
echo -e "${YELLOW}Note: Original files are kept as fallback for older browsers.${NC}"
echo -e "${YELLOW}The application uses <picture> tags to serve WebP with fallback.${NC}"
