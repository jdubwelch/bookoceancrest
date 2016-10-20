<?php

/*
|--------------------------------------------------------------------------
| Start the App
|--------------------------------------------------------------------------
|
| This file contains all the things that need to happen to start up the 
| application.
|
*/

// Test the default time zone.
date_default_timezone_set('America/Los_Angeles');

// Let's handle some errors
ini_set('display_errors', 'On');

error_reporting(E_ERROR | E_WARNING | E_PARSE);
// error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../cgi-bin/config/database.php'; 

// Start output buffering.
ob_start();

// Initialize a session.
session_start();

// Build the Request Object
$request = new \Mlaphp\Request($GLOBALS);

// Server is not set in GLOBALS for whatever reason
// Let's manually add it here ourselves
$request->server = $_SERVER;