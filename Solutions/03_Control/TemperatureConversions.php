#!/usr/bin/php

<?php

// TemperatureConversions.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

$scriptname = basename( __FILE__ );

if ($argc < 3) {


    echo "usage: $scriptname <c|f> temp1 [temp2 [, temp3...]]\n";

} else {

    switch ($argv[1]) {

    case 'c':
        for ($i = 2; $i < $argc; $i++) {

            $fahrenheit = floatval( $argv[$i] );
            $celsius = ($fahrenheit - 32) * (5 / 9);
            echo "$fahrenheit Fahrenheit == $celsius Celsius\n";
        }
        break;

    case 'f':
        for ($i = 2; $i < $argc; $i++) {

            $celsius = floatval( $argv[$i] );
            $fahrenheit = ($celsius * ( 9 / 5 )) + 32;
            echo "$celsius Celsius == $fahrenheit Fahrenheit\n";
        }
        break;

    default:
        echo "usage: $scriptname <c|f> temp1 [temp2 [, temp3...]]\n";
        break;
    }
}