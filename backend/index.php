<?php
require __DIR__ . '/vendor/autoload.php';

// Global CORS headers
Flight::before('start', function (&$params, &$output) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
});

// Handle preflight OPTIONS requests
Flight::route('OPTIONS *', function () {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    Flight::halt(200);
});


// Register all routes
require_once __DIR__ . '/routes/auth.php';
require_once __DIR__ . '/routes/userRoutes.php';
require_once __DIR__ . '/routes/employeeRoutes.php';
require_once __DIR__ . '/routes/customerRoutes.php';
require_once __DIR__ . '/routes/carRoutes.php';
require_once __DIR__ . '/routes/salesRoutes.php';



Flight::start();
