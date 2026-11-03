<?php
$im1 = imagecreatefromwebp('public/promonov1.webp');
$im2 = imagecreatefromwebp('public/promonov2.webp');

// Let's test a 390px wide screen with different heights: 180, 200, 220, 250
foreach ([180, 200, 220, 250, 280] as $h) {
    // If scaled to cover 390x$h
    $scale = max(390 / 1920, $h / 680);
    $scaledW = 1920 * $scale;
    $scaledH = 680 * $scale;
    $cropX = ($scaledW - 390) / 2;
    $cropY = ($scaledH - $h) / 2;
    
    // In original coordinates:
    $origLeftVisible = $cropX / $scale;
    $origRightVisible = (1920 - ($cropX / $scale));
    
    echo "Height: {$h}px | Scale: " . round($scale, 3) . " | Visible X range: " . round($origLeftVisible) . " to " . round($origRightVisible) . "\n";
}

