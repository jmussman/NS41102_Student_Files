<!DOCTYPE html>
<html>
<!-- index.php
        Copyright © 2019 NextStep IT Training. All rights reserved.
-->

<head>
    <title>Temperature Conversion</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>
<body>

<?php

$celsiusTemperatures = [];
$fahrenheitTemperatures = [];

$celsius = $_GET['celsius'] ?? null;
$fahrenheit = $_GET['fahrenheit'] ?? null;

if ($celsius) {

    $celsiusValues = preg_split("(\s+|,\s*)", $celsius);

    foreach ($celsiusValues as $celsiusValue) {

        $celsius = floatval($celsiusValue);
        array_push( $celsiusTemperatures, $celsius);

        $fahrenheit = ($celsius * (9 / 5)) + 32;
        array_push($fahrenheitTemperatures, $fahrenheit );
    }

} elseif ($fahrenheit) {

    $fahrenheitValues = preg_split("(\s+|,\s*)", $fahrenheit);

    foreach ($fahrenheitValues as $fahrenheitValue) {

        $fahrenheit = floatval($fahrenheitValue);
        array_push( $fahrenheitTemperatures, $fahrenheit);

        $celsius = ($fahrenheit - 32) * (5 / 9);
        array_push($celsiusTemperatures, $celsius );
    }

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
                <input type="text" name="celsius" value="<?= implode(', ', $celsiusTemperatures) ?>" />
            </td>
        </tr>
        <tr>
            <td class="form-label">
                Fahrenheit:
            </td>
            <td class="form-field">
                <input type="text" name="fahrenheit" value="<?= implode(', ', $fahrenheitTemperatures) ?>" />
            </td>
        </tr>
        </tbody>

        <tr>
            <td class="form-label"></td>
            <td class="form-field">
                <input type="submit" />&nbsp;
                <input type="reset" />
            </td>
        </tr>

    </table>
</form>

</body>
</html>