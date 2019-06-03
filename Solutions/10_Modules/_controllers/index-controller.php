<?php
// index-controller.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require_once($_SERVER['DOCUMENT_ROOT'] . '/_config/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/authorization.php');

// Launch the main fucntion.

main();

function main() {

    // Redirect to the login page if not authorized.

    requireAuthorization();
}
