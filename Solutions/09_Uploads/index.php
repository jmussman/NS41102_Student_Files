<?php
// index.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Run main to start the page.

main();

function main() {

    // Check for logged-in status.

    session_start();

    if (empty($_SESSION['username'])) {

        header('Location: /login-view.php');
        die();
    }
}
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
                <a href="/login-view.php?logout=true">Logout</a>
            </div>
        </div>
    </header>

    <div id="main" class="y-scroll">

        <div id="content">

            <h1>Welcome to Human Resources</h1>

            <ul>
                <li><a href="/employees-view.php">View employees</a></li>
            </ul>

        </div>

    </div>

    <footer>
        Copyright &copy; 2019 NextStep IT Training. All rights reserved.
    </footer>
</body>
</html>
