<?php
// employees-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require($_SERVER['DOCUMENT_ROOT'] . '/_controllers/employees-controller.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/authorization.php');
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
                                    <td class="<?= ($_SESSION['starts-with'] ?? null) == $letter ? 'selected' : '' ?>">
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
                        <label><input name="like" type="text" class="search" placeholder="Search..." value="<?= $_SESSION['like'] ?? '' ?>" /></label>
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
                    <?php while ($row = $statement->fetch(PDO::FETCH_BOUND)) { ?>
                    <tr>
                        <?php
                            // Mask the employee number for everyone except for administrators.

                            if ($role !== 'administrator') {

                                $employee_no = '***' . substr($employee_no, -4);
                            }
                        ?>

                        <td class="minimum-width align-center"><a href="/views/employee-view.php?employee_id=<?= $employee_id ?>"><?= $employee_no ?></a></td>
                        <td><a href="/views/employee-view.php?employee_id=<?= $employee_id ?>"><?= "{$last_name}, {$first_name}" ?></a></td>
                        <td class="minimum-width"><?= $city ?></td>
                        <td class="minimum-width align-center"><?= $hire_date ?></td>
                        <td class="minimum-width align-right"><?= $salary_employee ? 'salary' : 'hourly' ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>
        
        </div>
        
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-footer.php'); ?>

    </div>
</body>
</html>
