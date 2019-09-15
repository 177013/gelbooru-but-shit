<?php

namespace App;

use DI\Annotation\Inject;

final class HitCounter {
    /**
     * @Inject()
     * @var DatabaseInterface $db
     */
    private $db;

    public function __construct(\mysqli $db) {
        $this->db = $db;
    }

    public function up(): void {
        $this->db->query("UPDATE hit_counter SET count=count+1");
    }
}
