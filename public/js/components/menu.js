import {
    formValidation, processSerializeData, scrollToPosition,
    alternateScrollToPosition,
} from "./module.js";

var _token = $('meta[name="csrf-token"]').attr('content');
var menuTable;

menuDataTable();

$(document).on('click', '.submitMenuForm', function() {
    let isValid = formValidation($('#menuForm'));

    if (isValid) {
        processSerializeData({
            url: '/admin/menus',
            dataForm: $('#menuForm').serialize(),
        }).then(() => location.reload());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

$(document).on('click', '.updateMenuForm', function() {
    let isValid = formValidation($('#menuForm'));

    if (isValid) {
        processSerializeData({
            url: `/admin/menus/${$('#menu_id').val()}`,
            dataForm: $('#menuForm').serialize(),
            method: 'PATCH',
            tables: [menuTable],
        }).then(() => resetMenuForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

// Reset form
function resetMenuForm() {
    $('#menuForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('.updateButtonSection').addClass('d-none');
    $('.submitMenuForm').removeClass('d-none');
}

$(document).on('click', '.editMenu', function() {
    resetMenuForm();
    let id = $(this).attr('id');
    $.get(`/admin/menus/${id}/edit`, function(data) {
        console.log(data, data.parent_id != 0);
        $('.submitMenuForm').addClass('d-none');
        $('.updateButtonSection').removeClass('d-none');
        $('#menu_id').val(data.id);
        $('#name').val(data.name);
        $('#description').val(data.description);
        if (data.parent_id != 0) {
            $('#route').val(data.route).trigger('change');
            $('#parent_id').val(data.parent_id).trigger('change');
        }
        $('#roles').val(data.roles.map((role) => role.id)).trigger('change')
        alternateScrollToPosition($('#menuForm').find('.form-group').first());
    });
});

// Reset form using reset button (update)
$(document).on('click', '.resetMenuForm',() => resetMenuForm());

// Delete menu
$(document).on('click', '.deleteMenu', function() {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Delete!",
        html: `Did you want to proceed to delete this menu ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Delete this Menu`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if(result.value == true){
            $('#spinner').show();
            processSerializeData({
                url: `/admin/menus/${id}`,
                tables: [menuTable],
                method: 'DELETE',
                dataForm: {_token}
            });
        }
    });
});

function menuDataTable()
{
    menuTable = $('.menuTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/menus/all-data',
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
            { data: 'roles', name: 'roles' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action', searchable:false, orderable:false}
        ],
    });
}
