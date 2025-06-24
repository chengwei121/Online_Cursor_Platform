<?php
// Create a 800x450 image (16:9 aspect ratio)
$width = 800;
$height = 450;
$image = imagecreatetruecolor($width, $height);

// Set background color (light gray)
$bgColor = imagecolorallocate($image, 240, 240, 240);
imagefill($image, 0, 0, $bgColor);

// Set text color (dark gray)
$textColor = imagecolorallocate($image, 120, 120, 120);

// Add text
$text = "Course Placeholder";
imagestring($image, 5, ($width - imagefontwidth(5) * strlen($text)) / 2, 
           ($height - imagefontheight(5)) / 2, $text, $textColor);

// Save the images without sending headers
imagejpeg($image, __DIR__ . '/placeholder.jpg', 90);
imagejpeg($image, __DIR__ . '/default-course.jpg', 90);

imagedestroy($image);
echo "Placeholder images generated successfully!";
?>