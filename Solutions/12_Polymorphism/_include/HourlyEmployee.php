<?php
// HourlyEmployee.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Employee.php');

class HourlyEmployee extends Employee
{

    public function __construct() {

        parent::__construct();
    }

    public function calculateWeeklyPay() {

        return is_numeric($this->rate) ? floatval($this->rate) * 40 : 0;
    }
}