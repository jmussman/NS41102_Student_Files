<?php
// index.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>

<body>
    <header>
        <div id="header">
            <img src="/assets/images/header-logo.png" alt="Human Resources Logo" />
            <div id="menu">
                <a href="/index.php">Home</a>
                <a href="/employees-view.php">Employees</a>
            </div>
        </div>
    </header>

    <div id="main" class="y-scroll">

        <div id="content">

            <h1>Welcome to Human Resources</h1>

            <ul>
                <li><a href="/employees-view.php">View employees</a> (with PDO driver)</li>
                <li><a href="/employees-view.sqlite3.php">View employees</a> (with SQLITE3 driver)</li>
            </ul>

        </div>

    </div>

    <footer>
        Copyright &copy; 2019 NextStep IT Training. All rights reserved.
    </footer>
</body>
</html>
