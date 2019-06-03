<?php
// employees-controller.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/authorization.php');

// Employee data.

$employee_id = null;
$employee_no = null;
$first_name = null;
$last_name = null;
$city = null;
$hire_date = null;
$salary_employee = null;
$statement = null;

// Call the main function.

main();

function main() {

    global $employee_id, $employee_no, $first_name, $last_name, $city, $hire_date, $salary_employee, $statement;

    // Look for instruction in the query string; save them in the session and fall back to the session if they are not there.

    if (empty($_SESSION['like'])) {

        $_SESSION['starts-with'] = 'A';     // Default search pattern.
    }

    if (!empty($_GET['starts-with']) and ctype_upper($_GET['starts-with'])) {

        $_SESSION['starts-with'] = $_GET['starts-with'];
        unset($_SESSION['like']);

    } elseif (!empty($_GET['like'])) {

        $_SESSION['like'] = $_GET['like'];
        unset($_SESSION['starts-with']);
    }

    !empty($_SESSION['starts-with']) and $like = "{$_SESSION['starts-with']}%";
    !empty($_SESSION['like']) and $like = "%{$_SESSION['like']}%";

    // Establish the database connection and query.

    $db = new PDO(DSN);

    $sql = <<<'EOT'
    SELECT  employee_id,
            employee_no,
            hire_date,
            last_name,
            first_name,
            salary_employee,
            city
            FROM employees WHERE last_name like :like ORDER BY last_name
    EOT;

    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->bindValue('like', $like);

    $statement->bindColumn('employee_id', $employee_id);
    $statement->bindColumn('hire_date', $hire_date);
    $statement->bindColumn('employee_no', $employee_no);
    $statement->bindColumn('first_name', $first_name);
    $statement->bindColumn('last_name', $last_name);
    $statement->bindColumn('city', $city);
    $statement->bindColumn('salary_employee', $salary_employee);

    $statement->execute();
}
