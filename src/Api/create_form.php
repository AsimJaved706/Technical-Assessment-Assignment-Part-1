<?php
session_start();
require_once 'C:/xampp/htdocs/problems/config/db.php';

require_once __DIR__ . '/../Classes/FormCreator.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = getDbConnection();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['form_token']) && hash_equals($_SESSION['form_token'], $_POST['form_token'])) {
            $formCreator = new FormCreator($db);
            $formName = $_POST['form_name'];
            $fields = $_POST['fields'] ?? [];

            $result = $formCreator->createForm($formName, $fields);

            if ($result['status'] === 'success') {
                $_SESSION['form_token'] = bin2hex(random_bytes(32));
            }

            echo json_encode($result);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid form submission']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        exit();
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    exit();
}