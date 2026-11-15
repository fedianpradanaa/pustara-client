#!/usr/bin/env bash
# Script untuk test OG image via curl dan cek metadata HTML

echo "======================================================================"
echo "  Test Open Graph Metadata via HTTP"
echo "======================================================================"
echo ""

# Note: Replace with actual URL
WEBSITE_URL="https://pustara.pustakanusantara.id"

echo "Testing URL: $WEBSITE_URL"
echo ""

echo "[1] Checking og:image metadata:"
curl -s "$WEBSITE_URL" | grep -i "og:image" | head -10

echo ""
echo "[2] Checking og:title metadata:"
curl -s "$WEBSITE_URL" | grep -i "og:title" | head -5

echo ""
echo "[3] Checking og:description metadata:"
curl -s "$WEBSITE_URL" | grep -i "og:description" | head -5

echo ""
echo "[4] Checking twitter:image metadata:"
curl -s "$WEBSITE_URL" | grep -i "twitter:image" | head -5

echo ""
echo "[5] Full OpenGraph tags:"
curl -s "$WEBSITE_URL" | grep -i "og:" | head -20

echo ""
echo "======================================================================"
echo "  If images show default (og-image-default.jpg):"
echo "  - Client needs to upload custom social image"
echo "  - Or set og_social_image parameter in admin panel"
echo "======================================================================"
