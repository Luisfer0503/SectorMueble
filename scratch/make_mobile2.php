<?php
$im2 = imagecreatefromwebp('public/promonov2.webp');

// Target mobile resolution: 800 x 550 (~ 16:11, perfect for 390px mobile where height will be ~270px)
$dstW = 800;
$dstH = 550;
$out2 = imagecreatetruecolor($dstW, $dstH);

// Background wall color from im2
$bg = imagecolorallocate($out2, 248, 239, 227);
imagefill($out2, 0, 0, $bg);

// Copy wall gradient from right side
imagecopyresampled($out2, $im2, 0, 0, 800, 0, $dstW, $dstH, 1120, 680);

// Copy puff (left side): from im2 (0 to 620, height 680)
// Scaled height: 550
$puffSrcW = 600;
$puffSrcH = 680;
$puffDstW = (int)round($puffSrcW * ($dstH / $puffSrcH));
$puffDstH = $dstH;
imagecopyresampled($out2, $im2, 0, 0, 0, 0, $puffDstW, $puffDstH, $puffSrcW, $puffSrcH);

// Copy promo box (right side): from im2 (1000 to 1820, Y=140 to 520)
// Box is 820 x 380
$boxSrcX = 1000;
$boxSrcY = 140;
$boxSrcW = 820;
$boxSrcH = 380;

// We want box to fit in the right half of the mobile banner
// Say width ~ 450, height ~ 210
$boxScale = 0.56;
$boxDstW = (int)round($boxSrcW * $boxScale);
$boxDstH = (int)round($boxSrcH * $boxScale);
$boxDstX = $dstW - $boxDstW - 15;
$boxDstY = (int)round(($dstH - $boxDstH) / 2) - 10;

imagecopyresampled($out2, $im2, $boxDstX, $boxDstY, $boxSrcX, $boxSrcY, $boxDstW, $boxDstH, $boxSrcW, $boxSrcH);

imagewebp($out2, 'public/promonov2_mobile.webp', 92);
echo "promonov2_mobile.webp generated.\n";

