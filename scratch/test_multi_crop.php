<?php
$im1 = imagecreatefromwebp('public/promonov1.webp');
$im2 = imagecreatefromwebp('public/promonov2.webp');

foreach ([180, 200, 220] as $h) {
    // Generate scaled for 390x$h
    $targetW = 390;
    $targetH = $h;
    
    // Scale for im1
    $scale1 = max($targetW / 1920, $targetH / 680);
    $newW1 = (int)round(1920 * $scale1);
    $newH1 = (int)round(680 * $scale1);
    $cropX1 = (int)round(($newW1 - $targetW) / 2);
    $cropY1 = (int)round(($newH1 - $targetH) / 2);
    
    $thumb1 = imagecreatetruecolor($targetW, $targetH);
    $temp1 = imagecreatetruecolor($newW1, $newH1);
    imagecopyresampled($temp1, $im1, 0, 0, 0, 0, $newW1, $newH1, 1920, 680);
    imagecopy($thumb1, $temp1, 0, 0, $cropX1, $cropY1, $targetW, $targetH);
    imagewebp($thumb1, "public/test_im1_{$h}.webp", 90);
    
    // Scale for im2
    $scale2 = max($targetW / 1920, $targetH / 680);
    $newW2 = (int)round(1920 * $scale2);
    $newH2 = (int)round(680 * $scale2);
    $cropX2 = (int)round(($newW2 - $targetW) / 2);
    $cropY2 = (int)round(($newH2 - $targetH) / 2);
    
    $thumb2 = imagecreatetruecolor($targetW, $targetH);
    $temp2 = imagecreatetruecolor($newW2, $newH2);
    imagecopyresampled($temp2, $im2, 0, 0, 0, 0, $newW2, $newH2, 1920, 680);
    imagecopy($thumb2, $temp2, 0, 0, $cropX2, $cropY2, $targetW, $targetH);
    imagewebp($thumb2, "public/test_im2_{$h}.webp", 90);
}
echo "Crops done.\n";

