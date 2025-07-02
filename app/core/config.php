<?php

define('VERSION', '0.7.0');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APPS', ROOT . DS . 'app');
define('CORE', ROOT . DS . 'core');
define('LIBS', ROOT . DS . 'lib');
define('MODELS', ROOT . DS . 'models');
define('VIEWS', ROOT . DS . 'views');
define('CONTROLLERS', ROOT . DS . 'controllers');
define('LOGS', ROOT . DS . 'logs');	
define('FILES', ROOT . DS. 'files');

// ---------------------  DATABASE CONFIGURATION -------------------------
// Try PostgreSQL first (Replit), fallback to MySQL (filess.io)
if (isset($_ENV['DATABASE_URL'])) {
    // PostgreSQL (Replit) configuration
    $db_url = parse_url($_ENV['DATABASE_URL']);
    define('DB_TYPE',         'pgsql');
    define('DB_HOST',         $db_url['host']);
    define('DB_USER',         $db_url['user']); 
    define('DB_PASS',         $db_url['pass']);
    define('DB_DATABASE',     ltrim($db_url['path'], '/'));
    define('DB_PORT',         $db_url['port'] ?? 5432);
} else {
    // MySQL (filess.io) configuration
    define('DB_TYPE',         'mysql');
    define('DB_HOST',         'dpblo.h.filess.io');
    define('DB_USER',         '4806_sentenceby'); 
    define('DB_PASS',         $_ENV['DB_PASS']);
    define('DB_DATABASE',     '4806_sentenceby');
    define('DB_PORT',         '3305');
}