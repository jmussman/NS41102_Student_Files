<?php
// IndexController.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');

class IndexController {

    public function __construct() {

        global $authorization;

        // Redirect to the login page if not authorized.

        $authorization->requireAuthorization();
    }
}

$controller = new IndexController();
