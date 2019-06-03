<?php
// login-controller.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/authorization.php');

$errors = [];

// Run main to kick things off.

main();

function main() {

    global $username;

    // Process the request data.

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        get();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

        post();
    }

    // If logged in, then move to the employees page.

    if ($username) {

        // Redirect to the index page.

        header('Location: /index.php');
    }

    // Fall through to the login-vew HTML.
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

    if (!$username) {

        array_push($errors, "Invalid credentials provided.");
    }
}
