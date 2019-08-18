<?php
require __DIR__ . "/inv.header.php";

if (isset($_GET['page']) && $_GET['page'] != "") {
    if ($_GET['page'] == "account") {
        header("Cache-Control: store, cache");
        header("Pragma: cache");
        require __DIR__ . "/includes/header.php";
        require __DIR__ . "/includes/account.php";
    } else if ($_GET['page'] == "reg") {
        require __DIR__ . "/includes/signup.php";
    } else if ($_GET['page'] == "login") {
        require __DIR__ . "/includes/login.php";
    } else if ($_GET['page'] == "post") {
        require __DIR__ . "/includes/posts.php";
    } else if ($_GET['page'] == "history") {
        require __DIR__ . "/includes/history.php";
    } else if ($_GET['page'] == "account-options") {
        require __DIR__ . "/includes/account_options.php";
    } else if ($_GET['page'] == "account_profile") {
        require __DIR__ . "/includes/account_profile.php";
    } else if ($_GET['page'] == "comment") {
        require __DIR__ . "/includes/comment.php";
    } else if ($_GET['page'] == "search") {
        require __DIR__ . "/includes/search.php";
    } else if ($_GET['page'] == "favorites") {
        require __DIR__ . "/includes/favorites.php";
    } else if ($_GET['page'] == "alias") {
        require __DIR__ . "/includes/alias.php";
    } else if ($_GET['page'] == "reset_password") {
        require __DIR__ . "/includes/reset_password.php";
    } else if ($_GET['page'] == "forum") {
        require __DIR__ . "/includes/forum.php";
    } else {
        header("Location:" . $site_url . "/");
        exit;
    }
} else {
    header("Cache-Control: store, cache");
    header("Pragma: cache");
    require __DIR__ . "/includes/index.php";
}
