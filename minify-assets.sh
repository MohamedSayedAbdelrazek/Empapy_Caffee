#!/bin/bash
# ========================================
# Empapy Caffe - CSS & JS Minification Script
# Minifies all custom CSS/JS files in public/ for production
# Run: bash minify-assets.sh
# ========================================

set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
CSS_DIR="$SCRIPT_DIR/public/css"
JS_DIR="$SCRIPT_DIR/public/js"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

echo -e "${CYAN}========================================${NC}"
echo -e "${CYAN}  Empapy Caffe - Asset Minification     ${NC}"
echo -e "${CYAN}========================================${NC}"

TOTAL_SAVED=0

# Minify CSS files
echo -e "\n${YELLOW}📦 Minifying CSS files...${NC}"
for file in "$CSS_DIR"/*.css; do
    filename=$(basename "$file")
    # Skip already minified files
    if [[ "$filename" == *.min.css ]]; then
        continue
    fi
    
    BEFORE=$(stat -c%s "$file" 2>/dev/null || stat -f%z "$file")
    npx cleancss -o "$file" "$file" 2>/dev/null
    AFTER=$(stat -c%s "$file" 2>/dev/null || stat -f%z "$file")
    SAVED=$((BEFORE - AFTER))
    TOTAL_SAVED=$((TOTAL_SAVED + SAVED))
    PERCENT=$(( (SAVED * 100) / BEFORE ))
    echo -e "  ${GREEN}✓${NC} $filename: ${BEFORE}B → ${AFTER}B (saved ${SAVED}B / ${PERCENT}%)"
done

# Minify JS files
echo -e "\n${YELLOW}📦 Minifying JS files...${NC}"
for file in "$JS_DIR"/*.js; do
    filename=$(basename "$file")
    # Skip already minified files
    if [[ "$filename" == *.min.js ]]; then
        continue
    fi
    
    BEFORE=$(stat -c%s "$file" 2>/dev/null || stat -f%z "$file")
    npx uglifyjs "$file" -o "$file" -c -m 2>/dev/null
    AFTER=$(stat -c%s "$file" 2>/dev/null || stat -f%z "$file")
    SAVED=$((BEFORE - AFTER))
    TOTAL_SAVED=$((TOTAL_SAVED + SAVED))
    PERCENT=$(( (SAVED * 100) / BEFORE ))
    echo -e "  ${GREEN}✓${NC} $filename: ${BEFORE}B → ${AFTER}B (saved ${SAVED}B / ${PERCENT}%)"
done

echo -e "\n${CYAN}========================================${NC}"
echo -e "${GREEN}✅ Total saved: ${TOTAL_SAVED} bytes ($((TOTAL_SAVED / 1024)) KB)${NC}"
echo -e "${CYAN}========================================${NC}"
