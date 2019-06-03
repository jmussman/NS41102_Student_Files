<?php
// settings.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//
// This file contains application-wide configuration.
//

// Root directory for the application, picked up from the server context.

define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);

// Data Source location.

const DSN = 'sqlite:' . __ROOT__ . '/_data/hr.sqlite';

// Placeholder photo for employee page.

const PLACEHOLDER_PHOTO = 'assets/images/photo-placeholder.png';
