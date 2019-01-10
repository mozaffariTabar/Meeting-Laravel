@extends('layouts.master')

@section('title')
    Home page
@stop

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1 class="m-b-md">Meeting</h1>
            @foreach ($errors->all() as $error)
                <p class="warning">
                    {{ $error }}
                </p>
            @endforeach
            {{ Form::open(['url' => 'signup']) }}
                {{ csrf_field() }}
                <p>
                    {{ Form::email("email","",["class" => "form-control", "placeholder" => "Email Address"]) }}
                </p>
                <p>
                    {{ Form::text("phone","",["class" => "form-control", "placeholder" => "Mobile number (ex: 09121112222)"]) }}
                </p>
                <p>
                    {{ Form::text("re-phone","",["class" => "form-control", "placeholder" => "Re-Mobile number"]) }}
                </p>
                <p>
                    {{ Form::submit('SIGNUP',["class" => "btn btn-default"])}}
                </p>
            {{ Form::close() }}
            <a href="login">login</a>
        </div>
    </div>
@stop