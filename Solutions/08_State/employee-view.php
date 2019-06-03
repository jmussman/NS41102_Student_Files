<?php
// employee-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Declare the datasource (define is used here only to show the contrast).

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
const DSN = 'sqlite:' . __ROOT__ . '/_data/hr.sqlite';

// Error message arrays.

$errors = [
    'general' => [],
    'hire_date' => [],
    'employee_no' => [],
    'government_no' => [],
    'first_name' => [],
    'last_name' => [],
    'email' => [],
    'street' => [],
    'city' => [],
    'state_province' => [],
    'postal_code' => [],
    'country_code' => [],
    'rate' => []
];

$isValid = true;

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

// Uploaded photo.

$photoStream = null;

// Launch the main function to start things.

main();

function main() {

    global $errors, $isValid;
    global $employee_id;

    // Check for logged-in status.

    session_start();

    if (empty($_SESSION['username'])) {

        header('Location: /login-view.php');
        die();
    }

    // Handle request.

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        // GET request, load and display the employee.

        $employee_id = intval($_GET['employee_id'] ?? null);

        if ($employee_id) {

            // Retrieve an existing record.

            getEmployee($employee_id);
        }

        // At this point the variables are either an existing record or initialized for a new record.

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Only administrators can update employees.

        if ($_SESSION['role'] !== 'administrators') {

            array_push($generalErrors, 'Permission denied');

        } else {

            validateEmployeeData();

            // If the data is valid, then try to update it with a optimistic concurrency

            if ($isValid)  {

                $result = $employee_id ? updateEmployee() : addEmployee();

                if ($result) {

                    // If everything succeeded, redirect back to the employees page before we go any further.

                    header("Location: /views/employees-view.php");
                }
            }

            // Fall through and show any error messages that were logged.
        }
    }
}

function addEmployee() {

    $sql = <<<'EOT'
INSERT into employees ( version,
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
                        rate )
VALUES (                :version,
                        :hire_date,
                        :employee_no,
                        :government_no,
                        :first_name,
                        :last_name,
                        :email,
                        :street,
                        :city,
                        :state_province,
                        :postal_code,
                        :country_code,
                        :salary_employee,
                        :rate)
EOT;

    return persistEmployee($sql);
}

function getEmployee($employee_id) {

    global $errors;
    global $version, $hire_date, $employee_no, $government_no, $first_name, $last_name, $email, $street, $city, $state_province, $postal_code, $country_code, $salary_employee, $rate;

    $result = false;        // Assume failure!

    try {

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

        $db = new PDO(DSN);
        $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $statement->bindParam(':employee_id', $employee_id);
        $statement->execute();

        $statement->bindColumn('version', $version);
        $statement->bindColumn('hire_date', $hire_date);
        $statement->bindColumn('employee_no', $employee_no);
        $statement->bindColumn('government_no', $government_no);
        $statement->bindColumn('first_name', $first_name);
        $statement->bindColumn('last_name', $last_name);
        $statement->bindColumn('email', $email);
        $statement->bindColumn('street', $street);
        $statement->bindColumn('city', $city);
        $statement->bindColumn('state_province', $state_province);
        $statement->bindColumn('postal_code', $postal_code);
        $statement->bindColumn('country_code', $country_code);
        $statement->bindColumn('salary_employee', $salary_employee);
        $statement->bindColumn('rate', $rate);

        $statement->fetch(PDO::FETCH_BOUND);

        $result = true;
    }

    catch (Exception $e) {

        error_log($e->getMessage());
        array_push($errors['general'], "Data persistence operation failed");
    }

    finally {

        $statement = null;
        $db = null;
    }

    return $result;
}

