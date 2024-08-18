<?php
session_start();
require_once 'C:/xampp/htdocs/problems/config/db.php';
require_once __DIR__ . '/../Classes/FormManager.php';
require_once __DIR__ . '/../Classes/EmailSender.php';

$db = getDbConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formId = intval($_POST['form_id']);
    $formManager = new FormManager($db);
    $form = $formManager->getForm($formId);

    if ($form) {
        $errors = [];
        foreach ($form['fields'] as $field) {
            $fieldName = $field['name'];
            $fieldValue = $_POST[$fieldName] ?? '';
            $validation = json_decode($field['validation'], true);

            if (!empty($validation['required']) && $validation['required'] == 1 && empty($fieldValue)) {
                $errors[] = "$fieldName is required.";
            }

            if (!empty($validation['minlength']) && strlen($fieldValue) < $validation['minlength']) {
                $errors[] = "$fieldName must be at least {$validation['minlength']} characters.";
            }

            if (!empty($validation['maxlength']) && strlen($fieldValue) > $validation['maxlength']) {
                $errors[] = "$fieldName must be no more than {$validation['maxlength']} characters.";
            }

            if (!empty($validation['pattern']) && !preg_match($validation['pattern'], $fieldValue)) {
                $errors[] = "$fieldName is not in the correct format.";
            }
        }
        if (empty($errors)) {
            if ($formManager->submitForm($formId, $_POST)) {
                foreach ($form['fields'] as $field) {
                    if ($field['email_send'] == 1) {
                        $emailSender = new EmailSender();
                        $emailSender->sendFieldData($field['name'], $_POST[$field['name']]);
                    }
                }
                echo json_encode(['status' => 'success', 'message' => 'Form submitted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to submit form data']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Form not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
