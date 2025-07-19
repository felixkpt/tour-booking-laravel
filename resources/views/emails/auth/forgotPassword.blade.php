@extends('emails.auth.layout')

@section('content')
    <h1>Forget Password Email</h1>
    <p>You can reset your password using the link below:</p>
    <a href="{{ config('app.frontend_url').'/api/password/set/'.$token }}">Reset Password</a>
@endsection
