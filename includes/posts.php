<?php
if (!empty($_GET['s']) && $_GET['s'] == "add") {
    require __DIR__ . "/../includes/post_add.php";
} else if ($_GET['s'] == "view") {
    require __DIR__ . "/../includes/post_view.php";
} else if ($_GET['s'] == "list") {
    require __DIR__ . "/../includes/post_list.php";
} else if ($_GET['s'] == "vote") {
    require __DIR__ . "/../includes/post_vote.php";
} else if ($_GET['s'] == "random") {
    require __DIR__ . "/post_random.php";
}
