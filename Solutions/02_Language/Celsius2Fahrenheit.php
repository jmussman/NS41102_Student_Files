<?php

// Celsius2Fahrenheit.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

$scriptname = basename(__FILE__);
$celsius = 0;

if ($argc != 2) {

    echo "usage: $scriptname <c|f> temp1 [temp2 [, temp3...]]\n";

} else {

    $celsius = floatval( $argv[1] );
    $fahrenheit = ($celsius * ( 9 / 5 )) + 32;

    echo "$celsius Celsius == $fahrenheit Fahrenheit\n";
}
