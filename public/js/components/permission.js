import {
    formValidation, processSerializeData, scrollToPosition,
    alternateScrollToPosition,
} from "./module.js";

var _token = $('meta[name="csrf-token"]').attr('content');
var permissionTable;

permissionDataTable();

$(document).on('click', '.submitPermissionForm', function() {
    let isValid = formValidation($('#permissionForm'));

    if (isValid) {
        processSerializeData({
            url: '/admin/permission',
            dataForm: $('#permissionForm').serialize(),
        }).then(() => {
            resetPermissionForm();
            permissionTable.ajax.reload();
        });
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

$(document).on('click', '.updatePermissionForm', function() {
    let isValid = formValidation($('#permissionForm'));

    if (isValid) {
        processSerializeData({
            url: `/admin/permission/${$('#permission_id').val()}`,
            dataForm: $('#permissionForm').serialize(),
            method: 'PATCH',
            tables: [permissionTable],
        }).then(() => resetPermissionForm());
    } else {
        $('#spinner').hide();
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

// Reset form
function resetPermissionForm() {
    $('#permissionForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('.updateButtonSection').addClass('d-none');
    $('.submitPermissionForm').removeClass('d-none');
}

$(document).on('click', '.editPermission', function() {
    resetPermissionForm();
    let id = $(this).attr('id');
    $.get(`/admin/permission/${id}/edit`, function(data) {
        // console.log(data, data.parent_id != 0);
        $('.submitPermissionForm').addClass('d-none');
        $('.updateButtonSection').removeClass('d-none');
        $('#permission_id').val(data.id);
        $('#name').val(data.name);
        $('#description').val(data.description);
        if (data.parent_id != 0) {
            $('#route').val(data.route).trigger('change');
            $('#parent_id').val(data.parent_id).trigger('change');
        }
        alternateScrollToPosition($('#permissionForm').find('.form-group').first());
    });
});

// Reset form using reset button (update)
$(document).on('click', '.resetPermissionForm',() => resetPermissionForm());

// Delete Permission
$(document).on('click', '.deletePermission', function() {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Delete!",
        html: `Did you want to proceed to delete this permission ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Delete this permission`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if(result.value == true){
            $('#spinner').show();
            processSerializeData({
                url: `/admin/permission/${id}`,
                tables: [permissionTable],
                method: 'DELETE',
                dataForm: {_token}
            });
        }
    });
});

function permissionDataTable()
{
    permissionTable = $('.permissionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/permission/all-data',
            type: 'GET',
            data: function (d) {
                d._token = _token
            },
            "error": function (xhr, error, thrown) {
                if (xhr && xhr.status == 401) {
                    // window.location = '/';
                    console.log(xhr.responseJSON);

                } else {
                    // window.location.reload();
                    console.log(xhr.responseJSON);

                }
            }
        },
        autoWidth: false,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
            processing: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>Processing...</div>'
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'route', name: 'route' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action', searchable:false, orderable:false}
        ],
    });
}
