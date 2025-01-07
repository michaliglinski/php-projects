<?php

/**
 * Example Script for Database Operations [SFI/QB]
 *
 * This script demonstrates the usage of the `Db_Class` and `Test` classes
 * to perform common database operations such as INSERT, SELECT, UPDATE,
 * and fetching specific records (first or last). It uses the MIT License.
 *
 * Dependencies:
 * - class.db.simple.php: Provides the database query builder functionality.
 * - class.test.php: Contains utility methods for generating test data.
 *
 * License: MIT
 */

include ('.\class.db.simple.php');
include ('.\class.test.php');

use \My_Class\Db\Db_Class as Db;
use \My_Class\Db\Test as Test;

$Db = new Db([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'test'
]);

$data = [
    'user_name'         => Test::generateRandomName(5),
    'user_password'     => md5(Test::generateRandomWord(12)),
    'status'            => rand(0, 1),
    'birthday'          => Test::generateRandomDate('1990-01-01', '2005-01-01')
];

////////////////////////////////////////////
////////////////////////////////////////////

$Db->insert_to('users_test')
->values($data)
->exec();

/**
 * Output:
 * SQL: INSERT INTO users_test (
 *  user_name, user_password, status, birthday
 * ) VALUES (
 *  'Thlmp', '05d2c3dd0e312e2dafb8d17bed4841f6', '0', '1997-08-29'
 * )
 */

////////////////////////////////////////////
////////////////////////////////////////////

$Db->select()
->from('users_test')
->where(['status' => 1])
->order_by(['user_name' => 'ASC'])
->limit(10)
->exec();

/**
 * Output:
 * SQL: SELECT * FROM users_test ORDER BY user_name ASC LIMIT 10
 */

////////////////////////////////////////////
////////////////////////////////////////////

 $data = [
    'user_name'         => Test::generateRandomName(5),
    'user_password'     => md5(Test::generateRandomWord(12)),
    'status'            => rand(0, 1),
    'birthday'          => Test::generateRandomDate('1990-01-01', '2005-01-01')
];

$Db->update('users_test')
->set($data)
->where(['user_id' => 2])
->exec();

/**
 * Output:
 * SQL: UPDATE users_test SET
 *  user_name = 'Iehje', user_password = '8004d2a2a009a42119d3b7c4d7f20e1c', status = '1', birthday = '1997-04-28'
 */

////////////////////////////////////////////
////////////////////////////////////////////

$Db->get()->from('users_test')->firstItem()->exec();

/**
 * Output:
 * SQL: SELECT * FROM users_test ORDER BY ASC LIMIT 1
 */

////////////////////////////////////////////
////////////////////////////////////////////

$Db->get()->from('users_test')->lastItem()->exec();

/**
 * Output:
 * SQL: SELECT * FROM users_test ORDER BY DESC LIMIT 1
 */


?>