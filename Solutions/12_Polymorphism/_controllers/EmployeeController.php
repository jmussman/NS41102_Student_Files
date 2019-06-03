<?php
// EmployeeController.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/HourlyEmployee.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/SalaryEmployee.php');

class EmployeeController {

    public $employee;
    public $errors = [];

	private $photoStream = null;

	public function __construct() {

        global $authorization;

        // Redirect to the login page if not authorized.

        $authorization->requireAuthorization();

	    // Initial employee values for default.

        $this->employee = new HourlyEmployee();
	    $this->employee->hire_date = date('Y-m-d');

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

        return $this->persistEmployee($sql);
	}

	private function get() {

        // GET request, load and display the employee.

        $this->employee->employee_id = intval($_GET['employee_id'] ?? null);

        if ($this->employee->employee_id) {

            // Retrieve an existing record.

            $this->getEmployee();
        }

        // At this point the variables are either an existing record or initialized for a new record.
    }

    private function getEmployee() {

        $result = false;        // Assume failure!

        try {

            $db = new PDO(DSN);

            // The fun part here is that the first column in the result set is constructed to say the name of the
            // class that this row should be loaded into. When used with PDO::FETCH_CLASSTYPE, PDO will read the
            // first colmnn and instantiate an instance of that type for the result.

            $sql = <<<'EOT'
SELECT  CASE salary_employee WHEN 0 THEN "HourlyEmployee" WHEN 1 THEN "SalaryEmployee" END,
        employee_id,
        version,
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
        rate
        FROM employees WHERE employee_id = :employee_id
EOT;

            $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $statement->bindParam(':employee_id', $this->employee->employee_id);
            $statement->execute();

            $this->employee = $statement->fetch(PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE);
            $result = $this->employee;
        }

        catch (Exception $e) {

            error_log($e->getMessage());
            array_push($this->errors, "Data persistence operation failed");
        }

        finally {

            $statement = null;
            $db = null;
        }

        return $result;
    }

    private function persistEmployee($sql) {

        $result = false;        // Assume failure!

        try {

            $db = new PDO(DSN);

            $salary_employee = get_class($this->employee) === 'SalaryEmployee';

            // Prepare the statement and get the parameter list of properties from the employee object.

            $statement = $db->prepare($sql);
            $namedParameters = $this->employee->namedParameters();

            // There are three ways to handle the parameters: pass an associative array to execute, bind the
            // variables/values to the statement and then call execute, or use a combination of both.  Unfortunately
            // the following (commented) code will work for all of the parameters EXCEPT the photo, because the type
            // of the photo must be set to PDO::PARAM_LOB.
            //

//            $namedParameters[':salary_employee'] = $salary_employee ? 1 : 0;
//            $namedParameters[':version'] = $this->employee->version + 1;
//
//            if ($this->employee->employee_no) {
//
//                $namedParameters[':oldVersion'] = $this->employee->version;
//            }
//
//            if ($this->photoStream) {
//
//                $namedParameters['photo'] = $this->photoStream;
//            }
//
//            $statement->execute($namedParameters);

            // This is the second option; take the array of parameters and bind each one to the statement, then
            // add the salary_employee, oldVersion, and the photo to the bound values.
            //

//            $namedParameters[':salary_employee'] = $salary_employee ? 1 : 0;
//            $namedParameters[':version'] = $this->employee->version + 1;
//
//            if ($this->employee->employee_no) {
//
//                $namedParameters[':oldVersion'] = $this->employee->version;
//            }
//
//            foreach ($namedParameters as $parameter => $value) {
//
//                $statement->bindValue($parameter, $value);
//            }
//
//            if ($this->photoStream) {
//
//                $statement->bindParam(':photo', $this->photoStream, PDO::PARAM_LOB);
//            }
//
//            $statement->execute();

            // This is the third (hybrid) option that wraps two queries in a transaction. This is the preferred
            // pattern because the photo should never be bound to the Employee class, it will have to be added
            // as a separate query when using an ORM like Doctrine or risk moving null back to the database. While
            // keeping it with the employee for PDO is clean in the database, in Doctrine we will actually want
            // to move the photographs to a separate, linked table with its own ORM class.
            //

            $namedParameters[':salary_employee'] = $salary_employee ? 1 : 0;
            $namedParameters[':version'] = $this->employee->version + 1;

            if ($this->employee->employee_no) {

                $namedParameters[':oldVersion'] = $this->employee->version;
            }

            $db->beginTransaction();
            $statement->execute($namedParameters);
            $result = $statement->rowCount();

            if ($result && $this->photoStream) {

                $sql = "UPDATE employees SET photo = :photo WHERE employee_id = :employee_id AND version = :version";
                $statement = $db->prepare($sql);

                $statement->bindParam(':photo', $this->photoStream, PDO::PARAM_LOB);
                $statement->bindValue(':version', $this->employee->version + 1);
                $statement->bindValue(':employee_id', $this->employee->employee_id);
                $statement->execute();

                $result = $statement->rowCount();
            }

            if (!$result) {

                $error = 'Data persistence error: ' . implode(', ', $statement->errorInfo());

                error_log($error);
                array_push($this->errors, "Data persistence operation failed");

                $db->rollBack();

            } else {

                $db->commit();
            }
        }

        catch (Exception $e) {

            error_log($e->getMessage());
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

        if ($_SESSION['role'] !== 'administrators') {

            array_push($this->errors, 'Permission denied');

        } else {

            $this->validateEmployeeData();

            // If the data is valid, then try to update it with a optimistic concurrency

            if ($this->employee->isValid) {

                $result = $this->employee->employee_id ? $this->updateEmployee() : $this->addEmployee();

                if ($result) {

                    // If everything succeeded, redirect back to the employees page before we go any further.

                    header("Location: /views/employees-view.php");
                }
            }

            // Fall through and show any error messages that were logged.
        }
    }

    private function updateEmployee()
    {

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

	    // Figure out which type of employee to create.

        $salary_employee = $_POST['salary_employee'] ?? null;
        $this->employee = $salary_employee ? new SalaryEmployee() : new HourlyEmployee();

        // Spin through the keys in the associative array and map the values into the new employee.

        foreach ($_POST as $key => $value) {

            if (property_exists($this->employee, $key)) {

                $this->employee->{$key} = $value;
            }
        }

        // Adjust boolean and numeric properties (they came in as strings).

        $this->employee->version = intval($this->employee->version);
        $this->employee->rate = floatval($this->employee->rate);
        $this->employee->employee_id = intval($this->employee->employee_id);

        // Validate the employee; the errors will be placed by property name in employee->errors.

        $this->employee->validate();

        if ($this->employee->isValid) {

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
            } catch (Exception $e) {

                error_log($e);
            }
        }
    }
}

$controller = new EmployeeController();
$model = &$controller->employee;
