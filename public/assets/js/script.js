$(document).ready(function () {
    let fieldIndex = 0;
    $('#add-field-btn').click(function () {
        fieldIndex++;
        const fieldHtml = `
            <div class="mb-3">
                <label class="form-label">Field Type</label>
                <select class="form-select" name="fields[${fieldIndex}][type]">
                    <option value="input">Input</option>
                    <option value="textarea">Textarea</option>
                    <option value="select">Select</option>
                    <option value="radio">Radio</option>
                    <option value="checkbox">Checkbox</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Field Name</label>
                <input type="text" class="form-control" name="fields[${fieldIndex}][name]" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="fields[${fieldIndex}][email_send]" value="1">
                <label class="form-check-label">Send via Email</label>
            </div>
            <div class="mb-3">
                <label class="form-label">Validation Rules</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="fields[${fieldIndex}][validation][required]" value="1">
                    <label class="form-check-label">Required</label>
                </div>
                <div class="mb-2">
                    <label class="form-label">Min Length</label>
                    <input type="number" class="form-control" name="fields[${fieldIndex}][validation][minlength]" placeholder="Min Length">
                </div>
                <div class="mb-2">
                    <label class="form-label">Max Length</label>
                    <input type="number" class="form-control" name="fields[${fieldIndex}][validation][maxlength]" placeholder="Max Length">
                </div>
                <div class="mb-2">
                    <label class="form-label">Pattern</label>
                    <input type="text" class="form-control" name="fields[${fieldIndex}][validation][pattern]" placeholder="Regex Pattern">
                </div>
            </div>
            <hr>
        `;

        $('#field-creation-area').append(fieldHtml);
    });

    $('#dynamic-form').on('submit', function (e) {
        e.preventDefault();
    
        $.ajax({
            url: 'http://localhost/problems/src/Api/create_form.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                console.log('Response:', response); 
    
                if (response.status === 'success') {
                    alert('Form created successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                alert('An error occurred while creating the form.');
            }
        });
    });
    
    $('#dynamic-form-modal').on('submit', function (e) {
        e.preventDefault();
    
        let isValid = true;
        $(this).find('[required]').each(function () {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).addClass('is-invalid'); 
            } else {
                $(this).removeClass('is-invalid');
            }
        });
    
        if (isValid) {
            $.ajax({
                url: 'http://localhost/problems/src/Api/submit_form.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        alert('Form submitted successfully!');
                        $('#formModal').modal('hide'); 
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    alert('An error occurred while submitting the form.');
                }
            });
        } else {
            alert('Please fill in all required fields.');
        }
    });
    
    function loadForms() {
        $.ajax({
            url: 'http://localhost/problems/src/Api/list_forms.php', 
            method: 'GET',
            success: function (response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    let formListHtml = '';
                    res.forms.forEach(function (form) {
                        formListHtml += `<li class="list-group-item"><a href="#" class="open-form-modal" data-form-id="${form.id}">${form.name}</a></li>`;
                    });
                    $('#form-list').html(formListHtml);
                } else {
                    alert('Failed to load forms.');
                }
            },
            error: function () {
                alert('An error occurred while loading the forms.');
            }
        });
    }

    $(document).on('click', '.open-form-modal', function () {
        const formId = $(this).data('form-id');  
        console.log("Setting form ID:", formId); 
    
        $('#modal_form_id').val(formId);
    
        $.ajax({
            url: 'http://localhost/problems/src/Api/load_form.php',
            method: 'GET',
            data: { form_id: formId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const form = response.form;
                    let formHtml = '';
    
                    response.fields.forEach(field => {
                        formHtml += `
                            <div class="mb-3">
                                <label class="form-label">${field.name}</label>
                                ${generateFieldHtml(field)}
                            </div>`;
                    });
    
                    $('#form-container').html(formHtml); 
                    $('#formModal').modal('show');  
                } else {
                    alert('Failed to load the form.');
                }
            },
            error: function () {
                alert('An error occurred while loading the form.');
            }
        });
    });
    
    $(document).ready(function () {
      
        $('.load-form').on('click', function (e) {
            e.preventDefault();
            var formId = $(this).data('form-id'); 
    
            $.ajax({
                url: 'src/Api/load_form.php',
                method: 'GET',
                data: { form_id: formId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#modal_form_id').val(formId); 
                        var formHtml = '';
                        $.each(response.fields, function (index, field) {
                            formHtml += '<div class="mb-3">';
                            formHtml += '<label class="form-label">' + field.name + '</label>';
    
                            if (field.type === 'text' || field.type === 'textarea') {
                                formHtml += '<input type="' + field.type + '" class="form-control" name="' + field.id + '" id="' + field.name + '" required>';
                            } else if (field.type === 'select') {
                                formHtml += '<select class="form-control" name="' + field.id + '" id="' + field.name + '"></select>';
                            } else if (field.type === 'radio' || field.type === 'checkbox') {
                                formHtml += '<input type="' + field.type + '" class="form-check-input" name="' + field.id + '" id="' + field.name + '">';
                            }
    
                            formHtml += '</div>';
                        });
    
                        $('#form-container').html(formHtml); 
                        $('#formModal').modal('show');
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });
    });
    

    function generateFieldHtml(field) {
        switch (field.type) {
            case 'input':
                return `<input type="text" class="form-control" name="${field.name}">`;
            case 'textarea':
                return `<textarea class="form-control" name="${field.name}"></textarea>`;
            case 'select':
                return `<select class="form-select" name="${field.name}"></select>`;
            case 'radio':
                return `<input type="radio" class="form-check-input" name="${field.name}">`;
            case 'checkbox':
                return `<input type="checkbox" class="form-check-input" name="${field.name}">`;
            default:
                return '';
        }
    }
    loadForms();
    
});
