import {
    formValidation, processSerializeData, scrollToPosition,
    alternateScrollToPosition,
    in_array,
} from "./module.js";

var _token = $('meta[name="csrf-token"]').attr('content');
var userSetupTable;

userSetupDataTable();

// Open modal
$(document).on('click', '#addUser', function () {
    $('#userSetupModal').modal('show');
    resetUserSetupForm();
});





$(document).on('click', '#submitUserSetupForm', function () {

    let isValid = formValidation($('#userSetupModal'));

    if (isValid) {
        processSerializeData({
            url: '/admin/user-setup',
            dataForm: $('#userSetupForm').serialize(),
            modal: '#userSetupModal',
            tables: [userSetupTable]
        }).then(() => resetUserSetupForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

$(document).on('click', '#updateUser', function () {
    $('#userSetupModal').modal('show');
});

$(document).on('click', '#updateUserSetupForm', function () {
    let isValid = formValidation($('#userSetupForm'));

    if (isValid) {
        processSerializeData({
            url: `/admin/user-setup/${$('#user_id').val()}`,
            dataForm: $('#userSetupForm').serialize(),
            modal: '#userSetupModal',
            tables: [userSetupTable],
            method: 'PATCH'
        }).then(() => resetUserSetupForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

// Reset form
function resetUserSetupForm() {
    $('#userSetupForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('.userSetupBtn').text('Create User').attr('id', 'submitUserSetupForm');
}

$(document).on('click', '.editUser', function () {
    resetUserSetupForm();
    $.get(`/admin/user-setup/${$(this).attr('id')}/edit`, function (data) {
        console.log(data);
        $('#user_id').val(data.user.id);
        $('#name').val(data.user.name);
        $('#email').val(data.user.email);
        // console.log(data.user.roles.map((role) => role.id));
        $('#roles').val(data.user.roles.map((role) => role.name)).trigger('change')
        $('.userSetupBtn').text('Update User').attr('id', 'updateUserSetupForm');
        $('#userSetupModal').modal('show');
    });
});

// Delete menu
$(document).on('click', '.deleteUser', function () {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Delete!",
        html: `Did you want to proceed to delete this user ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Delete this User`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if (result.value == true) {
            $('#spinner').show();
            processSerializeData({
                url: `/admin/user-setup/${id}`,
                tables: [userSetupTable],
                method: 'DELETE',
                dataForm: { _token }
            });
        }
    });
});


// Resend Email
$(document).on('click', '.resendEmail', function () {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'info',
        title: "Resend User Email!",
        html: `Did you want resend user activation email notification?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Resend Email`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if (result.value == true) {
            $('#spinner').show();
            processSerializeData({
                url: `/admin/user-setup/${id}/resend-email-notification`,
                tables: [userSetupTable],
                method: 'POST',
                dataForm: { _token }
            });
        }
    });
});



// $(document).on('change', '#roles', function() {
//     let role = $(this).children("option:selected").val();
//     if(in_array('user'))
// }

function userSetupDataTable() {
    userSetupTable = $('.userSetupTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/user-setup/all-data',
            type: 'GET',
            data: function (d) {
                d._token = _token
            },
            "error": function (xhr, error, thrown) {
                if (xhr && xhr.status == 401) {
                    // window.location = '/';
                    // console.log(xhr.responseJSON);

                } else {
                    // window.location.reload();
                    console.log(xhr.responseJSON);

                }
            }
        },
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
            processing: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>Processing...</div>'
        },
        buttons: {
            dom: {
                button: {
                    className: 'btn btn-light'
                }
            },
            buttons: [
                // 'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles' },
            { data: 'action', name: 'action', searchable: false, orderable: false }
        ],
    });
}
