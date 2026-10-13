<?php

// 1. Prepare writable storage and cache directories in /tmp for Vercel Serverless environment
$tmpDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Redirect all writable cache paths away from read-only /var/task to /tmp
putenv('APP_STORAGE=/tmp/storage');
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_SERVER['APP_STORAGE'] = '/tmp/storage';

putenv('APP_PACKAGES_CACHE=/tmp/cache/packages.php');
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/cache/packages.php';
$_SERVER['APP_PACKAGES_CACHE'] = '/tmp/cache/packages.php';

putenv('APP_SERVICES_CACHE=/tmp/cache/services.php');
$_ENV['APP_SERVICES_CACHE'] = '/tmp/cache/services.php';
$_SERVER['APP_SERVICES_CACHE'] = '/tmp/cache/services.php';

putenv('APP_CONFIG_CACHE=/tmp/cache/config.php');
$_ENV['APP_CONFIG_CACHE'] = '/tmp/cache/config.php';
$_SERVER['APP_CONFIG_CACHE'] = '/tmp/cache/config.php';

putenv('APP_ROUTES_CACHE=/tmp/cache/routes.php');
$_ENV['APP_ROUTES_CACHE'] = '/tmp/cache/routes.php';
$_SERVER['APP_ROUTES_CACHE'] = '/tmp/cache/routes.php';

putenv('APP_EVENTS_CACHE=/tmp/cache/events.php');
$_ENV['APP_EVENTS_CACHE'] = '/tmp/cache/events.php';
$_SERVER['APP_EVENTS_CACHE'] = '/tmp/cache/events.php';

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

putenv('SESSION_DRIVER=cookie');
$_ENV['SESSION_DRIVER'] = 'cookie';
$_SERVER['SESSION_DRIVER'] = 'cookie';

putenv('CACHE_STORE=array');
$_ENV['CACHE_STORE'] = 'array';
$_SERVER['CACHE_STORE'] = 'array';

putenv('LOG_CHANNEL=stderr');
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

putenv('VERCEL=1');
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';

// 3. Catch startup exceptions to diagnose issues if they happen
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Server Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (" . $e->getLine() . ")</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
