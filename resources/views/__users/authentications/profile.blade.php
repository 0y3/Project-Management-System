@extends('layouts.mainlayout')

@section('title', 'Audit Log')

@push('styles')
@endpush

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Profile</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content pt-0">
    <!-- Inner container -->
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-300 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">
                {{-- @php
                    $userAvatar = isFileExistsInPublicPath('/images', session('user')['user_avatar'])??'images/default.jpg';
                @endphp --}}
                <!-- Navigation -->
                <div class="card">
                    <div class="card-body bg-indigo-400 text-center card-img-top" style="background-image: url(../../../../global_assets/images/backgrounds/panel_bg.png); background-size: contain;">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="img-fluid rounded-circle profileImage" src="{{ asset('images/default.jpg') }}" width="170" height="170" alt="">
                            {{-- <div class="card-img-actions-overlay rounded-circle">
                                <a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
                                    <i class="icon-plus3"></i>
                                </a>
                                <a href="user_pages_profile.html" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
                                    <i class="icon-link"></i>
                                </a>
                            </div> --}}
                        </div>

                        <h6 class="font-weight-semibold mb-0">{{ Auth::user()->name }}</h6>
                        {{-- <span class="d-block opacity-75">Head of UX</span> --}}

                        {{-- <div class="list-icons list-icons-extended mt-3">
                            <a href="#" class="list-icons-item text-white" data-popup="tooltip" title="" data-container="body" data-original-title="Google Drive"><i class="icon-google-drive"></i></a>
                            <a href="#" class="list-icons-item text-white" data-popup="tooltip" title="" data-container="body" data-original-title="Twitter"><i class="icon-twitter"></i></a>
                            <a href="#" class="list-icons-item text-white" data-popup="tooltip" title="" data-container="body" data-original-title="Github"><i class="icon-github"></i></a>
                        </div> --}}
                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2">
                            {{-- <li class="nav-item-header">Navigation</li> --}}
                            <li class="nav-item">
                                <a href="#profile" class="nav-link active" data-toggle="tab">
                                    <i class="icon-user"></i>
                                     My profile
                                </a>
                            </li>
                            <li class="nav-item-divider"></li>
                            <li class="nav-item">
                                <a href="/logout" class="nav-link" data-toggle="tab">
                                    <i class="icon-switch2"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->


        <!-- Right content -->
        <div class="tab-content w-100">
            <div class="tab-pane fade active show" id="profile">

                <!-- Sales stats -->
                <div class="card">
                    {{-- <div class="card-header header-elements-sm-inline">
                        <h6 class="card-title">Weekly statistics</h6>
                        <div class="header-elements">
                            <span><i class="icon-history mr-2 text-success"></i> Updated 3 hours ago</span>

                            <div class="list-icons ml-3">
                                <a class="list-icons-item" data-action="reload"></a>
                            </div>
                        </div>
                    </div> --}}

                    <div class="card-body">
                        {{-- <div class="chart-container">
                            <div class="chart has-fixed-height" id="tornado_negative_stack"></div>
                        </div> --}}
                        <form id="uploadAvatarForm" onsubmit="uploadAvatar();return false">
                            @csrf
                            <div class="form-group row mb-5">
                                <h2 style="font-size:20px;">Upload Avatar</h2>
                                <div class="col-lg-12">
                                    <input type="file" name="user_avatar" class="file-input-custom" data-show-caption="true" data-show-upload="true" accept="image/*" data-fouc>
                                </div>
                            </div>
                        </form>

                        <form id="changePasswordForm" class="form-validate">
                            @csrf
                            <h2 style="font-size:20px;">Change Password</h2>
                            <div class="alertSection"></div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="old_password" placeholder="Old Password" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="New password" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Password confirmation" required>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-primary submitChangePasswordForm">Change Password <i class="icon-key ml-1"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /sales stats -->

            </div>
        </div>
        <!-- /right content -->

    </div>
    <!-- /inner container -->
</div>
<!-- /content area -->
@endsection

@push('scripts')
<script src="{{asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js')}}"></script>
<script src="{{asset('global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>

<script  >

var _token = $('meta[name="csrf-token"]').attr('content');

