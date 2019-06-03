<?php
// login.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require($_SERVER['DOCUMENT_ROOT'] . '/_controllers/LoginController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Utility.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>

<body>
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-header.php') ?>

    <div id="main" class="y-scroll">

        <div id="content">

            <form method="POST">

                <div class="error-message">
                    <?php Utility::renderErrorMessages($controller->errors) ?>
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

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-footer.php') ?>
</body>
</html>