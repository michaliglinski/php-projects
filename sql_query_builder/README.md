
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

```
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

### 1. Initialize the Database Class

Create an instance of the `Db_Class` by providing your database connection settings.

```
use \My_Class\Db\Db_Class as Db;

$Db = new Db([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'test'
]);
```

### 2. Generate Test Data

Use the `Test` utility to generate random test data.

```
use \My_Class\Db\Test as Test;

$data = [
    'user_name'         => Test::generateRandomName(5),
    'user_password'     => md5(Test::generateRandomWord(12)),
    'status'            => rand(0, 1),
    'birthday'          => Test::generateRandomDate('1990-01-01', '2005-01-01')
];
```

### 3. Insert Data into a Table

```
$Db->insert_to('users_test')
   ->values($data)
   ->exec();
```

### 4. Select Data from a Table

Perform a `SELECT` query with filtering, sorting, and pagination.

```
$Db->select()
   ->from('users_test')
   ->where(['status' => 1])
   ->order_by(['user_name' => 'ASC'])
   ->limit(10)
   ->exec();
```


### 5. Update Data in a Table

Update a specific record in the database.

```
$Db->update('users_test')
   ->set($data)
   ->where(['user_id' => 2])
   ->exec();
```

### 6. Fetch Specific Items

Retrieve the first or last item in the dataset.

```
$Db->get()->from('users_test')->firstItem()->exec();
$Db->get()->from('users_test')->lastItem()->exec();
```

### 7. Debugging and Execution

All queries can be logged and inspected using the `print()` method before execution.

`$Db->exec(true);`


## Example Script

Below is a full example combining all operations:

```
<?php
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

## Contribution

Please fork the repository, create a feature branch, and submit a pull request.

## License

This project is licensed under the MIT License.