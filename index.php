<?php include('connection.php'); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD Management System</title>

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="css/datatables-1.10.25.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Header -->
    <div class="header">
        <div class="container">
            <h2>
                <i class="fas fa-database"></i>Employee Management System
            </h2>
            <p class="text-center">Simple Data Management System</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <div class="content-card">

                <!-- Action Buttons -->
                <div class="btnAdd">
                    <button type="button" class="btn btn-danger" id="deleteAllBtn">
                        <i class="fas fa-trash-alt"></i> Delete All Records
                    </button>
                    <button type="button" class="btn btn-success" id="openAddModalBtn">
                        <i class="fas fa-user-plus"></i> Add New User
                    </button>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="example" class="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-user"></i> Username</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-mobile-alt"></i> Mobile</th>
                                <th><i class="fas fa-city"></i> City</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            // ==================== INITIALIZE DATATABLE ====================

            var table = $('#example').DataTable({
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData[0]);
                },
                'serverSide': 'true',
                'processing': 'true',
                'paging': 'true',
                'order': [],
                'ajax': {
                    'url': 'fetch_data.php',
                    'type': 'post',
                },
                "aoColumnDefs": [
                    { "bSortable": false, "aTargets": [5] },
                ],
                "language": {
                    "lengthMenu": "Show _MENU_ entries",
                    "search": "Search:",
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i> Previous",
                        "next": "Next <i class='fas fa-chevron-right'></i>"
                    }
                },
                "autoWidth": false,
                "scrollX": false
            });

            $('.dataTables_length select').after('<i class="fas fa-chevron-down dropdown-arrow"></i>');

            // ==================== TOAST HELPER ====================

            function showToast(message, type) {
                var icon = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
                var toast = $('<div class="toast-msg toast-' + type + '"><i class="fas ' + icon + '"></i> ' + message + '</div>');
                $('body').append(toast);
                setTimeout(function() {
                    toast.fadeOut(400, function() { $(this).remove(); });
                }, 3000);
            }

            // ==================== OPEN ADD MODAL ====================

            $('#openAddModalBtn').on('click', function() {
                $('#addUserModal').modal('show');
            });

            // ==================== CLOSE MODAL BUTTONS ====================

            $(document).on('click', '.close-modal-btn', function() {
                var modalId = $(this).data('modal');
                $('#' + modalId).modal('hide');
            });

            // ==================== NAME VALIDATION ====================

            function validateName(name) {
                if (!name || name.trim() === '') {
                    return { valid: false, message: 'Name is required' };
                }
                var parts = name.trim().split(/\s+/);
                if (parts.length < 2) {
                    return { valid: false, message: 'Please enter at least 2 names (first and last name)' };
                }
                for (var i = 0; i < parts.length; i++) {
                    if (!/^[A-Z]/.test(parts[i])) {
                        return { valid: false, message: 'Each name must start with a capital letter (e.g., John Doe)' };
                    }
                }
                return { valid: true, message: '' };
            }

            // ==================== ADD USER FORM ====================

            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();

                // Reset errors
                $('.error-message').removeClass('show');
                $('.form-control').removeClass('error success');

                var isValid = true;
                var username = $('#addUserField').val().trim();
                var email = $('#addEmailField').val().trim();
                var mobile = $('#addMobileField').val().trim();
                var city = $('#addCityField').val().trim();

                // Validate Name
                var nameCheck = validateName(username);
                if (!nameCheck.valid) {
                    $('#addUserField').addClass('error');
                    $('#addUserField-error').html('<i class="fas fa-exclamation-circle"></i> ' + nameCheck.message).addClass('show');
                    isValid = false;
                } else {
                    $('#addUserField').addClass('success');
                }

                // Validate Email
                if (email === '') {
                    $('#addEmailField').addClass('error');
                    $('#addEmailField-error').html('<i class="fas fa-exclamation-circle"></i> Email is required').addClass('show');
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    $('#addEmailField').addClass('error');
                    $('#addEmailField-error').html('<i class="fas fa-exclamation-circle"></i> Enter a valid email').addClass('show');
                    isValid = false;
                } else {
                    $('#addEmailField').addClass('success');
                }

                // Validate Mobile
                if (mobile === '') {
                    $('#addMobileField').addClass('error');
                    $('#addMobileField-error').html('<i class="fas fa-exclamation-circle"></i> Mobile is required').addClass('show');
                    isValid = false;
                } else if (!/^\d{10,}$/.test(mobile.replace(/[\s\-\(\)]/g, ''))) {
                    $('#addMobileField').addClass('error');
                    $('#addMobileField-error').html('<i class="fas fa-exclamation-circle"></i> Enter a valid mobile (min 10 digits)').addClass('show');
                    isValid = false;
                } else {
                    $('#addMobileField').addClass('success');
                }

                // Validate City
                if (city === '') {
                    $('#addCityField').addClass('error');
                    $('#addCityField-error').html('<i class="fas fa-exclamation-circle"></i> City is required').addClass('show');
                    isValid = false;
                } else {
                    $('#addCityField').addClass('success');
                }

                if (!isValid) return false;

                // AJAX submit
                $.ajax({
                    url: "add_user.php",
                    type: "post",
                    data: {
                        username: username,
                        email: email,
                        mobile: mobile,
                        city: city
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json.status == 'true') {
                            table.draw();
                            $('#addUserForm')[0].reset();
                            $('.form-control').removeClass('success');
                            $('#addUserModal').modal('hide');
                            showToast('User added successfully!', 'success');
                        } else {
                            showToast('Failed to add user', 'error');
                        }
                    }
                });
            });

            // ==================== EDIT BUTTON ====================

            $(document).on('click', '.editbtn', function() {
                var id = $(this).data('id');
                var trid = $(this).closest('tr').attr('id');

                $.ajax({
                    url: "get_single_data.php",
                    type: "post",
                    data: { id: id },
                    success: function(data) {
                        var json = JSON.parse(data);
                        $('#editNameField').val(json.username);
                        $('#editEmailField').val(json.email);
                        $('#editMobileField').val(json.mobile);
                        $('#editCityField').val(json.city);
                        $('#editId').val(id);
                        $('#editTrid').val(trid);
                        $('#editUserModal').modal('show');
                    }
                });
            });

            // ==================== UPDATE USER FORM ====================

            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                $('.error-message').removeClass('show');
                $('.form-control').removeClass('error success');

                var isValid = true;
                var username = $('#editNameField').val().trim();
                var email = $('#editEmailField').val().trim();
                var mobile = $('#editMobileField').val().trim();
                var city = $('#editCityField').val().trim();
                var id = $('#editId').val();
                var trid = $('#editTrid').val();

                // Validate Name
                var nameCheck = validateName(username);
                if (!nameCheck.valid) {
                    $('#editNameField').addClass('error');
                    $('#editNameField-error').html('<i class="fas fa-exclamation-circle"></i> ' + nameCheck.message).addClass('show');
                    isValid = false;
                } else {
                    $('#editNameField').addClass('success');
                }

                // Validate Email
                if (email === '') {
                    $('#editEmailField').addClass('error');
                    $('#editEmailField-error').html('<i class="fas fa-exclamation-circle"></i> Email is required').addClass('show');
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    $('#editEmailField').addClass('error');
                    $('#editEmailField-error').html('<i class="fas fa-exclamation-circle"></i> Enter a valid email').addClass('show');
                    isValid = false;
                } else {
                    $('#editEmailField').addClass('success');
                }

                // Validate Mobile
                if (mobile === '') {
                    $('#editMobileField').addClass('error');
                    $('#editMobileField-error').html('<i class="fas fa-exclamation-circle"></i> Mobile is required').addClass('show');
                    isValid = false;
                } else if (!/^\d{10,}$/.test(mobile.replace(/[\s\-\(\)]/g, ''))) {
                    $('#editMobileField').addClass('error');
                    $('#editMobileField-error').html('<i class="fas fa-exclamation-circle"></i> Enter a valid mobile (min 10 digits)').addClass('show');
                    isValid = false;
                } else {
                    $('#editMobileField').addClass('success');
                }

                // Validate City
                if (city === '') {
                    $('#editCityField').addClass('error');
                    $('#editCityField-error').html('<i class="fas fa-exclamation-circle"></i> City is required').addClass('show');
                    isValid = false;
                } else {
                    $('#editCityField').addClass('success');
                }

                if (!isValid) return false;

                $.ajax({
                    url: "update_user.php",
                    type: "post",
                    data: {
                        username: username,
                        email: email,
                        mobile: mobile,
                        city: city,
                        id: id
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json.status == 'true') {
                            var actionButtons = '<a href="javascript:void(0);" data-id="' + id + '" class="btn btn-info btn-sm editbtn"><i class="fas fa-edit"></i> Edit</a> ' +
                                '<a href="javascript:void(0);" data-id="' + id + '" class="btn btn-danger btn-sm deleteBtn"><i class="fas fa-trash-alt"></i> Delete</a>';
                            var row = table.row("[id='" + trid + "']");
                            row.data([id, username, email, mobile, city, actionButtons]).draw();
                            $('#editUserModal').modal('hide');
                            showToast('User updated successfully!', 'success');
                        } else {
                            showToast('Failed to update user', 'error');
                        }
                    }
                });
            });

            // ==================== DELETE BUTTON ====================

            $(document).on('click', '.deleteBtn', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: "delete_user.php",
                        type: "post",
                        data: { id: id },
                        success: function(data) {
                            var json = JSON.parse(data);
                            if (json.status == 'success') {
                                $('#' + id).fadeOut(400, function() { $(this).remove(); });
                                showToast('User deleted successfully!', 'success');
                            } else {
                                showToast('Failed to delete user', 'error');
                            }
                        }
                    });
                }
            });

            // ==================== DELETE ALL BUTTON ====================

            $('#deleteAllBtn').on('click', function() {
                if (confirm('WARNING: This will delete ALL records from the database!\n\nAre you absolutely sure? This cannot be undone.')) {
                    if (confirm('FINAL WARNING: Click OK to delete ALL users permanently.')) {
                        $.ajax({
                            url: "delete_all.php",
                            type: "post",
                            success: function(data) {
                                var json = JSON.parse(data);
                                if (json.status == 'success') {
                                    table.draw();
                                    showToast('All records deleted successfully!', 'success');
                                } else {
                                    showToast('Failed to delete records', 'error');
                                }
                            }
                        });
                    }
                }
            });

            // ==================== RESET FORMS ON MODAL CLOSE ====================

            $('#addUserModal').on('hidden.bs.modal', function() {
                $('#addUserForm')[0].reset();
                $('.form-control').removeClass('error success');
                $('.error-message').removeClass('show');
            });

            $('#editUserModal').on('hidden.bs.modal', function() {
                $('#editUserForm')[0].reset();
                $('.form-control').removeClass('error success');
                $('.error-message').removeClass('show');
            });

        });
    </script>

    <!-- ==================== ADD USER MODAL ==================== -->

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus"></i> Add New User
                    </h5>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <!-- Name -->
                        <div class="input-wrapper">
                            <label class="form-label" for="addUserField">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="addUserField" placeholder="e.g., John Doe">
                            <div class="error-message" id="addUserField-error"></div>
                        </div>

                        <!-- Email -->
                        <div class="input-wrapper">
                            <label class="form-label" for="addEmailField">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="addEmailField" placeholder="e.g., john@example.com">
                            <div class="error-message" id="addEmailField-error"></div>
                        </div>

                        <!-- Mobile -->
                        <div class="input-wrapper">
                            <label class="form-label" for="addMobileField">
                                <i class="fas fa-mobile-alt"></i> Mobile Number
                            </label>
                            <span class="input-icon"><i class="fas fa-mobile-alt"></i></span>
                            <input type="text" class="form-control" id="addMobileField" placeholder="e.g., 0123456789">
                            <div class="error-message" id="addMobileField-error"></div>
                        </div>

                        <!-- City -->
                        <div class="input-wrapper">
                            <label class="form-label" for="addCityField">
                                <i class="fas fa-city"></i> City
                            </label>
                            <span class="input-icon"><i class="fas fa-city"></i></span>
                            <input type="text" class="form-control" id="addCityField" placeholder="e.g., New York">
                            <div class="error-message" id="addCityField-error"></div>
                        </div>

                        <!-- Buttons: Submit + Close side by side -->
                        <div class="form-buttons">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> Add User
                            </button>
                            <button type="button" class="btn btn-secondary close-modal-btn" data-modal="addUserModal">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== EDIT USER MODAL ==================== -->

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit"></i> Edit User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editId">
                        <input type="hidden" id="editTrid">

                        <!-- Name -->
                        <div class="input-wrapper">
                            <label class="form-label" for="editNameField">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="editNameField" placeholder="e.g., John Doe">
                            <div class="error-message" id="editNameField-error"></div>
                        </div>

                        <!-- Email -->
                        <div class="input-wrapper">
                            <label class="form-label" for="editEmailField">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="editEmailField" placeholder="e.g., john@example.com">
                            <div class="error-message" id="editEmailField-error"></div>
                        </div>

                        <!-- Mobile -->
                        <div class="input-wrapper">
                            <label class="form-label" for="editMobileField">
                                <i class="fas fa-mobile-alt"></i> Mobile Number
                            </label>
                            <span class="input-icon"><i class="fas fa-mobile-alt"></i></span>
                            <input type="text" class="form-control" id="editMobileField" placeholder="e.g., 0123456789">
                            <div class="error-message" id="editMobileField-error"></div>
                        </div>

                        <!-- City -->
                        <div class="input-wrapper">
                            <label class="form-label" for="editCityField">
                                <i class="fas fa-city"></i> City
                            </label>
                            <span class="input-icon"><i class="fas fa-city"></i></span>
                            <input type="text" class="form-control" id="editCityField" placeholder="e.g., New York">
                            <div class="error-message" id="editCityField-error"></div>
                        </div>

                        <!-- Buttons: Update + Close side by side -->
                        <div class="form-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                            <button type="button" class="btn btn-secondary close-modal-btn" data-modal="editUserModal">
                                <i class="fas fa-times"></i> Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>