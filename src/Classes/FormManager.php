<?php
class FormManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getForms() {
        $stmt = $this->db->query("SELECT * FROM forms");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getForm($formId) {
    $stmt = $this->db->prepare("SELECT * FROM forms WHERE id = :id");
    $stmt->bindParam(':id', $formId, PDO::PARAM_INT);
    $stmt->execute();
    $form = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($form) {
        error_log('Form retrieved successfully'); 

        $stmt = $this->db->prepare("SELECT * FROM form_fields WHERE form_id = :form_id");
        $stmt->bindParam(':form_id', $formId, PDO::PARAM_INT);
        $stmt->execute();
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($fields) {
            return [
                'form' => $form,
                'fields' => $fields
            ];
        } else {
            error_log('No fields found for form_id: ' . $formId);
            return null;
        }
    } else {
        error_log('Form not found with ID: ' . $formId);
        return null;
    }
}

    public function submitForm($formId, $formData) {
        $stmt = $this->db->prepare("INSERT INTO submissions (form_id, submission_data) VALUES (:form_id, :data)");
        $stmt->bindParam(':form_id', $formId);
        $stmt->bindParam(':data', json_encode($formData));
        $stmt->execute();
    }

}