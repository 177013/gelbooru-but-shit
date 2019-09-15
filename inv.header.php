<?php
require __DIR__ . "/config.php";
require __DIR__ . "/functions.global.php";

require __DIR__ . "/autoload.php";
require __DIR__ . "/vendor/autoload.php";

$db = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db) or die("Ooops?");
$db->set_charset('utf8');

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/containerConfig.php');
$container = $builder->build();

require __DIR__ . "/auto_login.php";

$thumbnailManager = new App\ThumbnailManager(PUBLIC_ROOT, $thumbnail_folder);