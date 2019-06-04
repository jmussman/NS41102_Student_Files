<?php
// photo.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//
// This page will redirect to the login page or return the contents of the photo image from the
// database record.
//

// Declare the datasource (define is used here only to show the contrast).

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
const DSN = 'sqlite:' . __ROOT__ . '/_data/hr.sqlite';

// URL to placeholder photo.

const PLACEHOLDER_PHOTO = "/assets/images/photo-placeholder.png";

// Kick off main to get things started.

main();

function main() {

    // Redirect if not logged in.

    session_start();

    if (empty($_SESSION['username'])) {

        // Not logged in cannot see photos, but don't redirect a photo request, just die.

        http_response_code(403);
        die();
    }

    // Get the employee id to retrieve the image for.

    $employee_id = intval($_GET['employee_id']) ?? null;

    if (!is_numeric($employee_id)) {

        // If it isn't a valid number for the employee id, that warrants an error 422.

        http_response_code(422);
        die();
    }

    // Retrieve the image data from the employee record.

    $photoStream = loadEmployeePhoto($employee_id);

    if (!$photoStream) {

        die();

        // Redirect the browser to go get the placeholder image.

        // header('Location: assets/images/photo-placeholder.png');

    } else {

        // Type the image for the response. Most databases return a resource stream for reading the blob, but
        // the PDO_SQLite driver does not. Unfortunate, and we don't really know what the driver is at this point.

        header('Content-Type: ' . typeImage($photoStream));

        if (is_resource($photoStream)) {

            // Send the photo back by efficiently passing the data from the stream through to the output with fpassthru.

            fpassthru($photoStream);

        } else {

            print($photoStream);
        }
    }
}

function loadEmployeePhoto($id) {

    $photoStream = null;

    try {


        $db = $db = new PDO(DSN);
        $sql = 'SELECT  photo FROM employees WHERE employee_id = :employee_id';
        $statement = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $statement->execute(array(':employee_id' => $id));
        $statement->bindColumn('photo', $photoStream, PDO::PARAM_LOB);
        $statement->fetch(PDO::FETCH_BOUND);
    }

    catch (Exception $e) {

        error_log($e->getMessage());
    }

    finally {

        $statement = null;
        $db = null;
    }

    // Check the result, if null load the placeholder photo and return that.

    if (!$photoStream) {

        $photoStream = fopen(PLACEHOLDER_PHOTO, 'rb');
    }

    // In case we fall through to here after a catch return a null image.

    return $photoStream;
}

function typeImage($photoStream) {

    $result = null;

    $imageTypes = array(

        'image/jpeg' => "\xFF\xD8\xFF",
        'image/gif' => 'GIF',
        'image/png' => "\x89PNG\r\n\x1a\n",         // "\x50\x4e\x47\x0d\x0a\x1a\x0a",
        'image/bmp' => 'BM',
        'image/psd' => '8BPS',
        'image/swf' => 'FWS'
    );

    // Nothing is greater than sixteen bytes, so read that from the stream and rewind it. We
    // don't need the whole file, so don't read it all into memory to make this work.
    //
    // Most databases return a resource stream for reading the blob, but the PDO_SQLite driver
    // does not. Unfortunate, and we don't really know what the driver is at this point.

    if (is_resource($photoStream)) {

        $photodata = fread($photoStream, 16);
        rewind($photoStream);

    } else {

        $photodata = substr($photoStream, 0,16);
    }

    foreach ($imageTypes as $type => $signature) {

        $leader = substr($photodata, 0, strlen($signature));

        if ($leader === $signature) {

            $result = $type;
            break;
        }
    }

    return $result;
}
