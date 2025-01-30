import {
    formValidation, processSerializeData, scrollToPosition,
    alternateScrollToPosition,
} from "./module.js";

var _token = $('meta[name="csrf-token"]').attr('content');
var roleTable;

roleDataTable();

$(document).on('click', '.submitRoleForm', function() {
    let isValid = formValidation($('#roleForm'));

    if (isValid) {
        processSerializeData({
            url: '/admin/role',
            dataForm: $('#roleForm').serialize(),
            tables: [roleTable],
        }).then(() => resetRoleForm());;
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

$(document).on('click', '.updateRoleForm', function() {
    let isValid = formValidation($('#roleForm'));

    if (isValid) {
        processSerializeData({
            url: `/admin/role/${$('#role_id').val()}`,
            dataForm: $('#roleForm').serialize(),
            method: 'PATCH',
            tables: [roleTable],
        }).then(() => resetRoleForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

// Reset form
function resetRoleForm() {
    $('#roleForm')[0].reset();
    $('.updateButtonSection').addClass('d-none');
    $('.submitRoleForm').removeClass('d-none');
}

$(document).on('click', '.editRole', function() {
    resetRoleForm();
    let id = $(this).attr('id');
    $.get(`/admin/role/${id}/edit`, function(data) {
        console.log(data);
        $('.submitRoleForm').addClass('d-none');
        $('.updateButtonSection').removeClass('d-none');
        $('#role_id').val(data.id);
        $('#name').val(data.name);
        alternateScrollToPosition($('#roleForm').find('.form-group').first());
    });
});

// Reset form using reset button (update)
$(document).on('click', '.resetRoleForm',() => resetRoleForm());

// Delete Role
$(document).on('click', '.deleteRole', function() {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Delete!",
        html: `Did you want to proceed to delete this role ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Delete this Role`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if(result.value == true){
            $('#spinner').show();
            processSerializeData({
                url: `/admin/role/${id}`,
                tables: [roleTable],
                method: 'DELETE',
                dataForm: {_token}
            });
        }
    });
});

function roleDataTable()
{
    roleTable = $('.roleTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/role/all-data',
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
        // order: [1,'desc'],
        // ordering: false,
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
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
            { data: 'name', name: 'name' },
            { data: 'menus', name: 'menus' },
            { data: 'action', name: 'action', searchable:false, orderable:false}
        ],
    });
}


$('#rolePermissionModal').on('hide.bs.modal', function (e) {
    let modal = $(this)
    $('#rolePermissionModalForm')[0].reset();
    modal.find('.modal-body .row').empty();
    modal.find('#rolePermissionModalForm .modal-title').text('');
    $('#role_id').val('');
});

$('#rolePermissionModal').on('show.bs.modal', function (e) {
    let btn = $(e.relatedTarget) // Button that triggered the modal
    let modal = $(this)

    let id = btn.data('id');
    let name = btn.data('name');
    $('#spinner').show();
    modal.find('.modal-body .row').empty()
    modal.find('#rolePermissionModalForm .modal-title').text(`Update ${name} Role Permissions`);
    $('#role_id').val(id);

    $.get(`/admin/role/permission/${id}`, function(data, status) {
        $.each(data.permissions, function(k, d) {

            // Check if the user has the current permission
            let isChecked = data.role_permission.includes(d.id) ? 'checked' : '';
            modal.find('.modal-body .row').append(`
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" value="${d.name}" name="permissions[]" ${isChecked}>
                                ${d.name}
                            </label>
                        </div>
                    </div>
                </div>
            `);
            // tmpHTML.append(`<option value="${d.user_id}">${d.last_name} ${d.first_name}</option>`)
        });
        // modal.find('.modal-body').html(tmpHTML);
    });
    $('#spinner').hide();
})


$(document).on('submit', '#rolePermissionModalForm', function(e) {
    e.preventDefault();
    $('#spinner').show();
    var formData = $(this).serialize();
    var url = `/admin/role/permission/${$('#role_id').val()}`;
    var that = $(this);
    $.post(url, formData, function(data, textStatus, xhr) {
        $('#spinner').hide();
        if(data.status == "success"){
            $('#rolePermissionModal').modal('hide');
            Swal.fire(data.status,data.message,data.status);
        }
    });
  });
