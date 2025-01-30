import {
    formValidation, processSerializeData, scrollToPosition,
    alternateScrollToPosition,
    in_array,
} from "./module.js";

var _token = $('meta[name="csrf-token"]').attr('content');
var projectTable;

projectDataTable();

// Open modal
$(document).on('click', '#addProject', function () {
    $('#projectModal').modal('show');
    resetprojectForm();
});





$(document).on('click', '#submitprojectForm', function () {

    let isValid = formValidation($('#projectModal'));

    if (isValid) {
        processSerializeData({
            url: '/project',
            dataForm: $('#projectForm').serialize(),
            modal: '#projectModal',
            tables: [projectTable]
        }).then(() => resetprojectForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

$(document).on('click', '#updateUser', function () {
    $('#projectModal').modal('show');
});

$(document).on('click', '#updateprojectForm', function () {
    let isValid = formValidation($('#projectForm'));

    if (isValid) {
        processSerializeData({
            url: `/project/${$('#project_id').val()}`,
            dataForm: $('#projectForm').serialize(),
            modal: '#projectModal',
            tables: [projectTable],
            method: 'PATCH'
        }).then(() => resetprojectForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

// Reset form
function resetprojectForm() {
    $('#projectForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('.projectBtn').text('Create Project').attr('id', 'submitprojectForm');
}

$(document).on('click', '.editProject', function () {
    resetprojectForm();
    $.get(`/project/${$(this).attr('id')}/edit`, function (data) {
        console.log(data);
        $('#project_id').val(data.project.id);
        $('#name').val(data.project.name);
        $('#start_date').val(data.project.start_date);
        $('#end_date').val(data.project.end_date);
        $('#description').val(data.project.description);
        $('.projectBtn').text('Update Project').attr('id', 'updateprojectForm');
        $('#projectModal').modal('show');
    });
});

// Delete menu
$(document).on('click', '.deleteProject', function () {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Delete!",
        html: `Did you want to proceed to delete this Project ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Delete this Project`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if (result.value == true) {
            $('#spinner').show();
            processSerializeData({
                url: `/project/${id}`,
                tables: [projectTable],
                method: 'DELETE',
                dataForm: { _token }
            });
        }
    });
});


function projectDataTable() {
    $('#spinner').show();
    projectTable = $('.projectTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/project/all-data',
            type: 'GET',
            data: function (d) {
                d._token = _token
            },
            complete: function (data) {
                console.log('project', data, data.responseJSON.recordsTotal);
                $('#project_badge').text(`${data.responseJSON.recordsTotal} Projects`);
                $('#spinner').hide();
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
            { data: 'project', name: 'project' },
            { data: 'tasksCount', name: 'taskCount',searchable: false },
            { data: 'projectMgr', name: 'projectMgr' },
            { data: 'assignee', name: 'assignee' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action', searchable: false, orderable: false }
        ],
        columnDefs: [
            {
                targets: [4],
                render: function (data, type, row) {
                    return `<span class="text-muted">${data}</span>`;
                }
            },
        ],
        createdRow: function (row, data, dataIndex) {
            if (data['daysRemain'] < 1  && (data['tasks'].length > data['taskcomplete'].length) ) {
                // console.log(data['debit_gl_id'],row);
                $(row).addClass('table-danger')
            }
        },
    });
}
