import { 
    formValidation, processSerializeData, scrollToPosition,
    alternateScrollToPosition,
} from "./module.js";

var _token = $('meta[name="csrf-token"]').attr('content');

const table = '.bankTable';
/*
const FxDatatables = function() {
    // Shared variables
    let dt;
    
    // Private functions
    const initDatatable = function() {
        dt = $(table).DataTable({
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "/admin/bank/all-data",
                type: 'post',
                // dataType: "json",
                dataSrc: 'data',
                complete:function (data) {
                    // console.log('cooo',data,data.responseJSON.recordsTotal);
                },
            },
            processing: true,
            serverSide: true,
            pageLength: 15,
            autoWidth: false,
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            destroy: true,
            scrollCollapse: true,
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
            },
            lengthMenu: [[30, 60, 120, 500, -1],[20, 60, 120, 500, 'All'] ],
            columns: [
                // { data: 'member_number' },
                { data: 'name' },
                { data: 'code' },
                { data: 'compliance_officer_fullname' },
                { data: null }
            ],
            columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                        <button type="button" id="${row['id']}" data-id="${row['id']}" data-name="${row['name']}"data-code="${row['code']}" data-compliance_officer_id="${row['compliance_officer_id']}"  class="btn btn-primary btn-sm editbank"><i class="icon-pencil mr-1"></i>Edit</button>
                        `;
                    },
                }
            ]
        });
        
        
    }



    // Public methods
    return {
        init: function() {
            initDatatable();
        }
    }
}();
*/

jQuery(document).ready(function() {
    // FxDatatables.init();
    $(table).DataTable({
        pageLength: 20,
        autoWidth: false,
        responsive: true,
        dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
        // destroy: true,
        scrollCollapse: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '→', 'previous': '←' }
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
        lengthMenu: [[20, 60, 120, 500, -1],[20, 60, 120, 500, 'All'] ],
    });
});

