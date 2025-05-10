<?php
require __DIR__ . '/vendor/autoload.php';

// Register all routes
require_once __DIR__ . '/routes/userRoutes.php';
require_once __DIR__ . '/routes/employeeRoutes.php';
require_once __DIR__ . '/routes/customerRoutes.php';
require_once __DIR__ . '/routes/carRoutes.php';
require_once __DIR__ . '/routes/salesRoutes.php';



Flight::start();
