@extends('layouts.user_login_layout')

@section('title', 'home')

@push('styles')

@endpush

@section('content')

<!-- Content area -->
<div class="content d-flex justify-content-center align-items-center fx-background">

    <!-- Login card -->
    <form class="login-form form-validate" id="loginForm" method="POST" action="{{ route('post-login') }}" >
        @csrf
        <div class="card mb-0" style="background-color: rgba(255,255,255,0.80);">
            <div class="card-body">
                <div class="text-center mb-3">
                    {{-- <img src="images/cbn-logo.png" alt="logo" data-src="images/cbn-logo.png" data-src-retina="images/cbn-logo.png" width="100" style="margin-bottom:20px;"> --}}
                    <h5 class="mb-0">Login to your account</h5>
                </div>

                <div class="alert alert-danger alert-dismissible d-none" id="formErrorSection">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    <span class="font-weight-semibold" id="formError"></span>.
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <div class="form-control-feedback">
                        <i class="icon-envelope text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <div class="form-check mb-0">
                        <label class="form-check-label">
                            {{-- <input type="checkbox" name="remember" class="form-input-styled" checked data-fouc>
                                Remember --}}
                        </label>
                    </div>

                    <a href="/forgot-password" class="ml-auto">Forgot password?</a>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block" id="submitLoginForm">Sign in <i class="icon-circle-right2 ml-2 submiticon"></i></button>
                </div>

                {{-- <div class="form-group text-center text-grey content-divider">
                        <span class="px-2">Don't have an account?</span>
                    </div>

                    <div class="form-group">
                        <a href="/register" class="btn btn-primary btn-block">Sign up</a>
                    </div> --}}


                {{-- <span style="text-align: justify;" class="form-text  small text-muted">The Central Bank of Nigeria (CBN) FX Blotter Reporting System (FXBRS, the System) is a centralised data aggregation system that allows Nigerian deposit money banks (DMBs) to report their FX transactions to the CBN continually with the aide of an Application Programming Interface (API).</span> --}}


                <span class="form-text text-center">By continuing, you're confirming that you've read our <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span>
            </div>
        </div>
    </form>
    <!-- /login card -->

</div>
<!-- /content area -->

@endsection

@push('scripts')
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
                        error.appendTo(element.parent().parent());
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

    $('#submitLoginForm').on('click', function(e) {
        e.preventDefault();
        $('#submitLoginForm').prop('disabled', true);
        $('.submiticon').removeClass('icon-circle-right2').addClass('icon-spinner9 spinner');

        console.log($('#loginForm').valid())
        $('#formErrorSection').addClass('d-none');
        if ($('#loginForm').valid()) {
            $.ajax({
                method: 'POST',
                data: $('#loginForm').serialize(),
                dataType: "json",
                url: '/login',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token here
                },
                success: function(data, status, xhr) {
                $('.submiticon').removeClass('icon-spinner9 spinner').addClass('icon-circle-right2');
                window.location.href = '/task'
                },
                error: function(xhr) {
                    $('#submitLoginForm').prop('disabled', false);
                    $('#formErrorSection').removeClass('d-none');
                    $('#formError').text(xhr.responseJSON.message);
                    $('.submiticon').removeClass('icon-spinner9 spinner').addClass('icon-circle-right2');
                    console.log(xhr.responseJSON.message);
                }
            });
        }
    });
</script>
@endpush
