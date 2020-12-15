@if(Session::has('error'))
    <p class="error">
        {!! Session::get('error') !!}
    </p>
@endif

{{-- For laravel validation --}}
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p class="error">
            Error - {{ $error }}
        </p>
    @endforeach
@endif

@if(Session::has('info'))
    <p class="info">
        {!! Session::get('info') !!}
    </p>
@endif

@if(Session::has('success'))
    <p class="success">
        {!! Session::get('success') !!}
    </p>
@endif