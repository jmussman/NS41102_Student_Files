<?php
// authorization.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Check for logged-in status and redirect to the landing page if not logged-in.

$username = null;
$role = null;

// Run main to launch things.

auth_main();

function auth_main() {

    global $username, $role;

    // Start the session to get $_SESSION.

    session_start();

    // Populate ourselves if authentication has taken place.

    if (!empty($_SESSION['username'])) {

        $username = $_SESSION['username'];
        $role = $_SESSION['role'] ?? null;
    }
}

function authorize($loginUsername, $loginPassword) {

    global $username, $role;

    // This is a very basic authorization; in a real application we could use a database, OAUTH, etc.

    if ($loginUsername === "administrator" and $loginPassword === "password") {

        $_SESSION['username'] = $username = $loginUsername;
        $_SESSION['role'] = $role = 'administrator';

    } elseif ($loginUsername === "staff" and $loginPassword === "password") {

        $_SESSION['username'] = $username = $loginUsername;
        $_SESSION['role'] = $role = 'staff';
    }
}

function requireAuthorization($errorCode = null) {

    global $username;

    if (!$username) {

        if (!$errorCode) {

            // Redirect to the login page and die to prevent any further page processing.

            header('Location: /views/login-view.php');

        } else {

            http_response_code(403);
        }

        die();
    }
}

function deauthorize() {

    $username = $_SESSION['username'] = null;
    $role = $_SESSION['role'] = null;
}
