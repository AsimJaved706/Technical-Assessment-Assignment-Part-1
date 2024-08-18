<?php
session_start();
require_once 'C:/xampp/htdocs/problems/config/db.php';

require_once __DIR__ . '/../Classes/FormManager.php';

$db = getDbConnection();

$formManager = new FormManager($db);
$forms = $formManager->getForms();

echo json_encode(['status' => 'success', 'forms' => $forms]);
