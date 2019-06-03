<?php
// employee-view.sqlite3.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Declare the datasource (define is used here to get the server root into the const).

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
const DSN = __ROOT__ . '/_data/hr.sqlite';

// Global column values from the database row with default values.

$employee_id = 0;
$version = 0;
$hire_date = date('Y-m-d');
$employee_no = '';
$government_no = '';
$hire_date = '';
$first_name = '';
$last_name = '';
$email = '';
$street = '';
$city = '';
$state_province = '';
$postal_code = '';
$country_code = '';
$salary_employee = false;
$rate = 0.0;

// Launch the main function to get things started.

main();

function main() {

    global $employee_id;

    if ($_SERVER['REQUEST_METHOD'] ==='GET') {

        global $employee_id;

        // GET request, load and display the employee.

        $employee_id = intval($_GET['employee_id'] ?? null);

        if ($employee_id) {

            // Retrieve an existing record.

            getEmployee($employee_id);

        } else {

            // Return to the list.

            header('Location: /employees-view.php');
        }
    }
}

function getEmployee($id) {

    global $generalErrors;
    global $version, $hire_date, $employee_no, $government_no, $first_name, $last_name, $email, $street, $city, $state_province, $postal_code, $country, $salary_employee, $rate;

    $db = new SQLite3(DSN);

    $sql = <<<'EOT'
SELECT  version,
        hire_date,
        employee_no,
        government_no,
        first_name,
        last_name,
        email,
        street,
        city,
        state_province,
        postal_code,
        country_code,
        salary_employee,
        rate FROM employees WHERE employee_id = :employee_id
EOT;

    $statement = $db->prepare($sql);
    $statement->bindValue(':employee_id', $id);
    $results = $statement->execute();
    $employee = $results->fetchArray(SQLITE3_ASSOC);

    if ($employee) {

        $version = $employee['version'];
        $hire_date = $employee['hire_date'];
        $employee_no = $employee['employee_no'];
        $government_no = $employee['government_no'];
        $first_name = $employee['first_name'];
        $last_name = $employee['last_name'];
        $email = $employee['email'];
        $street = $employee['street'];
        $city = $employee['city'];
        $state_province = $employee['state_province'];
        $country_code = $employee['country_code'];
        $salary_employee = $employee['salary_employee'];
        $rate = $employee['rate'];
    }

    // Close the database connection.

    $statement = null;
    $db = null;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Employee</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>

<body>
    <header>
        <div id="header">
            <img src="/assets/images/header-logo.png" alt="Human Resources Logo" />
            <div id="menu">
                <a href="/index.php">Home</a>
                <a href="/employees-view.sqlite3.php">Employees</a>
            </div>
        </div>
    </header>

    <div id="main" class="y-scroll">

        <div id="content">

            <form method="POST">

                <table class="form">

                    <thead>
                    </thead>

                    <tbody>

                        <tr>
                            <td class="form-label">Hire Date:</td>
                            <td class="form-field"><input name="hire_date" type="text" value="<?= $hire_date ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">Employee Number:</td>
                            <td class="form-field"><input name="employee_no" type="text" value="<?= $employee_no ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">Government Number:</td>
                            <td class="form-field"><input name="government_no" type="text" value="<?= $government_no ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">First Name:</td>
                            <td class="form-field"><input name="first_name" type="text" value="<?= $first_name ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">Last Name:</td>
                            <td class="form-field"><input name="last_name" type="text" value="<?= $last_name ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">Email:</td>
                            <td class="form-field"><input name="email" type="text" value="<?= $email ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">Street:</td>
                            <td class="form-field"><input name="street" type="text" value="<?= $street ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">City:</td>
                            <td class="form-field"><input name="city" type="text" value="<?= $city ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">State or Province:</td>
                            <td class="form-field"><input name="state_province" type="text" value="<?= $state_province ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">Postal Code:</td>
                            <td class="form-field"><input name="postal_code" type="text" value="<?= $postal_code ?>" /></td>
                            <td class="form-error"></td>
                        </tr>

                        <tr>
                            <td class="form-label">Country:</td>
                            <td class="form-field"><input name="country" type="text" value="<?= $country ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label">Salary Employee:</td>
                            <td class="form-field"><input name="salary_employee" type="checkbox"<?= $salary_employee ? ' checked="checked"' : '' ?>/></td>
                        </tr>

                        <tr>
                            <td class="form-label">Pay Rate:</td>
                            <td class="form-field"><input name="rate" type="text" value="<?= $rate ?>" /></td>
                        </tr>

                        <tr>
                            <td class="form-label"></td>
                            <td class="form-field">
                                <a href="/employees-view.sqlite3.php"><input type="button" value="Cancel" /></a>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </form>
        
        </div>
    </div>

    <footer>
        Copyright &copy; 2019 NextStep IT Training. All rights reserved.
    </footer>
</body>
</html>
