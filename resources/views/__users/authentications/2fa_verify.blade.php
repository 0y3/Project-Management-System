@extends('layouts.user_login_layout')

@section('title', 'home')

@push('styles')
@endpush

@section('content')
<!-- Content area -->
<div class="content d-flex justify-content-center align-items-center fx-background
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8 ">
                <div class="card">
                    <div class="card-header">Two Factor Authentication</div>
                    <div class="card-body">
                        <p>Two factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two factor authentication protects against phishing, social engineering and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.</p>
                        
                        @if (isset($data)) 
                            @if ($data['status'] == 'error')
                                <div class="alert alert-danger alert-dismissible" id="formErrorSection">
                                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                                    <span class="font-weight-semibold">{{ $data['message'] }}</span>.
                                </div>
                            @endif
                        @endif

                        Enter the pin from Google Authenticator app:<br/><br/>
                        <form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="one_time_password" class="control-label">One Time Password</label>
                                <input id="one_time_password" name="one_time_password" class="form-control col-md-4"  type="text" required/>
                            </div>
                            <button class="btn btn-primary" type="submit">Authenticate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection