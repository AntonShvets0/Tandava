<?php

namespace Tandava\Sql;

use mysqli;

/**
 * Class SqlConnection
 */
class SqlConnection
{
    /**
     * @return mysqli
     */
    public static function Connection()
    {
        return new mysqli(CONFIG['sql']['host'], CONFIG['sql']['user'], CONFIG['sql']['password'], CONFIG['sql']['db']);
    }
}