@extends('layouts.user_login_layout')

@section('title', 'home')

@push('styles')
@endpush

@section('content')

    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Login card -->
        <form class="login-form form-validate" id="forgotPasswordForm">
            @csrf
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">Password recovery</h5>
                        <span class="d-block text-muted">We'll send you instructions in email</span>
                        <div class="alertSection"></div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-right">
                        <input type="email" name="email" class="form-control" placeholder="Your email" required>
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                    </div>

                    <button type="button" class="btn bg-success btn-block" id="forgotPassword"><i class="icon-spinner11 mr-2"></i> Reset password</button>
                </div>
            </div>
        </form>
        <!-- /login card -->

    </div>
    <!-- /content area -->

@endsection

@push('scripts')
<script>

$('#forgotPassword').on('click', function() {
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
        messages: {
            email: {
                required: 'Enter your email address'
            }
        }
    });

    if ($('#forgotPasswordForm').valid()) {
        $('.alertSection').empty();
        $.ajax({
            method: 'POST',
            data: $('#forgotPasswordForm').serialize(),
            dataType:"json",
            url: '/recover-password',
            success: function (data, status, xhr) {
                $('.alertSection').html(`
                    <div class="alert alert-success border-0 alert-dismissible mt-2">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        <span class="font-weight-semibold">Success!
                        </span> Password reset has been sent to your email address.</a> alert message.
                    </div>
                `);
            },
            error:function(xhr) {
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