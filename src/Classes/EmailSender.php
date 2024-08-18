<?php
class EmailSender {
    public function sendFieldData($fieldName, $fieldValue) {
        $to = 'test@example.com';
        $subject = 'Form Submission: ' . $fieldName;
        $message = 'Field Value: ' . $fieldValue;
        $headers = 'From: test@test.com' . "\r\n" .
                   'Reply-To: test@test.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }
}
