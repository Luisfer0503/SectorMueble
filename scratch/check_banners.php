<?php
$im1 = imagecreatefromwebp('public/promonov1.webp');
$im2 = imagecreatefromwebp('public/promonov2.webp');
echo "Image 1: " . imagesx($im1) . "x" . imagesy($im1) . "\n";
echo "Image 2: " . imagesx($im2) . "x" . imagesy($im2) . "\n";

