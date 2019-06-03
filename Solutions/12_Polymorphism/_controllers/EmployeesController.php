<?php
// EmployeesController.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/HourlyEmployee.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/SalaryEmployee.php');

class EmployeesController {

    public $employee;
    public $like = null;
    public $startswith = null;

    private $db = null;
    private $query = null;
    private $statement = null;

    public function __construct() {

        global $authorization;

        // Redirect to the login page if not authorized.

        $authorization->requireAuthorization();

        $this->restoreState();
        $this->queryEmployees();
    }

    public function next() {

        $this->employee = $this->statement->fetch(PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE);

        if (!$this->employee) {

            // Nothing more, close the database up.

            $this->statement = null;
            $this->db = null;
        }

        return $this->employee;
    }

    private function queryEmployees() {

        // Establish the database connection and query.

        $this->db = new PDO(DSN);

        $sql = <<<'EOT'
SELECT  CASE salary_employee WHEN 0 THEN "HourlyEmployee" WHEN 1 THEN "SalaryEmployee" END,
        employee_id,
        employee_no,
        hire_date,
        last_name,
        first_name,
        city
        FROM employees WHERE last_name like :like ORDER BY last_name
EOT;

        $this->statement = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $this->statement->bindParam('like', $this->query);
        $this->statement->execute();
    }

    private function restoreState() {

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

        $this->startswith = $_SESSION['starts-with'] ?? null;
        !empty($this->startswith) and $this->query = "{$this->startswith}%";

        $this->like = $_SESSION['like'] ?? null;
        !empty($this->like) and $this->query = "%{$this->like}%";
    }
}

$controller = new EmployeesController();
$model = &$controller->employee;
