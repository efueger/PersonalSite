<?php

namespace App\Models;

abstract class BaseMapper
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
