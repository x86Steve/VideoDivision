@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Home</h1>
        <div class="row">
            <div class="col-lg-11 order-lg-1">
                @include('inc.showcase')
            </div>
        </div>
    </div>
@endsection