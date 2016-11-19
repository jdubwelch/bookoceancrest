<?php
 # Script 13.1 - header.html
// This page begins the HTML header for the site.

// Check for a $page_title value.
if (!isset($page_title)) {
    $page_title = 'O C E A N  C R E S T';
}

$auth = isset($request->session['name']) AND (substr($request->server['PHP_SELF'], -10) != 'logout.php');

$page = $request->server['PHP_SELF'];
$page = substr($page, 1);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?=$page_title?></title>

        <link href="/css/app.css" rel="stylesheet" type="text/css">
    </head>

<body>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="calendar" class="navbar-brand">
                    Ocean Crest
                </a>
            </div>

            <p class="navbar-text">Signed in as <?=$name?></p>
            <ul class="nav nav-pills navbar-right">
            <?php
            if ($auth) {
                include('nav-auth.php');
            } else {
                include('nav-guest.php');
            }
            ?>
            </ul>
        </div>

    </nav>

<div class="container">
    <div class="content">
