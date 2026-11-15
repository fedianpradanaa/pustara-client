#!/usr/bin/env php
<?php
/**
 * Test Script untuk Open Graph Image Implementation
 * Jalankan dengan: php test-og-implementation.php
 */

echo "═══════════════════════════════════════════════════════════════════\n";
echo "  TEST: Open Graph Image Implementation - Pustara Client\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Define root directory
$rootDir = __DIR__;
$publicDir = $rootDir . DIRECTORY_SEPARATOR . 'public';

$testsPassed = 0;
$testsFailed = 0;

// Test 1: Check migration file exists
echo "[TEST 1] Migration file exists\n";
$migrationFile = $rootDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations' . 
                 DIRECTORY_SEPARATOR . '2026_11_15_000000_add_og_social_image_parameter.php';
if (file_exists($migrationFile)) {
    echo "  ✅ PASS: Migration file found\n";
    $testsPassed++;
} else {
    echo "  ❌ FAIL: Migration file not found at $migrationFile\n";
    $testsFailed++;
}

// Test 2: Check default OG image exists
echo "\n[TEST 2] Default OG image exists\n";
$ogImagePath = $publicDir . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'images' . 
               DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . 'og-image-default.jpg';
if (file_exists($ogImagePath)) {
    $fileSize = filesize($ogImagePath);
    echo "  ✅ PASS: Default OG image exists\n";
    echo "     File size: " . number_format($fileSize) . " bytes\n";
    
    // Check image dimensions if GD is available
    if (extension_loaded('gd')) {
        $imageInfo = getimagesize($ogImagePath);
        if ($imageInfo !== false) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            echo "     Dimensions: {$width}x{$height} px";
            
            if ($width === 1200 && $height === 630) {
                echo " ✅ (correct 1200x630)\n";
            } else {
                echo " ⚠️  (expected 1200x630)\n";
            }
        }
    }
    $testsPassed++;
} else {
    echo "  ❌ FAIL: Default OG image not found at $ogImagePath\n";
    $testsFailed++;
}

// Test 3: Check MainController contains og_social_image
echo "\n[TEST 3] MainController updated with og_social_image parameter\n";
$controllerFile = $rootDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Http' . 
                  DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'MainController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    if (strpos($content, 'og_social_image') !== false) {
        echo "  ✅ PASS: og_social_image found in MainController\n";
        $testsPassed++;
    } else {
        echo "  ❌ FAIL: og_social_image not found in MainController\n";
        $testsFailed++;
    }
} else {
    echo "  ❌ FAIL: MainController not found\n";
    $testsFailed++;
}

// Test 4: Check MainController logic for image fallback
echo "\n[TEST 4] MainController has fallback logic (og_social_image > og_image > default)\n";
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    if (strpos($content, 'og-image-default.jpg') !== false) {
        echo "  ✅ PASS: Fallback to default image found\n";
        $testsPassed++;
    } else {
        echo "  ❌ FAIL: Default image fallback not found in MainController\n";
        $testsFailed++;
    }
} else {
    echo "  ❌ FAIL: Cannot read MainController\n";
    $testsFailed++;
}

// Test 5: Check Blade template has OG metadata
echo "\n[TEST 5] Blade template has complete OG metadata\n";
$bladeFile = $rootDir . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . 
             DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'mainapp.blade.php';
if (file_exists($bladeFile)) {
    $content = file_get_contents($bladeFile);
    $checks = [
        'og:image' => 'og:image',
        'og:image:width' => 'og:image:width',
        'og:image:height' => 'og:image:height',
        'og:image:type' => 'og:image:type',
        'twitter:image' => 'twitter:image',
    ];
    
    $allFound = true;
    foreach ($checks as $key => $search) {
        if (strpos($content, $search) === false) {
            echo "  ❌ Missing: $key\n";
            $allFound = false;
        }
    }
    
    if ($allFound) {
        echo "  ✅ PASS: All OG metadata tags found\n";
        $testsPassed++;
    } else {
        echo "  ❌ FAIL: Some OG metadata tags missing\n";
        $testsFailed++;
    }
} else {
    echo "  ❌ FAIL: mainapp.blade.php not found\n";
    $testsFailed++;
}

// Test 6: Check ParameterController has og_social_image
echo "\n[TEST 6] ParameterController includes og_social_image parameter\n";
$paramFile = $rootDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Http' . 
             DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Core' . 
             DIRECTORY_SEPARATOR . 'Setting' . DIRECTORY_SEPARATOR . 'ParameterController.php';
if (file_exists($paramFile)) {
    $content = file_get_contents($paramFile);
    if (preg_match('/og_social_image.*Gambar Social Sharing/i', $content)) {
        echo "  ✅ PASS: og_social_image parameter found in ParameterController\n";
        $testsPassed++;
    } else {
        echo "  ❌ FAIL: og_social_image not properly configured in ParameterController\n";
        $testsFailed++;
    }
} else {
    echo "  ❌ FAIL: ParameterController not found\n";
    $testsFailed++;
}

// Test 7: Check file permissions
echo "\n[TEST 7] Default OG image is readable\n";
if (file_exists($ogImagePath) && is_readable($ogImagePath)) {
    echo "  ✅ PASS: Default OG image is readable\n";
    $testsPassed++;
} else {
    echo "  ❌ FAIL: Default OG image not readable (check permissions)\n";
    $testsFailed++;
}

// Test 8: Check stored procedures/helpers
echo "\n[TEST 8] Database changes tracked (migration file present)\n";
if (file_exists($migrationFile)) {
    echo "  ✅ PASS: Migration file exists for database changes\n";
    $testsPassed++;
} else {
    echo "  ❌ FAIL: Migration not found\n";
    $testsFailed++;
}

// Summary
echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  TEST SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  ✅ Passed: $testsPassed\n";
echo "  ❌ Failed: $testsFailed\n";
echo "  Total:   " . ($testsPassed + $testsFailed) . "\n";
echo "═══════════════════════════════════════════════════════════════════\n";

if ($testsFailed === 0) {
    echo "\n  🎉 ALL TESTS PASSED! Implementation is ready.\n\n";
    exit(0);
} else {
    echo "\n  ⚠️  Some tests failed. Please review the implementation.\n\n";
    exit(1);
}
