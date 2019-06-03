<?php
// employee-controller.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/authorization.php');

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

        if ($_SESSION['role'] !== 'administrator') {

            array_push($errors['general'], 'Permission denied');

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
        array_push($errors, "Data persistence operation failed");
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

        // The optimistic concurrency change to the 'version' will have no affect on an insert, except to change the version to 1.

        $statement->bindValue(':version', $version + 1);
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

        $db->beginTransaction();
        $statement->execute();
        $result = $statement->rowCount();

        if ($result && $photoStream) {

            $sql = "UPDATE employees SET photo = :photo WHERE employee_id = :employee_id AND version = :version";
            $statement = $db->prepare($sql);

            $statement->bindParam(':photo', $photoStream, PDO::PARAM_LOB);
            $statement->bindValue(':version', $version + 1);
            $statement->bindValue(':employee_id', $employee_id);
            $statement->execute();
            $result = $statement->rowCount();
        }

        if (!$result) {

            $error = 'Data persistence error: ' . implode(', ', $statement->errorInfo());

            error_log($error);
            array_push($errors['general'], "Data persistence operation failed");

            $db->rollBack();

        } else {

            $db->commit();
        }
    }

    catch (Exception $e) {

        error_log('Data persistence error: ' . $e->getMessage());
        array_push($$errors, "Data persistence operation failed");
    }

    finally {

        $statement = null;
        $db = null;
    }

    return $result;
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
