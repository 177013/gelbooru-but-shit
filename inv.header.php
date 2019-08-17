<?php
require __DIR__ . "/config.php";
require __DIR__ . "/functions.global.php";
require __DIR__ . "/autoload.php";
$db = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db) or die("Ooops?");
$db->set_charset('utf8');
require __DIR__ . "/auto_login.php";

$thumbnailManager = new App\ThumbnailManager(PUBLIC_ROOT, $thumbnail_folder);