<?php

namespace Tandava\Sql;

use mysqli;

class SqlWriter extends SqlConnection
{
    /**
     * @var string
     */
    protected static $type;

    /**
     * @var string
     */
    protected static $table = "";

    /**
     * @var array
     */
    protected static $data = [
        'rows' => [],
        'where' => [],
        'whereOperator' => 'AND',
        'limit' => '',
        'order' => [],
        'join' => [],
        'joinTable' => '',
        'joinType' => 'INNER'
    ];

    /**
     * @param string|array $rows
     * @return Sql
     */
    public static function Select($rows = '*')
    {
        $args = func_get_args();
        if (isset($args[1])) {
            $rows = $args;
        }

        $rows = self::Escape($rows);

        if (is_string($rows)) {
            self::$data['rows'] = [$rows];
        } else {
            self::$data['rows'] = $rows;
        }
        self::$type = 'select';
        return new Sql();
    }

    /**
     * @param array $rows
     * @return Sql
     */
    public static function Insert($rows)
    {
        $rows = self::Escape($rows);

        self::$data['rows'] = $rows;
        self::$type = 'insert';
        return new Sql();
    }

    /**
     * @param array $rows
     * @param bool $quote
     * @return Sql
     */
    public static function Update($rows, $quote = true)
    {
        $rows = self::Escape($rows);

        self::$data['quote_update'] = $quote;
        self::$data['rows'] = $rows;
        self::$type = 'update';
        return new Sql();
    }

    /**
     * @param string $from
     * @return Sql
     */
    public static function From($from)
    {
        $from = self::Escape($from);

        self::$table = $from;
        return new Sql();
    }

    /**
     * @return Sql
     */
    public static function Delete()
    {
        self::$type = 'delete';
        return new Sql();
    }

    /**
     * @param string|array $row
     * @param string $operator
     * @param bool $quote
     * @param $val
     * @return Sql
     */
    public static function Where($row, $operator = "", $val = "", $quote = true)
    {
        if (!is_array($row)) {

            if (mb_strlen($val) == 0) {
                $val = $operator;
                $operator = '=';
            }

            $row = self::Escape($row);
            $val = self::Escape($val);

            self::$data['where'][] = [$row, $operator, $val, $quote];

        } else {
            foreach ($row as $value) {
                if (!isset($value[2])) {
                    $value[2] = "";
                }
                self::Where($value[0], $value[1], $value[2], $quote);
            }

        }
        return new Sql();
    }

    /**
     * @return Sql
     */
    public static function _And()
    {
        self::$data['whereOperator'] = 'AND';
        return new Sql();
    }

    /**
     * @return Sql
     */
    public static function _Or()
    {
        self::$data['whereOperator'] = 'OR';
        return new Sql();
    }

    /**
     * @param int $number
     * @return Sql
     */
    public static function Limit($number)
    {
        self::$data['limit'] = self::Escape($number);
        return new Sql();
    }

    /**
     * @param string $row
     * @param string $type
     * @return Sql
     */
    public static function Order($row, $type = "asc")
    {
        self::$data['order'] = [self::Escape($row), self::Escape(mb_strtoupper($type))];
        return new Sql();
    }

    /**
     * @param $tableName
     * @param $where
     * @param string $type
     * @return Sql
     */
    public static function Join($tableName, $where, $type = 'inner')
    {
        $type = mb_strtoupper($type);
        self::$data['joinType'] = $type;
        self::$data['joinTable'] = $tableName;

        foreach ($where as $value) {
            if (mb_strlen($value[2]) == 0) {
                $value[2] = $value[1];
                $value[1] = '=';
            }
            self::$data['join'][$value[0]] = [$value[2], $value[1]];
        }

        return new Sql();
    }

    /**
     * @param array|string $array
     * @return array|string
     */
    protected static function Escape($array)
    {
        $array_result = [];
        if (is_array($array)) {
            foreach ($array as $key => $val) {
                $key = addslashes($key);
                if (is_array($val)) {
                    $val = self::Escape($val);
                } else {
                    $val = addslashes($val);
                }
                $array_result[$key] = $val;
            }
        } else {
            $array_result = addslashes($array);
        }

        return $array_result;
    }
}