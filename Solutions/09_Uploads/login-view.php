<?php
// login-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

$errors = [];

// Run main to kick things off.

main();

function main() {

    // Fire up the session for this page.

    session_start();

    // Process the request data.

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        get();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

        post();
    }

    // If logged in, then move to the employees page.

    if ($_SESSION['username']) {

        // Redirect to the index page.

        header('Location: /index.php');
    }

    // Fall through to the login-vew HTML.
}

function authorize($loginUsername, $loginPassword) {

    // This is a very basic authorization; in a real application we could use a database, OAUTH, etc.

    if ($loginUsername === "administrator" and $loginPassword === "password") {

        $_SESSION['username'] = $loginUsername;
        $_SESSION['role'] = $role = 'administrator';

    } elseif ($loginUsername === "staff" and $loginPassword === "password") {

        $_SESSION['username'] = $loginUsername;
        $_SESSION['role'] = $role = 'staff';
    }
}

function deauthorize() {

    $username = $_SESSION['username'] = null;
    $role = $_SESSION['role'] = null;
}

function get() {

    // A GET request is a logout, or just landing on the application and maybe already logged in.

    if (!empty($_GET['logout'])) {

        deauthorize();
    }
}

function post() {

    global $username;

    // A post request is always looking for authentication.

    $form_username = $_POST['username'] ?? null;
    $form_password = $_POST['password'] ?? null;

    authorize($form_username, $form_password);

    if (!$_SESSION['username']) {

        array_push($errors, "Invalid credentials provided.");
    }
}

function renderErrorMessages($messages, $eol = false) {

    foreach ($messages as $index => $error) {

        echo '<span class="error-message">' . $error . ($eol || $index == count($messages) - 1 ? '' : ', ') . '&nbsp;</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles/application.css" />
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

            <form method="POST">

                <div class="error-message">
                    <?php renderErrorMessages($errors) ?>
                </div>

                <table class="form">

                    <thead>
                    </thead>

                    <tbody>
                    <tr>
                        <td class="form-label"><label for="username">Username:</label></td>
                        <td class="form-field"><input id="username" type="text" name="username"></td>
                    </tr>

                    <tr>
                        <td class="form-label"><label for="password">Password:</label></td>
                        <td class="form-field"><input id="password" type="password" name="password"></td>
                    </tr>

                    <tr>
                        <td class="form-label"></td>
                        <td class="form-field"><input type="submit"></td>
                    </tr>
                    </tbody>

                </table>
            </form>
        </div>
    </div>

    <footer>
        Copyright &copy; 2019 NextStep IT Training. All rights reserved.
    </footer>
</body>
</html>
