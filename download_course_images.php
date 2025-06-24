<?php

$courseImages = [
    // Instructor Images
    'images/instructors/dr-sarah-chen.jpg' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=388&q=80',
    'images/instructors/prof-james-martinez.jpg' => 'https://images.unsplash.com/photo-1556157382-97eda2d62296?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80',
    'images/instructors/emily-rodriguez.jpg' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=461&q=80',
    
    // Web Development
    'images/courses/web/javascript-course.jpg' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a',
    'images/courses/web/laravel-vue-course.jpg' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
    'images/courses/web/responsive-design-course.jpg' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8',
    
    // Mobile Development
    'images/courses/mobile/ios-swift-course.jpg' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c',
    'images/courses/mobile/android-kotlin-course.jpg' => 'https://images.unsplash.com/photo-1607252650355-f7fd0460ccdb',
    'images/courses/mobile/react-native-course.jpg' => 'https://images.unsplash.com/photo-1618761714954-0b8cd0026356',
    
    // Data Science
    'images/courses/data/python-data-science.jpg' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71',
    'images/courses/data/machine-learning.jpg' => 'https://images.unsplash.com/photo-1527474305487-b87b222841cc',
    'images/courses/data/deep-learning.jpg' => 'https://images.unsplash.com/photo-1555949963-ff9fe0c870eb',
    
    // Design
    'images/courses/design/uiux-design.jpg' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5',
    'images/courses/design/photoshop-course.jpg' => 'https://images.unsplash.com/photo-1572044162444-ad60f128bdea',
    'images/courses/design/after-effects.jpg' => 'https://images.unsplash.com/photo-1550784343-6bd0ce5d600b',
    
    // Business
    'images/courses/business/entrepreneurship.jpg' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f',
    'images/courses/business/project-management.jpg' => 'https://images.unsplash.com/photo-1552664730-d307ca884978',
    'images/courses/business/financial-analysis.jpg' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f',
    
    // Marketing
    'images/courses/marketing/digital-marketing.jpg' => 'https://images.unsplash.com/photo-1533750349088-cd871a92f312',
    'images/courses/marketing/social-media.jpg' => 'https://images.unsplash.com/photo-1562577309-4932fdd64cd1',
    'images/courses/marketing/seo-course.jpg' => 'https://images.unsplash.com/photo-1571677246347-5040036b95cc'
];

foreach ($courseImages as $localPath => $imageUrl) {
    $directory = dirname(__DIR__ . '/public/' . $localPath);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    
    $imageContent = file_get_contents($imageUrl);
    if ($imageContent !== false) {
        file_put_contents(__DIR__ . '/public/' . $localPath, $imageContent);
        echo "Downloaded: $localPath\n";
    } else {
        echo "Failed to download: $imageUrl\n";
    }
} 