<?php
// EmployeesController.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');

class EmployeesController {

    public $employee_id = null;
    public $employee_no = null;
    public $first_name = null;
    public $last_name = null;
    public $city = null;
    public $hire_date = null;
    public $salary_employee = null;

    public $startswith = null;
    public $like = null;

    private $db = null;
    private $statement = null;
    private $query = null;

    public function __construct() {

        global $authorization;

        // Redirect to the login page if not authorized.

        $authorization->requireAuthorization();

        $this->restoreState();
        $this->queryEmployees();
    }

    public function next() {

        $result = $this->statement->fetch(PDO::FETCH_BOUND);

        if (!$result) {

            // Nothing more, close the database up.

            $this->statement = null;
            $this->db = null;
        }

        return $result;
    }

    private function queryEmployees() {

        // Establish the database connection and query.

        $this->db = new PDO(DSN);

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

        $this->statement = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $this->statement->bindValue('like', $this->query);

        $this->statement->bindColumn('employee_id', $this->employee_id);
        $this->statement->bindColumn('hire_date', $this->hire_date);
        $this->statement->bindColumn('employee_no', $this->employee_no);
        $this->statement->bindColumn('first_name', $this->first_name);
        $this->statement->bindColumn('last_name', $this->last_name);
        $this->statement->bindColumn('city', $this->city);
        $this->statement->bindColumn('salary_employee', $this->salary_employee, PDO::PARAM_BOOL);   // PDO cannot figure this one out!

        $this->statement->execute();
    }

    private function restoreState() {

        // Look for instruction in the query string; save them in the session and fall back to the session if they are not there.

        if (empty($_SESSION['like'])) {

            $_SESSION['starts-with'] = 'A';     // Default search pattern.
        }

        if (ctype_upper($_GET['starts-with'] ?? null)) {

            $_SESSION['starts-with'] = $_GET['starts-with'];
            unset($_SESSION['like']);

        } elseif (!empty($_GET['like'])) {

            $_SESSION['like'] = $_GET['like'];
            unset($_SESSION['starts-with']);
        }

        $this->startswith = $_SESSION['starts-with'] ?? null;
        !empty($this->startswith) and $this->query = "{$this->startswith}%";

        $this->like = $_SESSION['like'] ?? null;
        !empty($this->like) and $this->query = "%{$this->like}%";
    }
}

$controller = new EmployeesController();
