<?php
// employee-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Declare the datasource (define is used here to get the server root into the const).

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
const DSN = 'sqlite:' . __ROOT__ . '/_data/hr.sqlite';

// TODO: add global variables to hold the data from the query, instead of using $row['name'].

// Launch the main function to get things started.

main();

function main()
{

    // TODO: make sure this is a GET request, then get the employee_id from the data. Use another function
    // to connect to the database, get the employee, and load the global variables. If the employee_id
    // is not set, redirect back to the employees-view.php page.
}

function getEmployee($id) {

    // TODO: query the database for the employee record and populate the globalvariables. Do not forget
    // to close the database connection. Then add the variables below to the HTML to present the data.
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
                        <td class="form-field"><input id="hire_date" name="hire_date" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="employee_no">Employee Number:</label></td>
                        <td class="form-field"><input id="employee_no" name="employee_no" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="government_no">Government Number:</label></td>
                        <td class="form-field"><input id="government_no" name="government_no" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="first_name">First Name:</label></td>
                        <td class="form-field"><input id="first_name" name="first_name" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="last_name">Last Name:</label></td>
                        <td class="form-field"><input id="last_name" name="last_name" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="email">Email:</label></td>
                        <td class="form-field"><input id="email" name="email" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="street">Street:</label></td>
                        <td class="form-field"><input id="street" name="street" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="city">City:</label></td>
                        <td class="form-field"><input id="city" name="city" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="state_province">State or Province:</label></td>
                        <td class="form-field"><input id="state_province" name="state_province" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="postal_code">Postal Code:</label></td>
                        <td class="form-field"><input id="postal_code" name="postal_code" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="country_code">Country Code:</label></td>
                        <td class="form-field"><input id="country_code" name="country_code" type="text" /></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="salary_employee">Salary Employee:</label></td>
                        <td class="form-field"><input id="salary_employee" name="salary_employee" type="checkbox" /></td> <?php // TODO: figure out how to check a checkbo! ?>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="rate">Pay Rate:</label></td>
                        <td class="form-field"><input id="rate" name="rate" type="text" /></td>
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
