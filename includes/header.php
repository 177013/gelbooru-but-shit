<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?= $site_url3 ?><?= isset($lozerisdumb) ? ' ' . $lozerisdumb : '' ?>
    </title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= $site_url ?>default.css?2" title="default"/>
    <link rel="search" type="application/opensearchdescription+xml" title="' . $site_url3 . '"
          href="<?= $site_url ?>/default.xml"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="description" content="A very large imageboard for Japanese related content that is anonymous."/>
    <script src="<?= $site_url ?>script/prototype.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/global.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/scriptaculous.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/builder.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/effects.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/dragdrop.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/controls.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/slider.js?2" type="text/javascript"></script>
    <script src="<?= $site_url ?>script/notes.js?2" type="text/javascript"></script>
</head>
<body>
<div id="header">
    <div class="header-title-container">
        <h2><a href="<?= $site_url ?>index.php" class="site-name"><?= $site_url3 ?></a></h2>
    </div>
    <ul class="flat-list" id="navbar">
        <?php
        $page = $_GET['page'] ?? '';
        $links = [
            [
                'url' => 'index.php?page=account',
                'text' => 'My Account',
                'active' => $page === 'account',
            ],[
                'url' => 'index.php?page=post&amp;s=list&amp;tags=all',
                'text' => 'Posts',
                'active' => $page === 'post',
            ],[
                'url' => 'index.php?page=comment&amp;s=list',
                'text' => 'Comments',
                'active' => $page === 'comment',
            ],[
                'url' => 'index.php?page=alias&amp;s=list',
                'text' => 'Alias',
                'active' => $page === 'alias',
            ],[
                'url' => 'index.php?page=forum&amp;s=list',
                'text' => 'Forum',
                'active' => $page === 'forum',
            ],[
                'url' => 'index.php?page=post&amp;s=random',
                'text' => 'Random',
            ],[
                'url' => 'help/index.php',
                'text' => 'Help'
            ],
        ];
        ?>

        <?php foreach ($links as $link) { ?>
        <li>
            <a class="navbar-link <?= ($link['active'] ?? false) ? 'is-active' : '' ?>"
               href="<?= $site_url . $link['url'] ?>">
                <?= $link['text'] ?>
            </a>
        </li>
        <?php } ?>

        <li id="notice"></li>
    </ul>
</div>
<div id="long-notice"></div>