$.validator.addMethod("strong_password", function (value, element) {
    let password = value;
    if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
        return false;
    }
    return true;
}, function (value, element) {
    let password = $(element).val();
    if (!(/^(.{8,20}$)/.test(password))) {
        return 'Password must be between 8 to 20 characters long.';
    }
    else if (!(/^(?=.*[A-Z])/.test(password))) {
        return 'Password must contain at least one uppercase.';
    }
    else if (!(/^(?=.*[a-z])/.test(password))) {
        return 'Password must contain at least one lowercase.';
    }
    else if (!(/^(?=.*[0-9])/.test(password))) {
        return 'Password must contain at least one digit.';
    }
    else if (!(/^(?=.*[@#$%&])/.test(password))) {
        return "Password must contain special characters from @#$%&.";
    }
    return false;
});

//
// Change Password
//
$('.submitChangePasswordForm').on('click', function() {
    // Initialize
    $('.form-validate').validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-invalid-label',
        successClass: 'validation-valid-label',
        validClass: 'validation-valid-label',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },

        // Different components require proper error label placement
        errorPlacement: function(error, element) {

            // Input group, styled file input
            if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            password: {
                minlength: 8,
                strong_password: true,
            },
            password_confirmation: {
                required: true,
                equalTo: '#password'
            }
        },
        messages: {
            password: {
                required: 'Enter your new password',
                minlength: jQuery.validator.format("At least {0} characters required")
            },
            old_password: {
                required: 'Enter your old password'
            }
        }
    });

    if ($('#changePasswordForm').valid()) {
        $('.alertSection').empty();
        // $(this).attr('disabled', true);
        $.ajax({
            method: 'POST',
            data: $('#changePasswordForm').serialize(),
            dataType:"json",
            url: '/change-password',
            success: function (data, status, xhr) {
                $('.alertSection').html(`
                    <div class="alert alert-success border-0 alert-dismissible mt-2">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        <span class="font-weight-semibold">Success!
                        </span> ${data.message}.</a>
                    </div>
                `);

                $('#changePasswordForm')[0].reset();
            },
            error:function(xhr) {
                $(this).attr('disabled', false);

                $('.alertSection').html(`
                    <div class="alert alert-danger border-0 alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        <span class="font-weight-semibold">oh!</span> ${xhr.responseJSON.message} <a href="#" class="alert-link">try submitting again</a>.
                    </div>
                `);
            }
        });
    }
});


var FileUpload = function() {


//
// Setup module components
//

// Bootstrap file upload
var _componentFileUpload = function() {
    if (!$().fileinput) {
        console.warn('Warning - fileinput.min.js is not loaded.');
        return;
    }

    // Modal template
    var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header align-items-center">\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';

    // Buttons inside zoom modal
    var previewZoomButtonClasses = {
        toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
        fullscreen: 'btn btn-light btn-icon btn-sm',
        borderless: 'btn btn-light btn-icon btn-sm',
        close: 'btn btn-light btn-icon btn-sm'
    };

    // Icons inside zoom modal classes
    var previewZoomButtonIcons = {
        prev: '<i class="icon-arrow-left32"></i>',
        next: '<i class="icon-arrow-right32"></i>',
        toggleheader: '<i class="icon-menu-open"></i>',
        fullscreen: '<i class="icon-screen-full"></i>',
        borderless: '<i class="icon-alignment-unalign"></i>',
        close: '<i class="icon-cross2 font-size-base"></i>'
    };

    // File actions
    var fileActionSettings = {
        zoomClass: '',
        zoomIcon: '<i class="icon-zoomin3"></i>',
        dragClass: 'p-2',
        dragIcon: '<i class="icon-three-bars"></i>',
        removeClass: '',
        removeErrorClass: 'text-danger',
        removeIcon: '<i class="icon-bin"></i>',
        indicatorNew: '<i class="icon-file-plus text-success"></i>',
        indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
        indicatorError: '<i class="icon-cross2 text-danger"></i>',
        indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    };

    $('.file-input-custom').fileinput({
        previewFileType: 'image',
        browseLabel: 'Select',
        browseClass: 'btn bg-slate-700',
        browseIcon: '<i class="icon-image2 mr-2"></i>',
        removeLabel: 'Remove',
        removeClass: 'btn btn-danger',
        removeIcon: '<i class="icon-cancel-square mr-2"></i>',
        uploadClass: 'btn bg-teal-400',
        uploadIcon: '<i class="icon-file-upload mr-2"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>',
            modal: modalTemplate
        },
        initialCaption: "Please select image",
        mainClass: 'input-group',
        previewZoomButtonClasses: previewZoomButtonClasses,
        previewZoomButtonIcons: previewZoomButtonIcons,
        fileActionSettings: fileActionSettings
    });
};

//
// Return objects assigned to module
//

return {
    init: function() {
        _componentFileUpload();
    }
}
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    FileUpload.init();
});

function uploadAvatar() {
    $.ajax({
        url: '/user/upload-avatar',
        method: 'POST',
        data: new FormData($('#uploadAvatarForm')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            Swal.fire(
                'Success',
                data.message,
                data.status
            ).then(() => getProfileImage())
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire(
                'Oh! Something went wrong',
                'Image not uploaded',
                'error'
            )
        }
    })
}

// Get profile image
function getProfileImage() {
    $.get('/user/profile-avatar', function(data) {
        $('.profileImage').attr('src', `{{ asset('images/${data.avatar}') }}`);
    });
}


</script>
@endpush
