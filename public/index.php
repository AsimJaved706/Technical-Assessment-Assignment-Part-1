<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$db = getDbConnection();
$_SESSION['form_token'] = bin2hex(random_bytes(32));

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dynamic Form Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <h1 class="mb-4">Dynamic Form Application</h1>

    <h2>Create a New Form</h2>
    <form id="dynamic-form" method="POST" class="mb-5">
      <input type="hidden" name="form_token" value="<?= htmlspecialchars($_SESSION['form_token']) ?>">
      <div style="display:none;">
        <input type="text" name="hp_field" value="">
      </div>
      <div class="mb-3">
        <label for="form_name" class="form-label">Form Name</label>
        <input type="text" class="form-control" id="form_name" name="form_name" required>
      </div>

      <div id="field-creation-area">
      </div>

      <button type="button" id="add-field-btn" class="btn btn-secondary">Add Field</button>
      <button type="submit" class="btn btn-primary">Create Form</button>
    </form>

    <h2>Available Forms</h2>
    <ul id="form-list" class="list-group">
      <?php foreach ($forms as $form): ?>
      <li class="list-group-item">
        <a href="#" class="load-form" data-form-id="<?= $form['id'] ?>"><?= htmlspecialchars($form['name']) ?></a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabel">Form Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="dynamic-form-modal">
            <input type="hidden" name="form_id" id="modal_form_id">
            <div id="form-container"></div>
            <button type="submit" class="btn btn-primary mt-3">Submit Form</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>