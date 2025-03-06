<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/RegistrationController.php';

use MKSpark\Controllers\RegistrationController;

$config = require __DIR__ . '/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new RegistrationController($config);
    $result = $controller->register($data);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
