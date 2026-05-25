<?php
define('IS_VERCEL', true);

// Create required Vercel directories in /tmp
$dirs = [
    '/tmp/storage',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
