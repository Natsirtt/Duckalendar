<!DOCTYPE html 
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Duckalendar - <?php if (isset($title)) { echo $title; } ?></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="/Duckalendar/css/style.css" />

        <script src="jquery-1.9.1.min.js" type="text/javascript"></script>
    </head>
    <body>
        <p><a href="/Duckalendar/"><img src="/Duckalendar/images/theDuck.png" alt="theDuck" id="theDuck" /></a></p>
        <div id="notification">
            <?php 
            if (isset($notification)) {
                echo '<p id="notificationMsg">'.$notification.'. Coin.</p>';
            }
            ?>
        </div>