<?php
$im2 = imagecreatefromwebp('public/promonov2.webp');

// Target mobile dimensions: 800x650 (approx 4:3.25, perfect for mobile viewport)
$outW = 800;
$outH = 650;
$out = imagecreatetruecolor($outW, $outH);

// Background color in im2 is approx #FDF8F0 / #F8ECE0
$bgCol = imagecolorallocate($out, 248, 241, 232);
imagefill($out, 0, 0, $bgCol);

// Let's copy background gradient or ambiance
// 1. Promo box: in im2, from X=1000 to 1820 (width 820), Y=180 to 490 (height 310)
// Let's extract promo box
$boxW = 820;
$boxH = 320;
$boxScale = 0.85; // scale to fit 800px width with padding: 820 * 0.85 = 697px
$dstBoxW = (int)($boxW * $boxScale);
$dstBoxH = (int)($boxH * $boxScale);
$dstBoxX = (int)(($outW - $dstBoxW) / 2);
$dstBoxY = 30;

imagecopyresampled($out, $im2, $dstBoxX, $dstBoxY, 1000, 175, $dstBoxW, $dstBoxH, $boxW, $boxH);

// 2. Puff: in im2, from X=0 to 760, Y=0 to 680
// Let's scale puff to fit width 700px, height ~ 360px
$puffSrcW = 760;
$puffSrcH = 680;
$puffScale = 0.52;
$dstPuffW = (int)($puffSrcW * $puffScale);
$dstPuffH = (int)($puffSrcH * $puffScale);
$dstPuffX = (int)(($outW - $dstPuffW) / 2);
$dstPuffY = $outH - $dstPuffH;

imagecopyresampled($out, $im2, $dstPuffX, $dstPuffY, 0, 0, $dstPuffW, $dstPuffH, $puffSrcW, $puffSrcH);

imagewebp($out, 'public/test_mobile_compose.webp', 92);
echo "Composed mobile preview generated.\n";

