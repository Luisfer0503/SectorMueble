<?php
$im2 = imagecreatefromwebp('public/promonov2.webp');
$im1 = imagecreatefromwebp('public/promonov1.webp');

foreach ([200, 220, 240, 260] as $h) {
    $targetW = 390;
    $targetH = $h;
    
    // Scale to cover 390x$h
    $scale = max($targetW / 1920, $targetH / 680);
    $scaledW = (int)round(1920 * $scale);
    $scaledH = (int)round(680 * $scale);
    
    // With object-position: 85% center (bias towards right side where text is)
    $excessW = $scaledW - $targetW;
    $cropX = (int)round($excessW * 0.85); // 85% cut from left, only 15% from right
    
    $thumb2 = imagecreatetruecolor($targetW, $targetH);
    $temp2 = imagecreatetruecolor($scaledW, $scaledH);
    imagecopyresampled($temp2, $im2, 0, 0, 0, 0, $scaledW, $scaledH, 1920, 680);
    imagecopy($thumb2, $temp2, 0, 0, $cropX, 0, $targetW, $targetH);
    imagewebp($thumb2, "public/test_pos_im2_{$h}.webp", 90);
    
    $thumb1 = imagecreatetruecolor($targetW, $targetH);
    $temp1 = imagecreatetruecolor($scaledW, $scaledH);
    imagecopyresampled($temp1, $im1, 0, 0, 0, 0, $scaledW, $scaledH, 1920, 680);
    imagecopy($thumb1, $temp1, 0, 0, $cropX, 0, $targetW, $targetH);
    imagewebp($thumb1, "public/test_pos_im1_{$h}.webp", 90);
}
echo "Position tests generated.\n";

