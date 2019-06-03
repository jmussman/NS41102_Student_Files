<!DOCTYPE html>
<html>
<!-- index.php
        Copyright © 2019 NextStep IT Training. All rights reserved.
-->

<head>
    <title>Celsius to Fahrenheit</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>
<body>

<?php

$celsius = '';
$fahrenheit = '';

if (!empty( $_GET['celsius'] )) {

    $celsius = floatval($_GET['celsius']);
    $fahrenheit = ($celsius * (9 / 5)) + 32;
}

?>

<form method="GET">
    <table class="form">

        <thead>
        </thead>

        <tbody>
        <tr>
            <td class="form-label">
                Celsius:
            </td>
            <td class="form-field">
                <input type="text" name="celsius" value="<?= $celsius ?>" />
            </td>
        </tr>
        <tr>
            <td class="form-label">
                Fahrenheit:
            </td>
            <td class="form-field">
                <input type="text" name="fahrenheit" value="<?= $fahrenheit ?>" />
            </td>
        </tr>
        </tbody>

        <tr>
            <td class="form-label"></td>
            <td class="form-field">
                <input type="submit" />
            </td>
        </tr>

    </table>
</form>

</body>
</html>