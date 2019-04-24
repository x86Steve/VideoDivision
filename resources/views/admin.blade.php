@extends('layouts.app') 
@section('content')
<div class="container">
    <br>
    <h1>Admin tools</h1>
    <br>

    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Upload</h5>
            <p class="card-text">Add a new movie or episode to the VideoDivision collection.</p>
            <a href="admin/upload" class="btn btn-primary">Go to upload form</a>
        </div>
    </div>
</div>
@endsection