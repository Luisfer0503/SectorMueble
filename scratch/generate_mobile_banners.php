<?php
// Let's create beautiful mobile banners: 750 x 500 (aspect 3:2, height ~260px on an iPhone 12 Pro 390px width)
// On 390px width, 750x500 is 260px height. That is almost DOUBLE the current 138px height!

// --- 1. BANNER 2 (Puff + 40%/30% discount) ---
$src2 = imagecreatefromwebp('public/promonov2.webp');
$w2 = 750;
$h2 = 500;
$mob2 = imagecreatetruecolor($w2, $h2);

// Fill with warm background tone from the wall
$bgCol2 = imagecolorat($src2, 1100, 100);
imagefill($mob2, 0, 0, $bgCol2);

// Copy background gradient from src2 (from X=850, width=1070, full height 680)
imagecopyresampled($mob2, $src2, 0, 0, 850, 0, $w2, $h2, 1070, 680);

// Copy the puff from the left of src2 (X=0 to 720, Y=0 to 680)
// We want puff on the left, scaled nicely
// Puff in mobile: width ~ 360, height ~ 340, placed at bottom-left
$puffSrcW = 700;
$puffSrcH = 680;
$puffDstH = 430;
$puffDstW = (int)round($puffSrcW * ($puffDstH / $puffSrcH));
$puffDstX = -20;
$puffDstY = $h2 - $puffDstH;
imagecopyresampled($mob2, $src2, $puffDstX, $puffDstY, 0, 0, $puffDstW, $puffDstH, $puffSrcW, $puffSrcH);

// Now copy the promo box from src2 (X=1020 to 1800, Y=145 to 500)
// Box size in src2: 780 x 355
$boxSrcX = 1020;
$boxSrcY = 145;
$boxSrcW = 785;
$boxSrcH = 355;

// Box in mobile: on the right side, large and clear!
// Width ~ 420, Height ~ 190
$boxScale = 0.52;
$boxDstW = (int)round($boxSrcW * $boxScale);
$boxDstH = (int)round($boxSrcH * $boxScale);
$boxDstX = $w2 - $boxDstW - 15;
$boxDstY = (int)round(($h2 - $boxDstH) / 2) - 15;

imagecopyresampled($mob2, $src2, $boxDstX, $boxDstY, $boxSrcX, $boxSrcY, $boxDstW, $boxDstH, $boxSrcW, $boxSrcH);

imagewebp($mob2, 'public/promonov2_mobile.webp', 95);
echo "promonov2_mobile.webp generated: {$w2}x{$h2}\n";


// --- 2. BANNER 1 (Sofas/Cama + 50%/35% discount) ---
$src1 = imagecreatefromwebp('public/promonov1.webp');
$w1 = 750;
$h1 = 500;
$mob1 = imagecreatetruecolor($w1, $h1);

// Copy background/wall from right side of src1
imagecopyresampled($mob1, $src1, 0, 0, 900, 0, $w1, $h1, 1020, 680);

// Copy Cama Aura / Sofá Milán (center furniture from src1: X=500 to 1250, Y=200 to 680)
// Or copy sofas on the left: X=0 to 950
$bedSrcX = 520;
$bedSrcY = 250;
$bedSrcW = 550;
$bedSrcH = 430;
$bedDstH = 340;
$bedDstW = (int)round($bedSrcW * ($bedDstH / $bedSrcH));
$bedDstX = 10;
$bedDstY = $h1 - $bedDstH;
imagecopyresampled($mob1, $src1, $bedDstX, $bedDstY, $bedSrcX, $bedSrcY, $bedDstW, $bedDstH, $bedSrcW, $bedSrcH);

// Copy logo SM at top left (X=60 to 200, Y=60 to 180)
imagecopyresampled($mob1, $src1, 20, 20, 60, 60, 110, 95, 140, 120);

// Copy promo box from src1 (X=1050 to 1800, Y=70 to 520)
$box1SrcX = 1050;
$box1SrcY = 70;
$box1SrcW = 760;
$box1SrcH = 450;

$box1Scale = 0.58;
$box1DstW = (int)round($box1SrcW * $box1Scale);
$box1DstH = (int)round($box1SrcH * $box1Scale);
$box1DstX = $w1 - $box1DstW - 15;
$box1DstY = (int)round(($h1 - $box1DstH) / 2);

imagecopyresampled($mob1, $src1, $box1DstX, $box1DstY, $box1SrcX, $box1SrcY, $box1DstW, $box1DstH, $box1SrcW, $box1SrcH);

imagewebp($mob1, 'public/promonov1_mobile.webp', 95);
echo "promonov1_mobile.webp generated: {$w1}x{$h1}\n";

