<?php namespace My_Class\Db;

/**
 * Project Name: [SFI/QB] SQL Simple Fluent Intereface Query Builder
 * File: class.db.simple.php
 * Description: Core class for building and executing SQL queries dynamically.
 * Author: Michał Igliński (mtur.reklama@gmail.com)
 * Created: 2025-01-07
 * Updated: 2025-01-07
 * License: MIT
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2025 Michał Igliński (mtur.reklama@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


/**
 * Class Db_Method_Register
 * Responsible for registering and tracking method calls for debugging and logging purposes.
 */
class Db_Method_Register {
    private static $lastMethod = null; // Stores the name of the last called method.
    private static $methodHistory = []; // Stores a history of called methods.

    /**
     * Registers a method call by updating the last method and method history.
     *
     * @param string $methodName Name of the method being called.
     * @param object $queryInstance Instance of the class calling the method.
     */
    public static function registerMethod($methodName, $queryInstance) {
        $queryInstance->lastMethod = $methodName;
        $queryInstance->methodHistory[] = $methodName;

        self::$lastMethod = $methodName;
        self::$methodHistory[] = $methodName;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Gets the name of the last registered method.
     *
     * @return string|null
     */
    public static function getLastMethod() {
        return self::$lastMethod;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Retrieves the history of all registered methods.
     *
     * @return array
     */
    public static function getMethodHistory() {
        return self::$methodHistory;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Retrieves and clears the method history.
     *
     * @return array
     */
    public static function getMethodHistoryAndClear() {
        $history = self::getMethodHistory();
        self::$lastMethod = null;
        self::$methodHistory = [];
        return $history;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Clears the stored method history and last method.
     */
    public static function clearMethodHistory() {
        self::$lastMethod = null;
        self::$methodHistory = [];
    }
}

////////////////////////////////////////////
////////////////////////////////////////////

/**
 * Class Db_Class
 * Manages database interactions and delegates method calls to a query instance.
 */
class Db_Class {

    public $methodName = null; // Stores the name of the current method.
    public $methodHistory = []; // Stores the history of methods called on this instance.

    public $queryInstance = null; // Instance of Db_Query for executing queries.
    private $settings = null; // Configuration settings for the database connection.

    /**
     * Constructor for Db_Class.
     *
     * @param mixed $settings Optional settings for the database connection.
     */
    public function __construct($settings = null) {
        $this->settings = $settings;
        $this->queryInstance = new Db_Query();
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Magic method to handle dynamic method calls.
     *
     * @param string $name Method name being called.
     * @param array $arguments Arguments passed to the method.
     *
     * @return $this
     */
    public function __call($name, $arguments) {
        Db_Method_Register::registerMethod($name, $this);

        if (sizeof($arguments) == 1) {
            $arguments = $arguments[0];
        }

        $this->queryInstance->$name($arguments);
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Placeholder for establishing a database connection.
     */
    public function connect() {}
}

////////////////////////////////////////////
////////////////////////////////////////////

/**
 * Class Db_Query
 * Responsible for building and executing SQL queries.
 */
class Db_Query {

    public $query = ''; // Stores the constructed SQL query.
    public $data = []; // Stores the data used in the query.

    /**
     * Magic method to handle dynamic method calls.
     *
     * @param string $name Method name being called.
     * @param array $arguments Arguments passed to the method.
     *
     * @return $this
     * @throws \BadMethodCallException If the method does not exist.
     */
    public function __call($name, $arguments) {
        if (!method_exists($this, $name)) {
            throw new \BadMethodCallException("Method $name does not exist in Db_Query.");
        }

        call_user_func_array([$this, $name], $arguments);
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Constructs a SELECT query.
     *
     * @param string|array $columns Columns to select.
     * @return $this
     */
    public function select($columns) {
        isset($columns) && empty($columns) ? $columns = '*' : null;

        if (!is_array($columns)) {
            $this->query .= 'SELECT ' . $columns . ' ';
        } else {
            $this->query .= 'SELECT ' . implode(', ', $columns) . ' ';
        }

        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds a FROM clause to the query.
     *
     * @param string $table Table name.
     * @return $this
     */
    public function from($table) {
        $this->query .= 'FROM ' . $table . ' ';
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds a WHERE clause to the query based on conditions.
     *
     * @param string|array $conditions Conditions for the WHERE clause.
     * @return $this
     */
    public function where($conditions) {
        if (Db_Method_Register::getLastMethod() == 'from') {
            if (!is_array($conditions) && str_contains($conditions, '=')) {
                $this->query .= 'WHERE ' . $conditions . ' ';
            } else {
                $where = [];
                foreach ($conditions as $column => $value) {
                    $where[] = "$column = '$value'";
                }
                $this->query .= 'WHERE ' . implode(' AND ', $where) . ' ';
            }
        } else if (Db_Method_Register::getLastMethod() == 'set') {
            $whereData = [];
            foreach ($conditions as $column => $value) {
                $whereData[] = "$column = '" . addslashes($value) . "'";
            }
            $this->query .= ' WHERE ' . implode(' AND ', $whereData);
        }

        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds an ORDER BY clause to the query.
     *
     * @param array $order Columns and their sort directions.
     * @return $this
     */
    public function order_by($order = []) {
        $orderBy = [];
        foreach ($order as $column => $direction) {
            $orderBy[] = "$column $direction";
        }
        $this->query .= 'ORDER BY ' . implode(', ', $orderBy) . ' ';
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds a LIMIT clause to the query.
     *
     * @param int $limit Number of rows to limit.
     * @return $this
     */
    public function limit($limit) {
        $this->query .= 'LIMIT ' . (int)$limit . ' ';
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds an INSERT INTO clause to the query.
     *
     * @param string $table Table name.
     * @return $this
     */
    public function insert_to($table) {
        $this->query .= 'INSERT INTO ' . $table . ' ';
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds column values for an INSERT query.
     *
     * @param array $data Data to insert.
     * @return $this
     */
    public function values($data) {
        $this->data = $data;
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(
            function ($value) {
                return "'" . addslashes($value) . "'";
            },
            array_values($this->data)
        ));

        $this->query .= "($columns) VALUES ($values)";
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds an UPDATE clause to the query.
     *
     * @param string $table Table name.
     * @return $this
     */
    public function update($table) {
        $this->query = 'UPDATE ' . $table;
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Adds a SET clause for an UPDATE query.
     *
     * @param array $data Data to update.
     * @return $this
     */
    public function set($data) {
        $this->data = $data;
        $setData = [];
        foreach ($data as $column => $value) {
            $setData[] = "$column = '" . addslashes($value) . "'";
        }
        $this->query .= ' SET ' . implode(', ', $setData);
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Appends a basic "SELECT *" SQL statement to the query.
     *
     * @return $this Returns the current instance for method chaining.
     */
    public function get() {
        $this->query .= "SELECT * ";
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Appends an SQL clause to retrieve the first item from the results.
     * The results are ordered in ascending order.
     *
     * @return $this Returns the current instance for method chaining.
     */
    public function firstItem() {
        $this->query .= "ORDER BY ASC LIMIT 1";
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Appends an SQL clause to retrieve the last item from the results.
     * The results are ordered in descending order.
     *
     * @return $this Returns the current instance for method chaining.
     */
    public function lastItem() {
        $this->query .= "ORDER BY DESC LIMIT 1";
        return $this;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Executes the built query and resets the query properties.
     *
     * @param bool $verbose Whether to display verbose output.
     */
    public function exec($verbose = false) {
        $this->print();

        $verbose ?
            var_dump(Db_Method_Register::getMethodHistoryAndClear()) :
            Db_Method_Register::clearMethodHistory();

        foreach (get_object_vars($this) as $property => $value) {
            $this->$property = null;
        }
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Prints the constructed SQL query.
     */
    public function print() {
        echo $this->query . "\n";
    }
}
