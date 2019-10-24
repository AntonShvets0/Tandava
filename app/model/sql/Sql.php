<?php

namespace Tandava\Sql;

use Error;
use mysqli_result;

class Sql extends SqlWriter
{
    /**
     * @var string
     * Запрос
     */
    public static $query;

    /**
     * @param int $result_type
     * @return array|bool|mysqli_result|int
     */
    public static function Exec($result_type = MYSQLI_ASSOC)
    {
        $connection = self::Connection();
        $query = "";
        $type = self::$type;

        switch (self::$type) {
            case 'insert':
                $query = self::ExecInsert();
                break;
            case 'update':
                $query = self::ExecUpdate() . ' ' . self::ExecWhere();
                break;
            case 'select':
                $query = self::ExecSelect() . ' ' . self::ExecFrom() . ' ' . self::ExecJoin() . ' ' . self::ExecWhere();
                break;
            case 'delete':
                $query = self::ExecDelete() . ' ' . self::ExecFrom() . ' ' . self::ExecWhere();
                break;
        }

        if (!empty(self::$data['order'][0])) {
            $query .= " ORDER BY " . self::$data['order'][0] . " " . self::$data['order'][1];
        }

        if (!empty(self::$data['limit'])) {
            $query .= " LIMIT " . self::$data['limit'];
        }

        self::$query = $query;

        $oldQuery = $query;
        $query = $connection->query($query);

        if (!$query) {
            throw new Error($connection->error . ": " . $oldQuery);
        }

        self::ClearVar();

        if ($type == 'select') {
            $result = [];
            while ($row = $query->fetch_array($result_type)) {
                $result[] = $row;
            }
        } elseif ($type == 'insert' && $query) {
            $result = $connection->insert_id;
        } else {
            $result = $query;
        }

        $connection->close();

        return $result;
    }

    /**
     * @return string
     */
    private static function ExecInsert()
    {
        $sql = "INSERT INTO `" . self::$table . "` (";

        $values = "";
        foreach (self::$data['rows'] as $key => $val) {
            $sql .= "{$key},";
            $values .= "'{$val}',";
        }

        $sql = mb_substr($sql, 0, -1) . ") VALUES (";
        $values = mb_substr($values, 0, -1);
        return $sql . $values . ')';
    }

    /**
     * @return string
     */
    private static function ExecUpdate()
    {
        $sql = "UPDATE `" . self::$table . "` SET ";
        foreach (self::$data['rows'] as $key => $val) {
            $val = self::$data['quote_update'] ? "'{$val}'" : $val;
            $sql .= "{$key}={$val},";
        }
        $sql = mb_substr($sql, 0, -1);
        return $sql;
    }

    /**
     * @return string
     */
    private static function ExecDelete()
    {
        return "DELETE";
    }

    /**
     * @return string
     */
    private static function ExecSelect()
    {
        $sql = "SELECT ";

        foreach (self::$data['rows'] as $val) {
            $sql .= "{$val},";
        }
        $sql = mb_substr($sql, 0, -1);
        return $sql;
    }

    /**
     * @return string
     */
    private static function ExecWhere()
    {
        $sql = "WHERE ";

        if (count(self::$data['where']) > 0) {
            foreach (self::$data['where'] as $val) {
                list($row, $operator, $val, $quote) = $val;
                $val = $quote ? "'{$val}'" : $val;
                $sql .= "{$row}{$operator}{$val} " . self::$data['whereOperator'] . ' ';
            }
            $sql = mb_substr($sql, 0, -(mb_strlen(self::$data['whereOperator']) + 1));
        } else {
            $sql .= "1";
        }

        return $sql;
    }

    /**
     * @return string
     */
    private static function ExecFrom()
    {
        return "FROM `" . self::$table . "` ";
    }

    private static function ExecJoin()
    {

        if (empty(self::$data['joinTable'])) {
            return '';
        }

        $result = self::$data['joinType'] . ' JOIN ' . self::$data['joinTable'] . ' ON ';

        foreach (self::$data['join'] as $key => $value) {
            $result .= "{$key} {$value[1]} {$value[0]} AND ";
        }
        $result = mb_substr($result, 0, -4);

        return $result;
    }

    /**
     * @return void
     */
    public static function ClearVar()
    {
        self::$type = "";
        self::$table = "";
        self::$data = [
            'rows' => [],
            'where' => [],
            'whereOperator' => 'AND',
            'limit' => '',
            'order' => [],
            'join' => [],
            'joinTable' => '',
            'joinType' => 'INNER'
        ];
    }
}