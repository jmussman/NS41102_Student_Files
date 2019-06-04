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

    // TODO: If the request method is GET, call the get function, otherwise if the request method is POST, then call the post function. After
    // they have processed, check the "username" in the session and if it is set redirect to the index page. Otherwise, fall through and
    // display the error.
}

function authorize($loginUsername, $loginPassword) {

    // TODO: For the time being simply check the username and password against known values, and if authenticated
    // add the "username" and "role" keys to the session. The two roles are "administrator" and "staff".
}

function deauthorize() {

    // TODO: Log the user out by clearing the username and role from the session.
}

function get() {

    // TODO: If the page is called via GET with a value for "logout" then call deauthorize to log the user out.
}

function post() {

    // TODO: get the username and password values from the POST data and use authorize to authenticate them.
    // Authenticate will set the state by addng the username to the session. If it does not, add an error
    // message to the $errors array to display when the page repaints.
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
