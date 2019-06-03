<?php
// employees.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Declare the datasource (define is used here only to show the contrast).

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
const DSN = 'sqlite:' . __ROOT__ . '/_data/hr.sqlite';

// Declare the database and statement variables.

$db = null;
$statement = null;

// Launch the main function to get things started.

main();

function main() {

    global $db, $statement;

    // Check for logged-in status.

    session_start();

    if (empty($_SESSION['username'])) {

        header('Location: /login-view.php');
        die();
    }

    // Look for instructions in the query string.

    $like = ctype_upper($_GET['starts-with'] ?? null) ? "{$_GET['starts-with']}%" : 'A%';
    $like = !empty($_GET['like']) ? "%{$_GET['like']}%" : $like;

    // Establish the database connection and query.

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

    $db = new PDO(DSN);

    $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $statement->execute(array(':like' => $like));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Employees</title>
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
                                    <td class="<?= ($_GET['starts-with'] ?? null) == $letter ? 'selected' : '' ?>"><a href="<?= "?starts-with==$letter" ?>"><?= $letter ?></td>
                                <?php } ?>
                                <td>
                                    <a href="/employee-view.php">Add new employee</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="search">
                    <form method="GET">
                        <input name="like" type="text" class="search" placeholder="Search..." value="<?= $_GET['like'] ?? '' ?>" /><input type="submit" value="&#x1f50d;" style="border: none;" />
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
                    <?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <?php $employee_no = $_SESSION['role'] === 'administrator' ? $row['employee_no'] : '***' . substr($row['employee_no'], -4) ?>
                        <td class="minimum-width align-center"><a href="/employee-view.php?employee_id=<?= $row['employee_id'] ?>"><?= $employee_no ?></a></td>
                        <td><a href="/employee-view.php?employee_id=<?= $row['employee_id'] ?>"><?= "{$row['last_name']}, {$row['first_name']}" ?></a></td>
                        <td class="minimum-width"><?= $row['city'] ?></td>
                        <td class="minimum-width align-center"><?= $row['hire_date'] ?></td>
                        <td class="minimum-width align-right"><?= $row['salary_employee'] ? 'salary' : 'hourly' ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>

            <?php
            // Setting the statement and db to null closes the objects and connection.

            $statement = null;
            $db = null;
            ?>

        </div>
    </div>

    <footer>
        Copyright &copy; 2019 NextStep IT Training. All rights reserved.
    </footer>
</body>
</html>
