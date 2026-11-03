<?php
$im2 = imagecreatefromwebp('public/promonov2.webp');
$srcW = imagesx($im2);
$srcH = imagesy($im2);

// Target 390x260 (typical mobile)
$targetW = 390;
$targetH = 260;

// Test 1: object-center
$scale = max($targetW / $srcW, $targetH / $srcH);
$newW = (int)round($srcW * $scale);
$newH = (int)round($srcH * $scale);
$cropX = (int)round(($newW - $targetW) / 2);

$thumb1 = imagecreatetruecolor($targetW, $targetH);
$temp = imagecreatetruecolor($newW, $newH);
imagecopyresampled($temp, $im2, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);
imagecopy($thumb1, $temp, 0, 0, $cropX, 0, $targetW, $targetH);
imagewebp($thumb1, 'public/test_crop_center.webp', 90);

// Test 2: object-right
$thumb2 = imagecreatetruecolor($targetW, $targetH);
imagecopy($thumb2, $temp, 0, 0, $newW - $targetW, 0, $targetW, $targetH);
imagewebp($thumb2, 'public/test_crop_right.webp', 90);

echo "Test crops generated.\n";

