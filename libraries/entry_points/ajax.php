<?php
/**
 * Generic AJAX endpoint for getting information about database
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

use PhpMyAdmin\Controllers\AjaxController;
use PhpMyAdmin\Core;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Response;
use PhpMyAdmin\Util;

if (! defined('PHPMYADMIN')) {
    exit;
}

global $containerBuilder;

$_GET['ajax_request'] = 'true';

/** @var Response $response */
$response = $containerBuilder->get(Response::class);
$response->setAjax(true);

/** @var DatabaseInterface $dbi */
$dbi = $containerBuilder->get(DatabaseInterface::class);

/** @var AjaxController $controller */
$controller = $containerBuilder->get(AjaxController::class);

if (empty($_POST['type'])) {
    Core::fatalError(__('Bad type!'));
}

switch ($_POST['type']) {
    case 'list-databases':
        $response->addJSON($controller->databases());
        break;
    case 'list-tables':
        Util::checkParameters(['db'], true);
        $response->addJSON($controller->tables([
            'db' => $_POST['db'],
        ]));
        break;
    case 'list-columns':
        Util::checkParameters(['db', 'table'], true);
        $response->addJSON($controller->columns([
            'db' => $_POST['db'],
            'table' => $_POST['table'],
        ]));
        break;
    case 'config-get':
        Util::checkParameters(['key'], true);
        $response->addJSON($controller->getConfig([
            'key' => $_POST['key'],
        ]));
        break;
    case 'config-set':
        Util::checkParameters(['key', 'value'], true);
        $result = $controller->setConfig([
            'key' => $_POST['key'],
            'value' => $_POST['value'],
        ]);
        if ($result !== true) {
            $response->setRequestStatus(false);
            $response->addJSON('message', $result);
        }
        break;
    default:
        Core::fatalError(__('Bad type!'));
}
