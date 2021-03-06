<?php
/**
 * Simple script to set correct charset for the license
 *
 * Note: please do not fold this script into a general script
 * that would read any file using a GET parameter, it would open a hole
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

use PhpMyAdmin\Response;

if (! defined('PHPMYADMIN')) {
    exit;
}

$response = Response::getInstance();
$response->disable();

header('Content-type: text/plain; charset=utf-8');

$filename = LICENSE_FILE;

// Check if the file is available, some distributions remove these.
if (@is_readable($filename)) {
    readfile($filename);
} else {
    printf(
        __(
            'The %s file is not available on this system, please visit ' .
            '%s for more information.'
        ),
        $filename,
        'https://www.phpmyadmin.net/'
    );
}
