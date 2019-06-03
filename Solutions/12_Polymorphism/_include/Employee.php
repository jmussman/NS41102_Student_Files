<?php
// Employee.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

class Employee {

    public $employee_id;
    public $version;
    public $hire_date;
    public $employee_no;
    public $government_no;
    public $first_name;
    public $last_name;
    public $email;
    public $street;
    public $city;
    public $state_province;
    public $postal_code;
    public $country_code;
    public $rate;

    public $errors = [];
    public $isValid = false;

    public function __construct() {

        // Quick way to initialize the error list to match the properties. We don't check the properties to exclude
        // $errors and $isValid because the entries in the array do not affect anything, while the if with the two
        // conditions to look for them slows things down.

        foreach ($this as $key => $value) {

            $this->errors[$key] = [];
        }
    }

    public function namedParameters() {

        // Turn the object into an associative array of parameters for just the data source properties.

        $properties = (array)$this;
        $parameters = [];

        // Errors and validation status are not used in a query.

        unset($properties['errors']);
        unset($properties['isValid']);

        if (!$this->employee_id) {

            // We are not going to pass a zero employee_id through to a query.

            unset($properties['employee_id']);
        }

        foreach ($properties as $key => $value) {

            $parameters[":$key"] = $value;
        }

        return $parameters;
    }

    public function validate() {

        // Get and validate the employee values from the POST data.

        $this->employee_id = intval($this->employee_id);        // Just to make sure it is stored as a number.
        $this->version = intval($this->version);                // Just to make sure it is stored as a number.

        if (!$this->hire_date) {

            array_push($this->errors['hire_date'], "hire date is required");

        } else {

            $timestamp = strtotime($this->hire_date);

            if (!$timestamp) {

                array_push($this->errors['hire_date'], "hire date not valid format");

            } else {

                // After validation dates are always converted to SQL standard YYYY-MM-DD format.

                $this->hire_date = date('Y-m-d', $timestamp);
            }
        }

        if (!$this->employee_no) {

            array_push($this->errors['employee_no'], "employee number is required");
        }

        if (!is_numeric($this->employee_no)) {

            array_push($this->errors['employee_no'], "employee number may only contain digits");

        } else if ($authorization->role !== 'administrator') {

            $this->employee_no = '***' . substr($this->employee_no, -4);

        } else {

            $this->employee_no = intval($this->employee_no);
        }

        if (!$this->government_no) {

            array_push($this->errors['government_no'], "government number is required");
        }

        if ($authorization->role !== 'administrator') {

            $this->government_no = '***' . substr($this->government_no, -4);
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

            // Just to make sure it's stored as a number even if it already is.

            $this->rate = floatval($this->rate);
        }

        // Set the isValid flag to indicate how validation proceeded.

        $this->isValid = true;

        foreach ($this->errors as $value) {

            if (!empty($value)) {

                $this->isValid = false;
                break;
            }
        }
    }
}
