<?php
// Authorization.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Check for logged-in status and redirect to the landing page if not logged-in.

class Authorization {

    public $username = null;
    public $role = null;

    public function __construct() {

        // Start the session to get $_SESSION.

        session_start();

        // Populate ourselves if authentication has taken place.

        if (!empty($_SESSION['username'])) {

            $this->username = $_SESSION['username'];
            $this->role = $_SESSION['role'] ?? null;
        }
    }

    public function authorize($username, $password) {

        // This is a very basic authorization; in a real application we could use a database, OAUTH, etc.

        if ($username === "administrator" and $password === "password") {

            $_SESSION['username'] = $this->username = $username;
            $_SESSION['role'] = $this->role = 'administrator';

        } elseif ($username === "staff" and $password === "password") {

            $_SESSION['username'] = $this->username = $username;
            $_SESSION['role'] = $this->role = 'staff';
        }
    }

    public function requireAuthorization($errorCode = null) {

        if (!$this->username) {


            if (!$errorCode) {

                // Redirect to the login page and die to prevent any further page processing.

                header('Location: /views/login-view.php');

            } else {

                http_response_code(403);
            }

            die();
        }
    }

    public function deauthorize() {

        $this->username = $_SESSION['username'] = null;
        $this->role = $_SESSION['role'] = null;
    }
}

// This is accessible in the page to quickly get the role.

$authorization = new Authorization();
