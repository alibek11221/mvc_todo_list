<?php

namespace App\Core;

use PDO;

class Db extends PDO
{

    /**
     * Db constructor.
     */
    public function __construct()
    {
        $dsn = $_ENV["DATABASE_URL"];
        $user = $_ENV["DB_USER"];
        $password = $_ENV["DB_PASSWORD"];
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        parent::__construct($dsn, $user, $password, $options);
    }

}
