import {
    formValidation, processSerializeData, scrollToPosition,
    alternateScrollToPosition,
    in_array,
} from "./module.js";

var _token = $('meta[name="csrf-token"]').attr('content');
var baseUrl = $('meta[name="url"]').attr('content');
var projectTaskTable;

projectTaskDataTable();

// Open modal
$(document).on('click', '#addTask', function () {
    $('#taskModal').modal('show');
    resetprojectTaskForm();
});





$(document).on('click', '#submitProjectTaskForm', function () {

    let isValid = formValidation($('#taskModal'));

    if (isValid) {
        processSerializeData({
            url: '/project/task/',
            dataForm: $('#projectTaskForm').serialize(),
            modal: '#taskModal',
            tables: [projectTaskTable]
        }).then(() => resetprojectTaskForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

$(document).on('click', '#updateProjectTask', function () {
    $('#taskModal').modal('show');
});

$(document).on('click', '#updateProjectTaskForm', function () {
    let isValid = formValidation($('#projectTaskForm'));

    if (isValid) {
        processSerializeData({
            url: `/project/task/${$('#task_id').val()}`,
            dataForm: $('#projectTaskForm').serialize(),
            modal: '#taskModal',
            tables: [projectTaskTable],
            method: 'PATCH'
        }).then(() => resetprojectTaskForm());
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

// Reset form
function resetprojectTaskForm() {
    $('#projectTaskForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('.taskBtn').text('Create Task').attr('id', 'submitProjectTaskForm');
}

$(document).on('click', '.editProjectTask', function () {
    resetprojectTaskForm();
    $.get(`${baseUrl}/project/task/edit/${$(this).attr('id')}`, function (data) {
        $('#task_id').val(data.task.id);
        $('#name').val(data.task.name);
        $('#start_date').val(data.task.start_date);
        $('#end_date').val(data.task.end_date);
        $('#assignee_id').val(data.task.assignee_id).trigger('change')
        $('#description').val(data.task.description);
        $('.taskBtn').text('Update Task').attr('id', 'updateProjectTaskForm');
        $('#taskModal').modal('show');
    });
});

// Delete menu
$(document).on('click', '.deleteProjectTask', function () {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Delete!",
        html: `Did you want to proceed to delete this Task ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Delete this Task`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if (result.value == true) {
            $('#spinner').show();
            processSerializeData({
                url: `/project/task/delete/${id}`,
                tables: [projectTaskTable],
                method: 'DELETE',
                dataForm: { _token }
            });
        }
    });
});


// completed ProjectTask
$(document).on('click', '.completedProjectTask', function () {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Complete Task Request!",
        html: `Did you want to proceed to complete this Task ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Completed this Task`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if (result.value == true) {
            $('#spinner').show();

            $.post(`/project/task/complete/${id}`,{_token:_token}, function(data, status) {
                if(data.status == "success"){
                    $('#task_per_div').removeClass().addClass(`progress-bar  ${data.data.color}`);
                    $('#task_per_div').css('width',`${data.data.task_per}%`);
                    $('#task_per_div span').text(`${data.data.task_per}% Complete`);
                    $('.projectTaskTable').DataTable().ajax.reload();
                    Swal.fire(
                        data.status,
                        data.message,
                        data.status
                    );
                }

            }).fail(function(e, status, error){
                // Handle the error
                console.error("Error fetching data: ", status, error);
                Swal.fire(
                    e.status,
                    e.message,
                    e.status
                );
                // swal('error',
                //     'Failed to load Bill Category. Please try again',
                //     'error'
                // )// Display an error message

            }).always(function() {
                $("#spinner").hide();
            });
        }
    });
});


function projectTaskDataTable() {
    $('#spinner').show();
    projectTaskTable = $('.projectTaskTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/project/task/all-data/`,
            type: 'GET',
            data: function (d) {
                d._token = _token,
                d.id = $('#project_id').val()
            },
            complete: function (data) {
                // console.log('task', data, data.responseJSON.recordsTotal);
                $('#spinner').hide();
            },
            "error": function (xhr, error, thrown) {
                if (xhr && xhr.status == 401) {
                    // window.location = '/';
                    // console.log(xhr.responseJSON);

                } else {
                    // window.location.reload();
                    // console.log(xhr.responseJSON);

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
            { data: 'name' },
            { data: 'start_date' },
            { data: 'assignee.name' },
            { data: 'status', name: 'status' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action', searchable: false, orderable: false }
        ],
        columnDefs: [
            {
                targets: [0],
                render: function (data, type, row) {
                    return `<span class="font-weight-semibold">${data}</span>`;
                }
            },
            {
                targets: [1],
                render: function (data, type, row) {
                    return `
                    <div class="d-inline-flex align-items-center">
					    <i class="icon-calendar2 mr-2"></i>
                        ${ moment(data).format("MMM Do, YYYY")} <br>-<br> ${ moment(row.end_date).format("MMM Do, YYYY")}
					</div>
                    `;
                }
            },
            {
                targets: [3],
                render: function (data, type, row) {
                    let status = data=='completed'?`<span class="badge bg-success-400">${data}</span>`:
                    (row.daysRemain < 1 )?'<span class="badge bg-danger-400">Overdue</span>':
                    `<span class="badge bg-warning-400">${data??''}</span>`;
                    return status;
                }
            },
            {
                targets: [4],
                render: function (data, type, row) {
                    return `<span class="text-muted">${data??''}</span>`;
                }
            },
        ],
        createdRow: function (row, data, dataIndex) {
            if (data['daysRemain'] < 1  && data['status'] == "in progress" ) {
                $(row).addClass('table-danger')
            }
        },
    });
}
