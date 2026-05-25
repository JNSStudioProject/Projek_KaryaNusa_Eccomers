<?php
define('IS_VERCEL', true);

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