function persistEmployee($sql) {

    global $errors;
    global $employee_id, $version, $hire_date, $employee_no, $government_no, $first_name, $last_name, $email, $street, $city, $state_province, $postal_code, $country_code, $salary_employee, $rate, $photoStream;

    $result = false;        // Assume failure!

    try {

        $db = new PDO(DSN);
        $statement = $db->prepare($sql);

        $statement->bindValue(':version',$version + 1);
        $statement->bindValue(':hire_date', $hire_date);
        $statement->bindValue(':employee_no', $employee_no);
        $statement->bindValue(':government_no', $government_no);
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':street', $street);
        $statement->bindValue(':city', $city);
        $statement->bindValue(':state_province', $state_province);
        $statement->bindValue(':postal_code', $postal_code);
        $statement->bindValue(':country_code', $country_code);
        $statement->bindValue(':salary_employee', $salary_employee);
        $statement->bindValue(':rate', $rate);

        if ($employee_id) {

            // Can't have more parameters than names in the query string.

            $statement->bindValue(':employee_id', $employee_id);
            $statement->bindValue(':oldVersion', $version);
        }


        if ($photoStream) {

            $statement->bindParam(':photo', $photoStream, PDO::PARAM_LOB);
        }

        $statement->execute();
        $result = $statement->rowCount();

        if (!$result) {

            $error = 'Data persistence error: ' . implode(', ', $statement->errorInfo());

            error_log($error);
            array_push($errors['general'], "Data persistence operation failed");
        }
    }

    catch (Exception $e) {

        error_log('Data persistence error: ' . $e->getMessage());
        array_push($errors['general'], "Data persistence operation failed");
    }

    finally {

        $statement = null;
        $db = null;
    }

    return $result;
}

function renderErrorMessages($messages, $eol = false) {

    foreach ($messages as $index => $error) {

        echo '<span class="error-message">' . $error . ($eol || $index == count($messages) - 1 ? '' : ', ') . '&nbsp;</span>';
    }
}

function updateEmployee() {

    $sql = <<<'EOT'
UPDATE employees set    version = :version,
                        hire_date = :hire_date,
                        employee_no = :employee_no,
                        government_no = :government_no,
                        first_name = :first_name,
                        last_name = :last_name,
                        email = :email,
                        street = :street,
                        city = :city,
                        state_province = :state_province,
                        postal_code = :postal_code,
                        country_code = :country_code,
                        salary_employee = :salary_employee,
                        rate = :rate
                        WHERE employee_id = :employee_id and version = :oldVersion
EOT;

    return persistEmployee($sql);
}

