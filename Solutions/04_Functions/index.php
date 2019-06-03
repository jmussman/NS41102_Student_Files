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

if (celsius) {

    $celsiusTemperatures = convertInputValues(preg_split("(\s+|,\s*)", $celsius));

    $fahrenheitTemperatures = celsiusToFahrenheit($celsiusTemperatures);

} elseif ($fahrenheit) {

    $fahrenheitTemperatures = convertInputValues(preg_split("(\s+|,\s*)", $fahrenheit));

    $celsiusTemperatures = fahrenheitToCelsius($fahrenheitTemperatures);
}

function convertInputValues($values) {

    $results = [];

    foreach ($values as $value) {

        array_push($results, floatval($value));
    }

    return $results;
}

function celsiusToFahrenheit($values) {

    $results = [];

    foreach ($values as $value) {

        $fahrenheit = ($value * (9 / 5)) + 32;

        array_push($results, $fahrenheit);
    }

    return $results;
}

function fahrenheitToCelsius($values) {

    $results = [];

    foreach ($values as $value) {

        $celsius = ($value - 32) * (5 / 9);

        array_push($results, $celsius);
    }

    return $results;
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