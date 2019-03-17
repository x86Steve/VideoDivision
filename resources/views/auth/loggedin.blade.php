@extends('layouts.app')
@section('content')
    <script>
        function goHome()
        {
            window.location.replace ("/");
        }
        setTimeout(goHome, 4000);
    </script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">VideoDivision</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Thanks for logging in, {{ Auth::user()->name }} !
                    <p>
                        You will be redirected to HOME in a moment...
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/16/2019
 * Time: 7:03 PM
 */
?>