function validateEmployeeData() {

    global $errors, $isValid;
    global $employee_id, $version, $hire_date, $hd, $employee_no, $government_no, $first_name, $last_name, $email, $street, $city, $state_province, $postal_code, $country_code, $salary_employee, $rate, $photoStream;

    // Get and validate the employee values from the POST data.

    $employee_id = intval($_POST['employee_id']);
    $version = intval($_POST['version']);
    $hire_date = $_POST['hire_date'];
    $employee_no = $_POST['employee_no'];
    $government_no = $_POST['government_no'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state_province = $_POST['state_province'];
    $postal_code = $_POST['postal_code'];
    $country_code = $_POST['country_code'];
    $salary_employee = ($_POST['salary_employee'] ?? null) ? 1 : 0;
    $rate = $_POST['rate'];

    if (!$hire_date) {

        array_push($errors['hire_date'], "hire date is required");
    }

    $timestamp = strtotime($hire_date);

    if (!$timestamp) {

        array_push($errors['hire_date'], "hire date not valid format");

    } else {

        $hire_date = date('Y-m-d', $timestamp);
    }

    if (!$employee_no) {

        array_push($errors['employee_no'], "employee number is required");
    }

    if (!is_numeric($employee_no)) {

        array_push($errors['employee_no'], "employee number may contain only digits");

    } else {

        $employee_no = intval($employee_no);
    }

    if (!$government_no) {

        array_push($errors['government_no'], "government number is required");
    }

    if (!$first_name) {

        array_push($errors['first_name'], "first name is required");
    }

    if (!$last_name) {

        array_push($errors['last_name'], "last name is required");
    }

    if ($rate === '') {

        array_push($errors['rate'], "rate is required");
    }

    if (!is_numeric($rate)) {

        array_push($errors['rate'], "rate must be a floating point value");

    } else {

        $rate = floatval($rate);
    }

    // Set the validation status.

    foreach ($errors as $error) {

        if (!empty($error)) {

            $isValid = false;
            break;
        }
    }

    // Load any photo data that came with the POST request; the temporary file is opened as a stream and will be passed
    // through to the update or insert request to avoid trying to load the whole image into memory at once.
    //
    // This part was placed AFTER validation because there is no point of doing it if validation did not succceed.

    if ($isValid) {

        try {

            $fileData = $_FILES['photo'];

            if (is_array($fileData)) {

                if (!empty($fileData['errors'])) {

                    foreach ($fileData['errors'] as $error) {

                        error_log($error);
                    }

                } else if (!empty($fileData['tmp_name'])) {

                    $photoStream = fopen($fileData['tmp_name'], 'r');
                }
            }
        } catch (Exception $e) {

            error_log($e);
        }
    }
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
                <a href="/login-view.php?logout=true">Logout</a>
            </div>
        </div>
    </header>

    <div id="main" class="y-scroll">

        <div id="content">

            <form method="POST" enctype="multipart/form-data">

                <input type="hidden" name="employee_id" value="<?= $employee_id ?>" />
                <input type="hidden" name="version" value="<?= $version ?>" />

                <div class="error-message">
                    <?php renderErrorMessages($errors['general'], '<br />') ?>
                </div>

                <table class="form">

                    <thead>
                    </thead>

                    <tbody>

                    <tr>
                        <td class="form-label"><label for="hire_date">Hire Date:</label></td>
                        <td class="form-field"><input id="hire_date" name="hire_date" type="text" value="<?= $hire_date ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['hire_date']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <?php $employee_no = ($_SESSION['role'] === 'administrator') ? $employee_no : '***' . substr($employee_no, -4) ?>
                        <td class="form-label"><label for="employee_no">Employee Number:</label></td>
                        <td class="form-field"><input id="employee_no" name="employee_no" type="text" value="<?= $employee_no ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['employee_no']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <?php $government_no = ($_SESSION['role'] === 'administrator') ? $government_no : '***' . substr($government_no, -4) ?>
                        <td class="form-label"><label for="government_no">Government Number:</label></td>
                        <td class="form-field"><input id="government_no" name="government_no" type="text" value="<?= $government_no ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['government_no']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="first_name">First Name:</label></td>
                        <td class="form-field"><input id="first_name" name="first_name" type="text" value="<?= $first_name ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['first_name']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="last_name">Last Name:</label></td>
                        <td class="form-field"><input id="last_name" name="last_name" type="text" value="<?= $last_name ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['last_name']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="email">Email:</label></td>
                        <td class="form-field"><input id="email" name="email" type="text" value="<?= $email ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['email']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="street">Street:</label></td>
                        <td class="form-field"><input id="street" name="street" type="text" value="<?= $street ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['street']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="city">City:</label></td>
                        <td class="form-field"><input id="city" name="city" type="text" value="<?= $city ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['city']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="state_province">State or Province:</label></td>
                        <td class="form-field"><input id="state_province" name="state_province" type="text" value="<?= $state_province ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['state_province']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="postal_code">Postal Code:</label></td>
                        <td class="form-field"><input id="postal_code" name="postal_code" type="text" value="<?= $postal_code ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['postal_code']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="country_code">Country Code:</label></td>
                        <td class="form-field"><input id="country_code" name="country_code" type="text" value="<?= $country_code ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['country_code']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="salary_employee">Salary Employee:</label></td>
                        <td class="form-field"><input id="salary_employee" name="salary_employee" type="checkbox"<?= $salary_employee ? ' checked="checked"' : '' ?>/></td>
                        <td class="form-error"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="rate">Pay Rate:</label></td>
                        <td class="form-field"><input id="rate" name="rate" type="text" value="<?= $rate ?>" /></td>
                        <td class="form-error">
                            <div class="error-message">
                                <?php renderErrorMessages($errors['rate']) ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-label"></td>
                        <td class="form-field">
                            <a href="/employees-view.php"><input type="button" value="Cancel" /></a>
                            <?php if ($_SESSION['role'] === 'administrator') { ?>
                                <input type="submit" />
                            <?php } ?>
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
