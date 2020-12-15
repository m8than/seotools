@extends('layouts.app')

@section('title', 'Login')
@section('page-modifiers', 'page-container--login')

@section('content')
<p>Hello Guest, <br />Welcome to SEOTools.</p>

<p>Please log in to continue</p>

<div class="login-form">
  <form method="POST">
    {{ csrf_field() }}
        <p class="login-form-field">
          <div class="login-form__label"><label name="email"><i class="fas fa-user"></i></label></div>
          <div class="login-form__input"><input type="text" name="email" placeholder="Email"/></div>
        </p>
        <p class="login-form__field">
          <div class="login-form__label"><label name="password"><i class="fas fa-key"></i></label></div>
          <div class="login-form__input"><input type="password" name="password" placeholder="Password"/></div>
        </p>
    <input type="submit" value="Log In"/>
  </form>
</div>
@endsection