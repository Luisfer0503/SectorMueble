<?php
$im2 = imagecreatefromwebp('public/promonov2.webp');
// Let's get the exact pixel coords:
// 1. Where does the puff end on the right?
// Let's check X columns from 400 to 1000
for ($x = 400; $x < 1100; $x += 20) {
    // Check vertical line at Y=350 (middle of height)
    $rgb = imagecolorat($im2, $x, 350);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    // Also at floor Y=550 (carpet)
    $rgbFloor = imagecolorat($im2, $x, 550);
    $rf = ($rgbFloor >> 16) & 0xFF;
    $gf = ($rgbFloor >> 8) & 0xFF;
    $bf = $rgbFloor & 0xFF;
    echo "X=$x | Mid (Y=350): $r,$g,$b | Floor (Y=550): $rf,$gf,$bf\n";
}

