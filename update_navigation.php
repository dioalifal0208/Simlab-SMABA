<?php

$file = 'resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

// Update widths
$content = str_replace("sidebarCollapsed ? '80px' : '260px'", "sidebarCollapsed ? '72px' : '240px'", $content);
$content = str_replace("sidebarCollapsed && window.innerWidth >= 1024 ? '80px' : '260px'", "sidebarCollapsed && window.innerWidth >= 1024 ? '72px' : '240px'", $content);

// Update search bar width
$content = str_replace('flex-1 max-w-md mx-auto', 'flex-1 max-w-xl mx-auto', $content);

// Update active state CSS (hex colors)
$content = str_replace('background-color: #eff6ff !important;', 'background-color: #dcfce7 !important; /* green-100 */', $content);
$content = str_replace('color: #2563eb !important;', 'color: #16a34a !important; /* green-600 */', $content);
$content = str_replace('background-color: #2563eb;', 'background-color: #16a34a; /* green-600 */', $content);
$content = str_replace('color: #2563eb;', 'color: #16a34a;', $content);

// Update tailwind green classes
$content = str_replace('bg-blue-', 'bg-green-', $content);
$content = str_replace('text-blue-', 'text-green-', $content);
$content = str_replace('border-blue-', 'border-green-', $content);
$content = str_replace('ring-blue-', 'ring-green-', $content);

file_put_contents($file, $content);
echo "Navigation updated";

