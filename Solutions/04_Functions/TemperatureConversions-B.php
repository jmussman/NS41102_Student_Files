#!/usr/bin/php

<?php

// TemperatureConversions-B.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

$scriptname = basename( __FILE__ );

if ($argc < 2) {


    echo "usage: $scriptname <c|f> [temp1 [, temp2...]]\n";

} else {

    // The input values are converted to numbers here to provide for single responsibility in the functions,
    // as it is not really their responsibility to deal with the form of the input data.

    $values = convertInputValues(array_slice($argv, 2));

    // The functions in this version are modified to accept a list of numbers as input, and return a list
    // of numbers as output. The numbers from both lists are then compared.

    switch ($argv[1]) {
    
    case 'c':
        $celsiusTemperatures = fahrenheitToCelsius($values);

        foreach ($celsiusTemperatures as $index => $celsius) {

            echo "{$values[$index]} Fahrenheit == $celsius Celsius\n";
        }
        break;
        
    case 'f':
        $fahrenheitTemperatures = celsiusToFahrenheit($values);

        foreach ($fahrenheitTemperatures as $index => $fahrenheit) {

            echo "{$values[$index]} Celsius == $fahrenheit Fahrenheit\n";
        }
        break;
        
    default:
        echo "usage: $scriptname <c|f> [temp1 [, temp2...]]\n";
        break;
    }

    echo "joined", implode(', ', $celsiusTemperatures);
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
