<?php
require_once 'C:/xampp/htdocs/problems/config/db.php';
require_once __DIR__ . '/../Classes/FormManager.php';

$db = getDbConnection();
$formManager = new FormManager($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['form_id'])) {
    $formId = intval($_GET['form_id']);
    error_log('Form ID received: ' . $formId);  

    $form = $formManager->getForm($formId);

    if ($form) {
        error_log('Form found: ' . print_r($form, true));  
        echo json_encode([
            'status' => 'success',
            'form' => $form['form'], 
            'fields' => $form['fields']
        ]);
    } else {
        error_log('Form not found for form_id: ' . $formId);  
        echo json_encode(['status' => 'error', 'message' => 'Form not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
