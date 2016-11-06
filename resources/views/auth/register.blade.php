@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('general.register') }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" id="register-form" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">{{ trans('register.name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <label for="type" class="col-md-4 control-label">{{trans('register.type') }}</label>

                            <div class="col-md-6">
                                <select id="type" name="type" class="form-control" required >
                                    <option value="1">{{trans('register.farmer') }}</option>
                                    <option value="2">{{trans('register.agriculturist') }}</option>
                                </select>

                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">{{trans('register.email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">{{trans('register.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">{{trans('register.confirmpass') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button onclick="submitForm()" class="btn btn-info">
                                    {{trans('register.registerbtn') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="registration-errors">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

function submitForm() {
    console.log('submit');
    var errors = [];

    if( $('#name').val()==''){ 
        errors.push('{{trans('register.errors.name')}}');
    }

    var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    if(!testEmail.test($('#email').val())) {
        errors.push('{{trans('register.errors.email')}}');
    }
    
    if($('#password').val()== '' || $('#password').val().length < 6){
        errors.push('{{trans('register.errors.password')}}');
    }

    if($('#password').val() !== $('#password-confirm').val()) {
        errors.push('{{trans('register.errors.passwordconfirm')}}');
    }

    if(errors.length <=0) {
        $('#register-form').submit();
    } else {
         alert(errors.toString());
    }

}
</script>
@endsection
