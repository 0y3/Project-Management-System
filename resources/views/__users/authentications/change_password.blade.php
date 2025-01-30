@extends('layouts.user_login_layout')

@section('title', 'home')

@push('styles')
@endpush

@section('content')

    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Login card -->
        <form class="login-form form-validate" id="resetPasswordForm">
            @csrf
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">Reset Password</h5>
                        {{-- <span class="d-block text-muted">We'll send you instructions in email</span> --}}
                        <div class="alertSection"></div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-right">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Your password" required>
                    </div>
                    <div class="form-group form-group-feedback form-group-feedback-right">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Password confirmation" required>
                    </div>

                    <input type="hidden" name="token" value="{{ $newToken }}">
                    <button type="button" class="btn bg-success btn-block" id="resetPassword"><i class="icon-spinner11 mr-2"></i> Reset Password </button>
                </div>
            </div>
        </form>
        <!-- /login card -->

    </div>
    <!-- /content area -->

@endsection

@push('scripts')
<script nonce="{{ csp_nonce() }}" >

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

$('#resetPassword').on('click', function() {
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
            }
        }
    });

    if ($('#resetPasswordForm').valid()) {
        $('.alertSection').empty();
        $(this).attr('disabled', true);
        $.ajax({
            method: 'POST',
            data: $('#resetPasswordForm').serialize(),
            dataType:"json",
            url: '/new-password',
            success: function (data, status, xhr) {
                $('.alertSection').html(`
                    <div class="alert alert-success border-0 alert-dismissible mt-2">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        <span class="font-weight-semibold">Success!
                        </span> ${data.message}.</a>
                    </div>
                `);

                setTimeout(() => {
                    window.location.href = '/';
                }, 3000);
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

</script>
@endpush