@extends ('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-11 order-lg-1">
                <form action="contact" method="POST">

                    <div class="form-group">

                        {{csrf_field()}}
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" aria-describedby="emailHelp" required placeholder="Enter Your Name" name="name">
                        <small id="emailHelp" class="form-text text-muted">We will sell your email to anyone who wants it.</small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" required placeholder="Enter your Email"name="email">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea name="message" class="form-control" id="message" required placeholder="Please enter your message." cols="30" rows="10">
        </textarea>


                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/3/2019
 * Time: 4:53 PM
 */
?>