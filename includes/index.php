<?php
/** @var \App\HitCounter $hitCounter */
$hitCounter = $container->get('App\\HitCounter');
$hitCounter->up();

$query = "SELECT t1.pcount, t2.count FROM $post_count_table AS t1 JOIN $hit_counter_table as t2 WHERE t1.access_key='posts'";
$result = $db->query($query);
$row = $result->fetch_assoc();

$templateParams = [
    'site_url' => $site_url,
    'site_name' => $site_url3,
    'post_count' => $row['pcount'],
    'post_count_digits' => str_split($row['pcount']),
    'visitor_count' => $row['count'],
];

/** @var \Twig\Environment $twig */
$twig = $container->get('\\Twig\\Environment');

echo $twig->render('index.html.twig', $templateParams);