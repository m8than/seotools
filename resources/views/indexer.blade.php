@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1>Link Indexer</h1>

<div style="text-align:center;">
    <div class="desc">
        <p>Your Unique Links Processed: {{ $processed_links }}</p>
        <p>Your Unique Total Links: {{ $total_links }}</p>
        <p>Your Unique Total Links In Queue: {{ $total_links_in_queue }}</p>
    </div>
</div>
<div class="form">
  <form method="POST">
        {{ csrf_field() }}
        <p class="form-field">
            <div class="form__label"><label name="links"><i class="fas fa-link"></i></label></div>
            <div class="form__input"><textarea type="text" name="links" placeholder="Links here separated by new line"></textarea></div>
        </p>
    <input type="submit" value="Add to indexer"/>
  </form>
</div>
@endsection