<?php

namespace App;

interface DatabaseInterface {
    public function query(string $query);
}
