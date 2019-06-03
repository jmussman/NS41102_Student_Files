<?php
// employee-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Declare the datasource (define is used here to get the server root into the const).

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
const DSN = 'sqlite:' . __ROOT__ . '/_data/hr.sqlite';

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

    $db = $db = new PDO(DSN);

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

    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statement->execute(array(':employee_id' => $id));
    $employee = $statement->fetch(PDO::FETCH_ASSOC);

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
                <a href="/employees-view.php">Employees</a>
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
                        <td class="form-label"><label for="hire_date">Hire Date:</label></td>
                        <td class="form-field"><input id="hire_date" name="hire_date" type="text" value="<?= $hire_date ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="employee_no">Employee Number:</label></td>
                        <td class="form-field"><input id="employee_no" name="employee_no" type="text" value="<?= $employee_no ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="government_no">Government Number:</label></td>
                        <td class="form-field"><input id="government_no" name="government_no" type="text" value="<?= $government_no ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="first_name">First Name:</label></td>
                        <td class="form-field"><input id="first_name" name="first_name" type="text" value="<?= $first_name ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="last_name">Last Name:</label></td>
                        <td class="form-field"><input id="last_name" name="last_name" type="text" value="<?= $last_name ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="email">Email:</label></td>
                        <td class="form-field"><input id="email" name="email" type="text" value="<?= $email ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="street">Street:</label></td>
                        <td class="form-field"><input id="street" name="street" type="text" value="<?= $street ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="city">City:</label></td>
                        <td class="form-field"><input id="city" name="city" type="text" value="<?= $city ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="state_province">State or Province:</label></td>
                        <td class="form-field"><input id="state_province" name="state_province" type="text" value="<?= $state_province ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="postal_code">Postal Code:</label></td>
                        <td class="form-field"><input id="postal_code" name="postal_code" type="text" value="<?= $postal_code ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="country_code">Country Code:</label></td>
                        <td class="form-field"><input id="country_code" name="country_code" type="text" value="<?= $country_code ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="salary_employee">Salary Employee:</label></td>
                        <td class="form-field"><input id="salary_employee" name="salary_employee" type="checkbox"<?= $salary_employee ? ' checked="checked"' : '' ?> /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="rate">Pay Rate:</label></td>
                        <td class="form-field"><input id="rate" name="rate" type="text" value="<?= $rate ?>" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"></td>
                        <td class="form-field">
                            <a href="/employees-view.php"><input type="button" value="Cancel" /></a> <?php // The cancel button is wrapped in a link that redirects back to the employees-view.php page ?>
                        </td>
                        <td class="form-error"></td>
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
