@extends('layouts.user_login_layout')

@section('title', 'home')

@push('styles')
@endpush

@section('content')

    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Login card -->
        <form class="login-form form-validate" id="signupForm">
            @csrf
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h5 class="mb-0">Sign Up</h5>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="first_name" placeholder="First name" required>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="last_name" placeholder="Last name" required>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="mobile_phone" placeholder="Mobile Phone" required>
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>

                    <div class="form-group d-flex align-items-center">
                        <div class="form-check mb-0">
                            <label class="form-check-label">
                                {{-- <input type="checkbox" name="remember" class="form-input-styled" checked data-fouc>
                                Remember --}}
                            </label>
                        </div>

                        <a href="#" class="ml-auto">Forgot password?</a>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary btn-block" id="submitSignupForm">Sign up <i class="icon-circle-right2 ml-2"></i></button>
                    </div>

                    <div class="form-group text-center text-muted content-divider">
                        <span class="px-2">Don't have an account?</span>
                    </div>

                    <div class="form-group">
                        <a href="/" class="btn btn-light btn-block">Sign in</a>
                    </div>
                </div>
            </div>
        </form>
        <!-- /login card -->

    </div>
    <!-- /content area -->

@endsection

@push('scripts')
{{-- <script src="{{asset('assets/js/form_floating.js')}}"></script> --}}
<script>

// const token = window.localStorage.getItem('token');

var validator;

var LoginValidation = function() {

    // Uniform
    var _componentUniform = function() {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform();
    };

    // Validation config
    var _componentValidation = function() {
        if (!$().validate) {
            console.warn('Warning - validate.min.js is not loaded.');
            return;
        }

        // Initialize
        validator = $('.form-validate').validate({
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
                    minlength: 8
                }
            },
            messages: {
                username: "Enter your username",
                password: {
                    required: "Enter your password",
                    minlength: jQuery.validator.format("At least {0} characters required")
                }
            }
        });
    };

    // Return objects assigned to module
    return {
        init: function() {
            _componentUniform();
            _componentValidation();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    LoginValidation.init();  
});

$('#submitSignupForm').on('click', function() {
    console.log($('#signupForm').valid())
    if ($('#signupForm').valid()) {
        $.ajax({
            method: 'POST',
            data: $('#signupForm').serialize(),
            dataType:"json",
            url: '{{ config("app.api_url") }}register',
            success: function (data, status, xhr) {
                window.location.href = '/'
            },
            error:function(xhr) {
                console.log(xhr.responseJSON.message);
            }
        });
    }
});



</script>
@endpush