$(document).on('click', '.submitbankForm', function() {
    let isValid = formValidation($('#bankForm'));

    if (isValid) {

        $('#spinner').show();
        let formName = $('#bankForm');
        var formData = new FormData(formName[0]);
        let url =  `/admin/bank/store`;

        axios.post( url,formData)
        .then(function(response){
            // $('div.page-loading').css('display','none');
            $('#spinner').hide();
            console.log(response);
            if(response.status >= 200 && response.status <= 299){
            // if(response.status){
                window.location.reload();
                Swal.fire({
                    text: 'Bank Submitted Successfully',
                    icon: 'success',
                    buttonsStyling: false,
                    confirmButtonText: 'Yes, got it!',
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                    }).then((result) => {
                    if (result.isConfirmed) {
                        // form[0].reset();

                        $('#fxTradeModal').modal('toggle'); // hide Modal
                        // $(table).DataTable().ajax.reload();
                    }
                });
            }
            else{
                $('#spinner').hide();
                console.log(response);
                Swal.fire({
                    text: 'Sorry, Looks like there are some errors detected, please try again',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Yes, got it!',
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        // $(table).DataTable().ajax.reload();
        })
        .catch(error => {
                // $('div.page-loading').css('display','none');
                // form[0].reset();
                $('#spinner').hide();
                console.log(error);
                Swal.fire({
                    text: 'Sorry, Looks like there are some errors detected, please try again',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Yes, got it!',
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
        });
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

$(document).on('click', '.updatebankForm', function() {
    let isValid = formValidation($('#bankForm'));

    if (isValid) {

        $(document).find('span.form-text').remove();
        $('#spinner').show();
        // let formName = $('#bankForm');
        // var formData = new FormData(formName[0]);
        let url = `/admin/bank/update/${$('#bank_id').val()}`;

        $.ajax({
            url: url, 
            method:'POST',
            data:$('#bankForm').serialize(),
            success: function(response){
                console.log(response);
                if(response.status >= 200 && response.status <= 299){
                    $('#spinner').hide();
                    resetbankForm();
                    Swal.fire({
                        text: 'Bank Update Successfully',
                        icon: 'success',
                        buttonsStyling: false,
                        confirmButtonText: 'Yes, got it!',
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    window.location.reload();
                    // $(table).DataTable().ajax.reload(); 
                }
                else{
                    console.log(response);
                    $('#spinner').hide();
                    Swal.fire({
                        text: 'Sorry, Looks like there are some errors detected, please try again',
                        icon: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Yes, got it!',
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            },
            error: function (error) {
                console.log(error);
                $('#spinner').hide();
                Swal.fire({
                    text: 'Sorry, Looks like there are some errors detected, please try again',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Yes, got it!',
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        });
    } else {
        alternateScrollToPosition($('span.text-red').first().parents('.form-group'));
    }
});

// Reset form
function resetbankForm() {
    $('#bankForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('.updateButtonSection').addClass('d-none');
    $('.submitbankForm').removeClass('d-none');
}

$(document).on('click', '.editbank', function() {
    let id = $(this).attr('id');
        //console.log(data);
        $('.submitbankForm').addClass('d-none');
        $('.updateButtonSection').removeClass('d-none');
        $('#bank_id').val($(this).attr('data-id'));
        $('#bank_code').val($(this).attr('data-code'));
        $('#name').val($(this).attr('data-name'));
        if( $(this).attr('data-compliance_officer_id') !='null'){
        $('#parent_id').select2().val($(this).attr('data-compliance_officer_id')).trigger('change');
        }
        alternateScrollToPosition($('#bankForm').find('.form-group').first());

});

// Reset form using reset button (update)
$(document).on('click', '.resetbankForm',() =>resetbankForm());

// Delete bank
$(document).on('click', '.deletebank', function() {
    let id = $(this).attr('id');
    Swal.fire({
        type: 'warning',
        title: "Delete!",
        html: `Did you want to proceed to delete this bank ?`,
        showCancelButton: true,
        confirmButtonText: `Yes, Delete this bank`,
        cancelButtonColor: '#d33',
        confirmButtonColor: "#4CAF50",
        reverseButtons: true
    }).then((result) => {
        if(result.value == true){
            $('#spinner').show();
            processSerializeData({
                url: `/admin/banks/${id}`,
                tables: [table],
                method: 'DELETE',
                dataForm: {_token}
            });
        }
    });
});

// send auth token key email 
$(document).on('click', '.sendauthtokenemail', function() {

        $('#spinner').show();
        let url = `/admin/bank/${$(this).attr('data-id')}/sendtokenemail`;

        axios.post(url)
        .then(function(response){
            // $('div.page-loading').css('display','none');
            $('#spinner').hide();
            console.log(response);
            if(response.status >= 200 && response.status <= 299){
            // if(response.status){
                Swal.fire({
                    text: 'Auth Token Key Email Sent Successfully',
                    icon: 'success',
                    buttonsStyling: false,
                    confirmButtonText: 'Yes, got it!',
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                    }).then((result) => {
                    if (result.isConfirmed) {
                        // form[0].reset();

                        // $('#fxTradeModal').modal('toggle'); // hide Modal
                        // $(table).DataTable().ajax.reload();
                    }
                });
            }
            else{
                $('#spinner').hide();
                console.log(response);
                Swal.fire({
                    text: 'Sorry, Looks like there are some errors detected, please try again',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Yes, got it!',
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        // $(table).DataTable().ajax.reload();
        })
        .catch(error => {
                // $('div.page-loading').css('display','none');
                // form[0].reset();
                $('#spinner').hide();
                console.log(error);
                Swal.fire({
                    text: 'Sorry, Looks like there are some errors detected, please try again',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Yes, got it!',
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
        });

});


// function bankDataTable() 
// {
//     bankTable = $('.bankTable').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: '/admin/bank/all-data',
//             type: 'POST',
//             data: function (d) {
//                 d._token = _token
//             },
//             "error": function (xhr, error, thrown) {
//                 if (xhr && xhr.status == 401) {
//                     // window.location = '/';
//                     console.log(xhr.responseJSON);

//                 } else {
//                     // window.location.reload();
//                     console.log(xhr.responseJSON);

//                 }             
//             }
//         },
//         autoWidth: false,
//         dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
//         language: {
//             search: '<span>Filter:</span> _INPUT_',
//             searchPlaceholder: 'Type to filter...',
//             lengthMenu: '<span>Show:</span> _MENU_',
//             paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
//             processing: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>Processing...</div>'
//         },
//         columns: [
//             { data: 'name', name: 'name' },
//             { data: 'code', name: 'code' },
//             { data: 'compliance_officer_fullname', name: 'compliance_officer_fullname' },
//             { data: 'action', name: 'action', searchable:false, orderable:false}
//         ],
//     });
// }
