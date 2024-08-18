<?php
class FormCreator {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function createForm($formName, $fields) {
        try {
            $stmt = $this->db->prepare("INSERT INTO forms (name) VALUES (:name)");
            $stmt->bindParam(':name', $formName);
            $stmt->execute();
            $formId = $this->db->lastInsertId();

            if (!$formId) {
                return ['status' => 'error', 'message' => 'Failed to create form.'];
            }

            foreach ($fields as $field) {
                $type = $field['type'];
                $name = $field['name'];
                $validation = json_encode($field['validation']);
                $email_send = isset($field['email_send']) ? 1 : 0;

                $stmt = $this->db->prepare(
                    "INSERT INTO form_fields (form_id, type, name, validation, email_send) 
                     VALUES (:form_id, :type, :name, :validation, :email_send)"
                );
                $stmt->bindParam(':form_id', $formId);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':validation', $validation);
                $stmt->bindParam(':email_send', $email_send, PDO::PARAM_BOOL);
                $result = $stmt->execute();
                if (!$result) {
                    return ['status' => 'error', 'message' => "Failed to insert field {$field['name']}."];
                }
            }
            return ['status' => 'success', 'message' => 'Form created successfully', 'form_id' => $formId];

        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
