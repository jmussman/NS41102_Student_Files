<!DOCTYPE html>
<html>
<!-- client-side-index.php
        Copyright © 2019 NextStep IT Training. All rights reserved.
-->

<head>
    <title>Temperature Conversion</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
    <script src="/assets/scripts/jquery-3.4.1.min.js"></script>
</head>
<body>

<?php

error_log(date("Y-m-d H:i") . " Parsing PHP content");

$generalErrors = [];
$celsiusErrors = [];
$fahrenheitErrors = [];

$celsiusTemperatures = '';
$fahrenheitTemperatures = '';

main();

function main() {

    global $generalErrors, $celsiusErrors, $fahrenheitErrors;
    global $celsiusTemperatures, $fahrenheitTemperatures;

    if (!empty($_GET['celsius']) && !empty($_GET['fahrenheit'])) {

        array_push($generalErrors, "One of the Celsius or Fahrenheit fields must have one or more numeric values, but not both.");

    } elseif (!empty($_GET['celsius'])) {

        $celsiusTemperatures = $_GET['celsius'];
        $newTemperatures = convertInputValues(preg_split("(\s+|,\s*)", $celsiusTemperatures));

        if (!empty($newTemperatures) and is_numeric($newTemperatures[0])) {

            $celsiusTemperatures = $newTemperatures;
            $fahrenheitTemperatures = celsiusToFahrenheit($celsiusTemperatures);

            $celsiusTemperatures = implode(', ', $celsiusTemperatures);
            $fahrenheitTemperatures = implode(', ', $fahrenheitTemperatures);

        } else {

            $celsiusErrors = $newTemperatures;
        }

    } elseif (!empty($_GET['fahrenheit'])) {

        $fahrenheitTemperatures = $_GET['fahrenheit'];
        $newTemperatures = convertInputValues(preg_split("(\s+|,\s*)", $fahrenheitTemperatures));

        if (!empty($newTemperatures) and is_numeric($newTemperatures[0])) {

            $fahrenheitTemperatures = $newTemperatures;
            $celsiusTemperatures = fahrenheitToCelsius($fahrenheitTemperatures);

            $fahrenheitTemperatures = implode(', ', $fahrenheitTemperatures);
            $celsiusTemperatures = implode(', ', $celsiusTemperatures);

        } else {

            $fahrenheitErrors = $newTemperatures;
        }
    }
}

function convertInputValues($values) {

    $errors = [];
    $results = [];

    foreach ($values as $value) {

        if (is_numeric($value) == false) {

            $message = "'$value' is not a numeric value";

            array_push($errors, $message);
        }

        array_push($results, floatval($value));
    }

    return $errors ? $errors : $results;
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

function renderErrorMessages($messages, $eol = false) {

    foreach ($messages as $index => $error) {

        echo '<span class="error-message">' . $error . ($eol || $index == count($messages) - 1 ? '' : ', ') . '&nbsp;</span>';
    }
}

?>

<form id="temperature-conversion-form" method="GET">

    <p>
        Enter a space or comma (or both) separated list of numbers for either Celsius or Fahrenheit
        and click the <em>Submit</em> button to convert the values.
    </p>

    <div id="general-errors" class="error-message">
        <?php renderErrorMessages($generalErrors, '<br />') ?>
    </div>

    <table class="form">

        <thead>
        </thead>

        <tbody>
        <tr>
            <td class="form-label">
                <label for="celsius">Celsius:</label>
            </td>
            <td class="form-field">
                <input id="celsius" type="text" name="celsius" value="<?= $celsiusTemperatures ?>" />
            </td>
            <td class="form-error">
                <div id="celsius-errors" class="error-message">
                    <?php renderErrorMessages($celsiusErrors) ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="form-label">
                <label for="fahrenheit">Fahrenheit:</label>
            </td>
            <td class="form-field">
                <input id="fahrenheit" type="text" name="fahrenheit" value="<?= $fahrenheitTemperatures ?>" />
            </td>
            <td class="form-error">
                <div id="fahrenheit-errors" class="error-message">
                    <?php renderErrorMessages($fahrenheitErrors) ?>
                </div>
            </td>
        </tr>
        </tbody>

        <tr>
            <td class="form-label"></td>
            <td class="form-field">
                <input type="submit" />
                <input type="reset" />
            </td>
            <td class="form-error"></td>
        </tr>

    </table>

    <script src="/assets/scripts/application.js"></script>
</form>

</body>
</html>