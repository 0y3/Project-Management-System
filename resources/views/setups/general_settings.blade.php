@extends('layouts.mainlayout')

@section('title', 'Settings')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">

        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Settings</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <!-- <div class="header-elements d-none py-0 mb-3 mb-md-0">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                <span class="breadcrumb-item active">Fund Sources</span>
            </div>
        </div> -->

    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content pt-0">
    <div class="row">
        <div class="col-xl-12">

            <div class="card">
                <div class="card-header header-elements-sm-inline">
                    <h6 class="card-title">Settings</h6>
                    <div class="header-elements">

                    </div>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label>FCTP OVER SOLD</label>
                                    <input placeholder="0" id="fctp_os" type="text" class="form-control numberInputWithCommas">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>FCTP OVER BOUGHT</label>
                                    <input placeholder="0" id="fctp_ob" type="text" class="form-control numberInputWithCommas">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>FCTP TIME</label>
                                    <input id="fctp_time" type="time" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>OVER SOLD POS LIMIT</label>
                                    <input placeholder="0" id="os_pos_limit" type="text" class="form-control numberInputWithCommas">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>OVER BOUGHT POS LIMIT</label>
                                    <input placeholder="0" id="ob_pos_limit" type="text" class="form-control numberInputWithCommas">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>POS LIMIT TIME</label>
                                    <input id="pos_limit_time" type="time" class="form-control">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button onclick="saveSettings()" class="float-right btn btn-primary px-5">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



</div>
<!-- /content area -->
@endsection

@push('scripts')
{{-- <script src="{{asset('global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script> --}}
{{-- <script src="{{asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script> --}}
{{-- <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script> --}}


<script>
    let setinObj = {}

    $(document).ready(function() {
        getSettings()
    });


    // replace inputs with commas.
    const inputs = document.querySelectorAll('.numberInputWithCommas');

    inputs.forEach(input => {
        input.addEventListener("keyup", function() {
            input.value = input.value
                .replace(/[^\d.-]/g, '') // Allow negative sign and remove other invalid characters except digits, dots, and a single negative sign
                .replace(/^\./, '') // Remove leading decimal point
                .replace(/\.(?=.*\.)/g, '') // Remove extra decimal points
                .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'); // Add commas
        });
    });


    const removeCommas = (num) => {
        return num.replace(/[\,]/g, '')
    }


    function getSettings() {
        $('#spinner').show();
        $.ajax({
            method: 'GET',
            dataType: 'JSON',
            url: '/get_settings',
            success: function(data) {
                console.log('settings:=', data);
                setinObj = data
                setDataToFields()
                $('#spinner').hide();

            },
            error: function(data) {
                console.log('Error:', data);
                $('#spinner').hide();
            }
        })
    }

    function setDataToFields() {
        $("#fctp_os").val(setinObj.fctp_os ? parseFloat(setinObj.fctp_os).toLocaleString() : '')
        $("#fctp_ob").val(setinObj.fctp_ob ? parseFloat(setinObj.fctp_ob).toLocaleString() : '')
        $("#os_pos_limit").val(setinObj.os_pos_limit ? parseFloat(setinObj.os_pos_limit).toLocaleString() : '')
        $("#ob_pos_limit").val(setinObj.ob_pos_limit ? parseFloat(setinObj.ob_pos_limit).toLocaleString() : '')

        $("#fctp_time").val(setinObj.fctp_time ? setinObj.fctp_time.split('.')[0] : '00:00')
        $("#pos_limit_time").val(setinObj.pos_limit_time ? setinObj.pos_limit_time.split('.')[0] : '00:00')
    }

    function saveSettings() {
        let dataToSave = {
            fctp_os: $("#fctp_os").val() ? removeCommas($("#fctp_os").val()) : 0,
            fctp_ob: $("#fctp_ob").val() ? removeCommas($("#fctp_ob").val()) : 0,
            os_pos_limit: $("#os_pos_limit").val() ? removeCommas($("#os_pos_limit").val()) : 0,
            ob_pos_limit: $("#ob_pos_limit").val() ? removeCommas($("#ob_pos_limit").val()) : 0,

            pos_limit_time: $("#pos_limit_time").val(),
            fctp_time: $("#fctp_time").val(),
        }

        console.log(dataToSave);

        $('#spinner').show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: dataToSave,
            url: `/update_settings`,
            success: function(data, status, xhr) {
                console.log(data);
                getSettings()
                Swal.fire({
                    toast: true,
                    text: 'Settings Updated',
                    showConfirmButton: false,
                    position: 'top-end',
                    timer: 1500,
                    timerProgressBar: false,
                    icon: 'success'

                })

            },
            error: function(data) {
                console.log('Error:', data);
                $('#spinner').hide();
            }
        })
    }
</script>


@endpush