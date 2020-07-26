<?php

namespace App\Models;

use App\Core\Db;

abstract class AbstractModel
{
    /**
     * @var Db
     */
    protected $db;

    /**
     * AbstractModel constructor.
     *
     * @param Db $db
     */
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

}
