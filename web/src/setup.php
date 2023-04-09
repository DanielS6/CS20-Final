<?php

/**
 * Set up everything that we need, including loading *all source files that
 * are not entry points* because we are not using composer, and setting up
 * error reporting and sessions.
 * 
 * This file should be the first thing included in every entry point.
 */

/**
 * CONFIGURATION
 *
 * Set up configuration settings:
 *  - display all errors, including startup errors, and including all severities
 *    to help with debugging and because there are no secrets that need to be
 *    hidden
 *  - ensure server timezone is always UTC
 *  - user aborts are ignored on requests that affect the database
 */
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );
ini_set( 'date.timezone', 'UTC' );

if ( ( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) === 'POST' ) {
    ignore_user_abort( true );
}

/**
 * DEPENDENCIES
 * 
 * PROJECTNAME depends on the `mysqli` PHP extension (to interact with the
 * database) and on PHP 8.0 or later (to allow some of the PHP features used).
 */
if ( !extension_loaded( 'mysqli' ) ) {
    trigger_error( 'The `mysqli` extension is missing!', E_USER_ERROR );
}
if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
    trigger_error(
        'PHP 8.0+ is required, you are using ' . PHP_VERSION,
        E_USER_ERROR
    );
}

/**
 * FILE LOADING
 *
 * Instead of adding (relatively) complicated logic for only autoloading the
 * needed classes using `spl_autoload_register`, just load all of the classes
 * on every request.
 */
$includeFiles = [
    'HTML/HTMLBuilder.php', 'HTML/HTMLElement.php', 'HTML/HTMLPage.php',
    'Pages/SitePage.php',
    'Pages/DemoPage.php', 'Pages/LoginPage.php', 'Pages/SignUpPage.php',
];
foreach ( $includeFiles as $file ) {
    require_once $file;
}
// Avoid globals
unset( $file );
unset( $includeFiles );

// Session
session_start();
