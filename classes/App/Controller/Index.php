<?php

namespace App\Controller;

use App\HitCounter;
use DI\Annotation\Inject;
use Twig\Environment;

final class Index {
    /**
     * @Inject
     * @var HitCounter
     */
    private $hitCounter;
    /**
     * @Inject
     * @var \mysqli
     */
    private $db;
    /**
     * @Inject
     * @var Environment
     */
    private $twig;

    public function render(): string {
        global $site_url;
        global $site_url3;

        $this->hitCounter->up();

        $query = "SELECT t1.pcount, t2.count FROM post_count AS t1 JOIN hit_counter as t2 WHERE t1.access_key='posts'";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();

        $templateParams = [
            'site_url' => $site_url,
            'site_name' => $site_url3,
            'post_count' => $row['pcount'],
            'post_count_digits' => str_split($row['pcount']),
            'visitor_count' => $row['count'],
        ];

        return $this->twig->render('index.html.twig', $templateParams);
    }
}
