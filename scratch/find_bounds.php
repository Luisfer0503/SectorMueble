<?php
$im1 = imagecreatefromwebp('public/promonov1.webp');
$im2 = imagecreatefromwebp('public/promonov2.webp');

// For im2, background color is around the edges. Let's find where the box "Todos los productos" and "30% De descuento" ends on the right:
// In im2, the rightmost white text or brown border
$w = imagesx($im2);
$h = imagesy($im2);

// Check pixels from right (w-1 down to 0) to find rightmost feature of the box
for ($x = $w - 1; $x >= 0; $x--) {
    for ($y = 100; $y < 500; $y++) {
        $rgb = imagecolorat($im2, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        // Background on the right is warm ivory: r ~ 254, g ~ 247, b ~ 238
        if ($r < 235 || $g < 225 || $b < 215) {
            echo "Im2 rightmost feature at X = $x, Y = $y (R:$r G:$g B:$b)\n";
            break 2;
        }
    }
}

// Leftmost feature of im2 (the puff)
for ($x = 0; $x < $w; $x++) {
    for ($y = 100; $y < 600; $y++) {
        $rgb = imagecolorat($im2, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r < 235 || $g < 225 || $b < 215) {
            echo "Im2 leftmost feature at X = $x, Y = $y\n";
            break 2;
        }
    }
}

// Same for im1
for ($x = $w - 1; $x >= 0; $x--) {
    for ($y = 100; $y < 500; $y++) {
        $rgb = imagecolorat($im1, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        // Background on the right of im1 is terracotta/beige
        if ($r > 240 && $g > 240 && $b > 240) { // white line or text
            echo "Im1 rightmost white line/text at X = $x, Y = $y\n";
            break 2;
        }
    }
}

for ($x = 0; $x < $w; $x++) {
    for ($y = 100; $y < 600; $y++) {
        $rgb = imagecolorat($im1, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if ($r > 240 && $g > 240 && $b > 240) {
            echo "Im1 leftmost white line/text at X = $x, Y = $y\n";
            break 2;
        }
    }
}

