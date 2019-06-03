<?php
// employees-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

// Declare the datasource (define is used here to get the server root into the const).

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
const DSN = 'sqlite:' . __ROOT__ . '/_data/hr.sqlite';

// TODO: for global variables consider that the statement and database must be closed in the HTML below.

// Launch the main function to get things started. FYI: any global variables must be defined ABOVE the
// first function call, or things do not get handled properly by the interpreter.

main();

function main()
{

    // Look for instructions in the query string.

    $like = !empty($_GET['starts-with']) && ctype_upper($_GET['starts-with']) ? "{$_GET['starts-with']}%" : 'A%';
    $like = !empty($_GET['like']) ? "%{$_GET['like']}%" : $like;

    // TODO: The code to figure out the search pattern for the employees table has been given to you, your job
    // is to figure out how to get the query set up as the remainder of the function.
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Employees</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>

<body>
    <header>
        <div id="header">
            <img src="/assets/images/header-logo.png" alt="Human Resources Logo" />
            <div id="menu">
                <a href="/index.php">Home</a>
                <a href="/employees-view.php">Employees</a>
            </div>
        </div>
    </header>

    <div id="main">

        <div id="content">

            <div class="index-search">
                <div class="index">
                    <table class="index">
                        <thead>
                        </thead>

                        <tbody>
                            <tr>
                                <?php foreach (range('A','Z') as $letter) { ?>
                                    <td class="<?= !empty($_GET['starts-with']) && $_GET['starts-with'] == $letter ? 'selected' : '' ?>"><a href="<?= "?starts-with=$letter" ?>"><?= $letter ?></td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="search">
                    <form method="GET">
                        <input name="like" type="text" class="search" placeholder="Search..." value="<?= $_GET['like'] ?? '' ?>" /><input type="submit" value="&#x1f50d;" style="border: none;" />
                    </form>
                </div>
                <div class="clear-all"></div>
            </div>

            <div class="list y-scroll">
                <table class="list">

                    <thead>
                    <tr>
                        <th class="minimum-width">Employee ID</th>
                        <th>Name</th>
                        <th class="minimum-width">City</th>
                        <th class="minimum-width">Hire Date</th>
                        <th class="minimum-width">Employee Type</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php // TODO: the loop to fetch rows goes here, and the table cells need to display the results ?>
                    <tr>
                        <td class="minimum-width align-center"></td>
                        <td></td>
                        <td class="minimum-width"></td>
                        <td class="minimum-width align-center"></td>
                        <td class="minimum-width align-right"></td>
                    </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>

            <?php // TODO: close the database cleanly here ?>

        </div>
    </div>

    <footer>
        Copyright &copy; 2019 NextStep IT Training. All rights reserved.
    </footer>
</body>
</html>
