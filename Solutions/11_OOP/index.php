<!-- index.php
        Copyright © 2019 NextStep IT Training. All rights reserved.
-->

<?php require($_SERVER['DOCUMENT_ROOT'] . '/_controllers/IndexController.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="assets/styles/application.css" />
</head>

<body>
<?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-header.php') ?>

<div id="main" class="y-scroll">

    <div id="content">

        <h1>Welcome to Human Resources</h1>

        <ul>
            <li><a href="/views/employees-view.php">View employees</a></li>
        </ul>

    </div>

</div>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-footer.php') ?>
</div>
</body>
</html>
<?php
