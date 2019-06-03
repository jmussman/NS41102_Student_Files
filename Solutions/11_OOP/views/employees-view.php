<?php
// employees-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require($_SERVER['DOCUMENT_ROOT'] . '/_controllers/EmployeesController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employees</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>

<body>
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-header.php'); ?>

    <div id="main">

        <div id="content">

            <div class="index-search">
                <div class="index">
                    <table class="index">
                        <thead>
                        </thead>

                        <tbody>
                            <tr>
                                <?php foreach (range('A','Z') as $letter) { ?>
                                    <td class="<?= $controller->startswith == $letter ? 'selected' : '' ?>">
                                        <a href="<?= "?starts-with=$letter" ?>"><?= $letter ?></a>
                                    </td>
                                <?php } ?>
                                <td>
                                    <a href="/views/employee-view.php">Add new employee</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="search">
                    <form method="GET">
                        <label><input name="like" type="text" class="search" placeholder="Search..." value="<?= $controller->like ?>" /></label>
                        <input type="submit" value="&#x1f50d;" style="border: none;" />
                    </form>
                </div>
                <div class="clear-all"></div>
            </div>

            <div class="list y-scroll">
                <table class="list">

                    <thead>
                    <tr>
                        <th class="minimum-width">Employee ID</th>
                        <th>Name</th>
                        <th class="minimum-width">City</th>
                        <th class="minimum-width">Hire Date</th>
                        <th class="minimum-width">Employee Type</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php while ($controller->next()) { ?>
                    <tr>
                        <?php
                            // Mask the employee number for everyone except for administrators.

                            $employee_no = ($authorization->role !== 'administrator') ? '***' . substr($controller->employee_no, -4) : $controller->employee_no;
                        ?>

                        <td class="minimum-width align-center"><a href="/views/employee-view.php?employee_id=<?= $controller->employee_id ?>"><?= $employee_no ?></a></td>
                        <td><a href="/views/employee-view.php?employee_id=<?= $controller->employee_id ?>"><?= "{$controller->last_name}, {$controller->first_name}" ?></a></td>
                        <td class="minimum-width"><?= $controller->city ?></td>
                        <td class="minimum-width align-center"><?= $controller->hire_date ?></td>
                        <td class="minimum-width align-right"><?= $controller->salary_employee ? 'salary' : 'hourly' ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-footer.php'); ?>
</body>
</html>
