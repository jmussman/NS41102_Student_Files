<?php
// LoginController.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');

class LoginController {

    public $errors = [];

    public function __construct() {

        global $authorization;

        // Process the request data.

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->get();

        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->post();
        }

        // If logged in, then move to the employees page.

        if ($authorization->username) {

            // Redirect to the index page.

            header('Location: /index.php');
        }

        // Fall through to the login-vew HTML.
    }

    private function get() {

        global $authorization;

        // A GET request is a logout, or just landing on the application and maybe already logged in.

        if (!empty($_GET['logout'])) {

            $authorization->deauthorize();
        }
    }

    private function post() {

        global $authorization;

        // A post request is always looking for authentication.

        $form_username = $_POST['username'] ?? null;
        $form_password = $_POST['password'] ?? null;

        $authorization->authorize($form_username, $form_password);

        if (!$authorization->username) {

            array_push($this->errors, "Invalid credentials provided.");
        }
    }
}

$controller = new LoginController();
