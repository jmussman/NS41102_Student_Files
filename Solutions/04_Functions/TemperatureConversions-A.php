#!/usr/bin/php

<?php

// TemperatureConversions-A.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

echo "Script";
exit();

$scriptname = basename( __FILE__ );

if ($argc < 3) {


    echo "usage: $scriptname <c|f> temp1 [temp2 [, temp3...]]\n";

} else {

    switch ($argv[1]) {
    
    case 'c':
        fahrenheitToCelsius();
        break;
        
    case 'f':
        celsiusToFahrenheit();
        break;
        
    default:
        echo "usage: $scriptname <c|f> temp1 [temp2 [, temp3...]]\n";
        break;
    }
}

function celsiusToFahrenheit() {

    global $argc, $argv;

    for ($i = 2; $i < $argc; $i++) {

        $celsius = floatval($argv[$i]);
        $fahrenheit = ($celsius * (9 / 5)) + 32;

        echo "$celsius Celsius == $fahrenheit Fahrenheit\n";
    }
}

function fahrenheitToCelsius() {

    echo "f2C";

    global $argc, $argv;

    for ($i = 2; $i < $argc; $i++) {

        $fahrenheit = floatval( $argv[$i] );
        $celsius = ($fahrenheit - 32) * (5 / 9);

        echo "$fahrenheit Fahrenheit == $celsius Celsius\n";
    }
}
