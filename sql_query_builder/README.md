
# SQL Simple Fluent Intereface Query Builder - README

This project provides a lightweight, object-oriented SQL database query builder in PHP. It helps implement dynamic sql queriesg and execution through a Fluent Interface, enabling method chaining for cleaner and more intuitive syntax. The codebase is modular, easily extendable, and promotes clean and maintainable development practices.

## Features

-   **Dynamic Query Builder**: Create and execute SQL queries using method chaining.
-   **Modular Design**: Separate classes for query logic (`Db_Query`), method registration (`Db_Method_Register`), and database handling (`Db_Class`).
-   **Test Utilities**: Generate random data for testing purposes (`Test` class).

## Installation

Clone this repository and include the relevant files in your PHP project.

`git clone https://github.com/your-repo/database-management-utility.git`

Ensure the necessary files are included in your script:

```php
/**
 * This file contains a simplified class for handling SQL
 * queries in PHP. It serves as the foundation for a lightweight,
 * object-oriented query builder utility, enabling quick and
 * dynamic creation and execution of database queries.
 */

include ('class.db.simple.php');

/**
 * This file is a test file which provides helper methods for
 * generating random data commonly used in testing scenarios.
 */
include ('class.test.php');
```

## Getting Started

### Prerequisites

-   **PHP 7.4+**: Ensure your environment supports modern PHP features.
-   **Database Access**: Provide valid database credentials for successful connection.

## Usage

---

### 1. Initialize the Database Class

Create an instance of the `Db_Class` by providing your database connection settings.

```php
use \My_Class\Db\Db_Class as Db;

$Db = new Db([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'test'
]);
```

---

### 2. Generate Test Data

Use the `Test` utility to generate random test data.

```php
use \My_Class\Db\Test as Test;

$data = [
    'user_name'         => Test::generateRandomName(5),
    'user_password'     => md5(Test::generateRandomWord(12)),
    'status'            => rand(0, 1),
    'birthday'          => Test::generateRandomDate('1990-01-01', '2005-01-01')
];
```

---

### 3. Insert Data into a Table

```php
$Db->insert_to('users_test')
   ->values($data)
   ->exec();
```

Output:

```
INSERT INTO users_test (user_name, user_password, status, birthday) VALUES ('Thlmp', '05d2c3dd0e312e2dafb8d17bed4841f6', '0', '1997-08-29')
```

---

### 4. Select Data from a Table

Perform a `SELECT` query with filtering, sorting, and pagination.

```php
$Db->select()
   ->from('users_test')
   ->where(['status' => 1])
   ->order_by(['user_name' => 'ASC'])
   ->limit(10)
   ->exec();
```

Output:

```
SELECT * FROM users_test ORDER BY user_name ASC LIMIT 10
```

---

### 5. Update Data in a Table

Update a specific record in the database.

```php
$Db->update('users_test')
   ->set($data)
   ->where(['user_id' => 2])
   ->exec();
```

Output:

```
UPDATE users_test SET user_name = 'Iehje', user_password = '8004d2a2a009a42119d3b7c4d7f20e1c', status = '1', birthday = '1997-04-28'
```

---

### 6. Fetch Specific Items

Retrieve the first or last item in the dataset.

```php
$Db->get()->from('users_test')->firstItem()->exec();
$Db->get()->from('users_test')->lastItem()->exec();
```

Output:
```
SELECT * FROM users_test ORDER BY ASC LIMT 1
SELECT * FROM users_test ORDER BY DESC LIMIT 1
```

---

### 7. Debugging and Execution

All queries can be logged and inspected using the `print()` method before execution.

`$Db->exec(true);`


## Example Script

Below is a full example combining all operations:

```php
include ('class.db.simple.php');
include ('class.test.php');

use \My_Class\Db\Db_Class as Db;
use \My_Class\Db\Test as Test;

$Db = new Db([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'test'
]);

$data = [
    'user_name' => Test::generateRandomName(5),
    'user_password' => md5(Test::generateRandomWord(12)),
    'status' => rand(0, 1),
    'birthday' => Test::generateRandomDate('1990-01-01', '2005-01-01')
];

/* Insert Data */
$Db->insert_to('users_test')->values($data)->exec();

/* Select Data */
$Db->select()
    ->from('users_test')
    ->where(['status' => 1])
    ->order_by(['user_name' => 'ASC'])
    ->limit(10)
    ->exec();

/* Update Data */
$Db->update('users_test')
    ->set($data)
    ->where(['user_id' => 2])
    ->exec();

/* Fetch First and Last Items */
$Db->get()->from('users_test')->firstItem()->exec();
$Db->get()->from('users_test')->lastItem()->exec();
?>
```

Output:

```php
INSERT INTO users_test (user_name, user_password, status, birthday) VALUES ('Cttxl', 'a28e5ab9b7792501646e49a7b5130ab2', '1', '2003-05-06')
SELECT * FROM users_test ORDER BY user_name ASC LIMIT 10
UPDATE users_test SET user_name = 'Kbkfs', user_password = '623bd6d17c7353c9ea0125e497a5edec', status = '1', birthday = '1991-09-11'
SELECT * FROM users_test ORDER BY ASC LIMIT 1
SELECT * FROM users_test ORDER BY DESC LIMIT 1
```

## Next Step

The next step is to implement database support, enabling the execution of the constructed queries on the database.
This will involve retrieving data from the database, processing it, and ensuring that the query builder works seamlessly with
the underlying database structure. Proper integration with the database will allow the builder to efficiently
manage data, execute queries, and provide dynamic results.

## Contribution

Please fork the repository, create a feature branch, and submit a pull request.

## License

This project is licensed under the MIT License.

## Author

This project was created by **Michał Igliński**.

You can connect with me on [LinkedIn](https://www.linkedin.com/in/miglinski) or
check out more of my work on [GitHub](https://github.com/michaliglinski).
