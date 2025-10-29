$(document).ready(function () {
    // On page load, apply is-invalid border to error fields if any
    $('#form-profile input').each(function() {
        if ($(this).hasClass('is-invalid')) {
            $(this).removeClass('border-dark').addClass('is-invalid bg-white').prop('readonly', false);
        }
    });
    // Edit Profile Button Logic

    $('.btn-edit-profile').on('click', function () {
        // Enable only name, phone, email fields and add border-dark, set bg-white
        $('#form-profile input[name="name"], #form-profile input[name="phone"], #form-profile input[name="email"]').prop('readonly', false).removeClass('bg-light').addClass('bg-white border-dark');
        // Username stays readonly and disabled
        $('#form-profile input[name="username"]').prop('readonly', true).prop('disabled', true).addClass('bg-light').removeClass('bg-white border-dark');
        // Hide edit button, show save and cancel buttons
        $(this).addClass('d-none');
        $('.btn-save-profile').removeClass('d-none');
        $('.btn-cancel-profile').removeClass('d-none');
    });

    // Save Profile Button Logic (optional: disable fields after submit)
    $('#form-profile').on('submit', function () {
        // Optionally disable fields after submit
        $('#form-profile input[name="name"], #form-profile input[name="username"], #form-profile input[name="phone"], #form-profile input[name="email"]').prop('readonly', true).removeClass('bg-white border-dark').addClass('bg-light');
        $('.btn-edit-profile').removeClass('d-none');
        $('.btn-save-profile').addClass('d-none');
        $('.btn-cancel-profile').addClass('d-none');
    });

    // Cancel Profile Button Logic
    $('.btn-cancel-profile').on('click', function () {
        // Disable all inputs except username and remove border-dark, set bg-light
        $('#form-profile input[name="name"], #form-profile input[name="phone"], #form-profile input[name="email"]').prop('readonly', true).removeClass('bg-white border-dark is-invalid').addClass('bg-light');
        // Username stays readonly and disabled
        $('#form-profile input[name="username"]').prop('readonly', true).prop('disabled', true).addClass('bg-light').removeClass('bg-white border-dark');
        // Hide save and cancel, show edit
        $('.btn-save-profile').addClass('d-none');
        $(this).addClass('d-none');
        $('.btn-edit-profile').removeClass('d-none');
    });
});