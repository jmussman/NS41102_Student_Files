<?php
// EmployeeController.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');

class EmployeeController {

    public $errors = [];
    public $isValid = true;

	public $employee_id = 0;
	public $version = 0;
	public $hire_date = '';
	public $employee_no = '';
	public $government_no = '';
	public $first_name = '';
	public $last_name = '';
	public $email = '';
	public $street = '';
	public $city = '';
	public $state_province = '';
	public $postal_code = '';
	public $country_code = '';
	public $salary_employee = false;
	public $rate = 0.0;

	private $photoStream = null;

	public function __construct() {

        global $authorization;

        // Redirect to the login page if not authorized.

        $authorization->requireAuthorization();

        // Initialize the error arrays; this cannot be done as before because a property value cannot be calculated.

        $this->errors['general'] = [];
        $this->errors['hire_date'] = [];
        $this->errors['employee_no'] = [];
        $this->errors['government_no'] = [];
        $this->errors['first_name'] = [];
        $this->errors['last_name'] = [];
        $this->errors['email'] = [];
        $this->errors['street'] = [];
        $this->errors['city'] = [];
        $this->errors['state_province'] = [];
        $this->errors['postal_code'] = [];
        $this->errors['country_code'] = [];
        $this->errors['rate'] = [];

	    // Default the hire-date of a new employee to today's date.

	    $this->hire_date = date('Y-m-d');

	    // Handle the request.

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->get();

        } else {

            $this->post();
        }
	}

    private function addEmployee() {

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
        VALUES (        :version,
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

	    return $this->persistEmployee($sql);
    }

	private function get() {

        // GET request, load and display the employee.

        $this->employee_id = intval($_GET['employee_id'] ?? null);

        if ($this->employee_id) {

            // Retrieve an existing record.

            $this->getEmployee();
        }

        // At this point the variables are either an existing record or initialized for a new record.
    }

    private function getEmployee() {

        $result = false;        // Assume failure!

        try {

            $db = new PDO(DSN);

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
        rate
        FROM employees WHERE employee_id = :employee_id
EOT;

            $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            $statement->bindParam(':employee_id', $this->employee_id);
            $statement->execute();

            $statement->bindColumn('version', $this->version);
            $statement->bindColumn('hire_date', $this->hire_date);
            $statement->bindColumn('employee_no', $this->employee_no);
            $statement->bindColumn('government_no', $this->government_no);
            $statement->bindColumn('first_name', $this->first_name);
            $statement->bindColumn('last_name', $this->last_name);
            $statement->bindColumn('email', $this->email);
            $statement->bindColumn('street', $this->street);
            $statement->bindColumn('city', $this->city);
            $statement->bindColumn('state_province', $this->state_province);
            $statement->bindColumn('postal_code', $this->postal_code);
            $statement->bindColumn('country_code', $this->country_code);
            $statement->bindColumn('salary_employee', $this->salary_employee);
            $statement->bindColumn('rate', $this->rate);

            $statement->fetch(PDO::FETCH_BOUND);

            $result = true;
        }

        catch (Exception $e) {

            error_log($e->getMessage());
            array_push($this->generalErrors, "Data persistence operation failed");
        }

        finally {

            $statement = null;
            $db = null;
        }

        return $result;
    }

    public function persistEmployee($sql) {

        $result = false;        // Assume failure!

        try {

            $db = new PDO(DSN);

            // This solution uses bindParam instead of an associative array because it makes it clearer for execute
            // exactly what is happening. There is no defining the list inside the call to execute, and if the list
            // is defined outside of execute bindValue (or bindParam) is just as fast and more effective. FYI the
            // next lab uses ORM classes, and it makes sense to use the associative array in that case :)
            //

            // The optimistic concurrency change to the 'version' will have no affect on an insert, except to change the version to 1.

            $statement = $db->prepare($sql);
            $statement->bindValue(':version',$this->version + 1);
            $statement->bindValue(':hire_date', $this->hire_date);
            $statement->bindValue(':employee_no', $this->employee_no);
            $statement->bindValue(':government_no', $this->government_no);
            $statement->bindValue(':first_name', $this->first_name);
            $statement->bindValue(':last_name', $this->last_name);
            $statement->bindValue(':email', $this->email);
            $statement->bindValue(':street', $this->street);
            $statement->bindValue(':city', $this->city);
            $statement->bindValue(':state_province', $this->state_province);
            $statement->bindValue(':postal_code', $this->postal_code);
            $statement->bindValue(':country_code', $this->country_code);
            $statement->bindValue(':salary_employee', $this->salary_employee);
            $statement->bindValue(':rate', $this->rate);

            if ($this->employee_id) {

                $statement->bindValue(':employee_id', $this->employee_id);
                $statement->bindValue(':oldVersion', $this->version);
            }

            $db->beginTransaction();
            $statement->execute();
            $result = $statement->rowCount();

            if ($result && $this->photoStream) {

                $sql = "UPDATE employees SET photo = :photo WHERE employee_id = :employee_id AND version = :version";
                $statement = $db->prepare($sql);

                $statement->bindParam(':photo', $this->photoStream, PDO::PARAM_LOB);
                $statement->bindValue(':version', $this->version + 1);
                $statement->bindValue(':employee_id', $this->employee_id);
                $statement->execute();

                $result = $statement->rowCount();
            }

            if (!$result) {

                $error = 'Data persistence error: ' . implode(', ', $statement->errorInfo());

                error_log($error);
                array_push($this->errors['general'], "Data persistence operation failed");

                $db->rollBack();

            } else {

                $db->commit();
            }
        }

        catch (Exception $e) {

            error_log('Data persistence error: ' . $e->getMessage());
            array_push($this->errors, "Data persistence operation failed");
        }

        finally {

            $statement = null;
            $db = null;
        }

        return $result;
    }

    private function post() {

        // Only administrators can update employees.

        if ($_SESSION['role'] !== 'administrator') {

            array_push($generalErrors, 'Permission denied');

        } else {

            $this->validateEmployeeData();

            // If the data is valid, then try to update it with a optimistic concurrency

            if ($this->isValid) {

                $result = $this->employee_id ? $this->updateEmployee() : $this->addEmployee();

                if ($result) {

                    // If everything succeeded, redirect back to the employees page before we go any further.

                    header("Location: /views/employees-view.php");
                }
            }

            // Fall through and show any error messages that were logged.
        }
    }

    private function updateEmployee() {

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

        return $this->persistEmployee($sql);
    }

    private function validateEmployeeData() {

        // Get and validate the employee values from the POST data.

        $this->employee_id = intval($_POST['employee_id']);
        $this->version = intval($_POST['version']);
        $this->hire_date = $_POST['hire_date'];
        $this->employee_no = $_POST['employee_no'];
        $this->government_no = $_POST['government_no'];
        $this->first_name = $_POST['first_name'];
        $this->last_name = $_POST['last_name'];
        $this->email = $_POST['email'];
        $this->street = $_POST['street'];
        $this->city = $_POST['city'];
        $this->state_province = $_POST['state_province'];
        $this->postal_code = $_POST['postal_code'];
        $this->country_code = $_POST['country_code'];
        $this->salary_employee = ($_POST['salary_employee'] ?? null) ? 1 : 0;
        $this->rate = $_POST['rate'];

        if (!$this->hire_date) {

            array_push($this->errors['hire_date'], "hire date is required");
        }

        $timestamp = strtotime($this->hire_date);

        if (!$timestamp) {

            array_push($this->errors['hire_date'], "hire date not valid format");

        } else {

            $this->hire_date = date('Y-m-d', $timestamp);
        }

        if (!$this->employee_no) {

            array_push($this->errors['employee_no'], "employee number is required");
        }

        if (!$this->government_no) {

            array_push($this->errors['government_no'], "government number is required");
        }

        if (!$this->first_name) {

            array_push($this->errors['first_name'], "first name is required");
        }

        if (!$this->last_name) {

            array_push($this->errors['last_name'], "last name is required");
        }

        if ($this->rate === '') {

            array_push($this->errors['rate'], "rate is required");
        }

        if (!is_numeric($this->rate)) {

            array_push($this->errors['rate'], "rate must be a floating point value");

        } else {

            $this->rate = floatval($this->rate);
        }

        // Set the validation status.

        foreach ($this->errors as $error) {

            if (!empty($error)) {

                $this->isValid = false;
                break;
            }
        }

        if ($this->isValid) {

            // Load any photo data that came with the POST request; the temporary file is opened as a stream and will be passed
            // through to the update or insert request to avoid trying to load the whole image into memory at once.
            //
            // This part was placed AFTER validation because there is no point of doing it if validation did not succceed.

            try {

                $fileData = $_FILES['photo'];

                if (is_array($fileData)) {

                    if (!empty($fileData['errors'])) {

                        foreach ($fileData['errors'] as $error) {

                            error_log($error);
                        }

                    } else if (!empty($fileData['tmp_name'])) {

                        $this->photoStream = fopen($fileData['tmp_name'], 'r');
                    }
                }
            }

            catch (Exception $e) {

                error_log($e);
            }
        }
    }
}

$controller = new EmployeeController();


