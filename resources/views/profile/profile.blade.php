


@extends('layouts.app')
<!-- TODO Redirect user when they are not logged in. -->
@section('content')
    <?php echo "<br>";?>
    <div class="container">
        <div class="row">
            <div class="col-lg-11 order-lg-1">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="" data-target="#profile" data-toggle="tab" class="nav-link {{Session::has('error_profile_settings') ? "" : "active"}}">Profile</a>
                    </li>
                    @if(!isset($CurrentUser))
                    <li class="nav-item">
                        <a href="" data-target="#messages" data-toggle="tab" class="nav-link">What's Going on?</a>
                    </li>
                    <li class="nav-item">
                        <a href="" data-target="#edit" data-toggle="tab" class="nav-link {{Session::has('error_profile_settings') ? "active" : ""}}">User Settings</a>
                    </li>
                    <li class="nav-item">
                        <a href="" data-target="#subscriptions" data-toggle="tab" class="nav-link">Subscriptions</a>
                    </li>
                    @endif
                </ul>
                <?php echo "<br>";?>
                <div class="container">
                    @if(Session::has('payment_message_success'))
                        <div class="alert alert-success alert-dismissable">
                            <a class="panel-close close" data-dismiss="alert">×</a> {{Session::get('payment_message_success')}}
                        </div>
                    @endif

                        @if(Session::has('payment_message_cancel'))
                            <div class="alert alert-danger alert-dismissable">
                                <a class="panel-close close" data-dismiss="alert">×</a> {{Session::get('payment_message_cancel')}}
                            </div>
                        @endif

                    @if(isset($error_msg))
                        <div class="alert alert-danger alert-dismissable">
                            <a class="panel-close close" data-dismiss="alert">×</a> {{$error_msg}}
                        </div>
                    @endif

                    @if (Session::has('success_msg'))
                        <div class="alert alert-success alert-dismissable">
                            <a class="panel-close close" data-dismiss="alert">×</a> {{Session::get('success_msg')}}
                        </div>
                    @endif

                    @if (Session::has('error_password'))
                        <div class="alert alert-danger alert-dismissable">
                            <a class="panel-close close" data-dismiss="alert">×</a> {{Session::get('error_password')}}
                        </div>
                    @endif
                </div>
                <div class="tab-content py-4">
                    <div class="tab-pane {{Session::has('error_profile_settings') ? "" : "active"}}" id="profile">
                        <h5 class="mb-3">User Profile</h5>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <h2><b>Welcome, {{isset($CurrentUser) ? $CurrentUser->username : Auth::user()->username}}</b></h2>
                                <img src="{{Config::get('customfilelocations.locations.avatars')}}{{ isset($CurrentUser) ? $CurrentUser->avatar : Auth::user()->avatar}}" onerror="this.src= '{{Config::get('customfilelocations.locations.avatars')}}default.png'" style="width: 200px; height: 200px; float: contour; border-radius: 50%;margin-right: 25%; margin-bottom: 5%;">
                                @if(!isset($CurrentUser))
                                    <p><strong>Update profile picture</strong></p>
                                    <!-- First line is required to upload images !-->
                                        <form enctype="multipart/form-data" action="/public/profile" method="POST">
                                            <input type="file" name="avatar">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <input type="submit" class=" btn btn-sm btn-primary">
                                        </form>
                                @endif
                                <div class="card border border-primary" style="width: 50rem; ">
                                    <span class="border border-primary">
                                        <div class="card-body center">
                                            <h5 class="card-title"><strong>Who am I:</strong></h5>
                                            <h6 class="card-subtitle mb-2 text-muted">{{isset($CurrentUser) ? $CurrentUser->jobtitle : Auth::user()->jobtitle}}</h6>
                                            <p class="card-text">{{isset($CurrentUser) ? $CurrentUser->description : Auth::user()->description}}</p>
                                        </div>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>Current Subscriptions</h5>
                                <hr>
                                @if(sizeof($Video_Titles) > 0)
                                @foreach ($Video_Titles as $Title)
                                    <a href="/public/{{helper_isMovie($Title->Video_ID) ? "watch" : "view"}}/{{$Title->Video_ID}}" class="badge badge-dark badge-pill">{{$Title->Title}}</a>
                                @endforeach
                                @else
                                    <a href="/public/live_search/grid" class="badge badge-info badge-pill">Hmm, it's a little empty here... Click here to select some shows! :)</a>
                                @endif
                                <hr>

                                <h5>Subscriber Status</h5>
                                @if(isset($CurrentUser) ? $CurrentUser->isPaid === 0 : Auth::user()->isPaid === 0)
                                    <a href="/public/payment" class="badge  badge-danger">&cross; You are currently an unsubscribed user! &cross;</a>
                                @elseif(isset($CurrentUser) ? $CurrentUser->isPaid === 1 : Auth::user()->isPaid === 1)
                                    <a href="/public/payment" class="badge badge-success">Everything's lookin' good! Enjoy! &checkmark;</a>
                                @else
                                    <a href="/public/payment" class="badge badge-info">Please consider subscribing!</a>
                                @endif
                                <hr>

                                @if(isset($CurrentUser))
                                <div class="col-md-6">
                                    <h6>Instant Messaging / Friend Zone:</h6>
                                    <div class="btn-group">
                                        <a href="/public/chat?user={{ $CurrentUser->id }}" class="btn btn-primary btn-sm ">Message this user!</a>
                                        <a href="/public/chat/addremove/{{$CurrentUser->id}}" class="btn {{helper_isFriend($CurrentUser->id) ? "btn-danger" : "btn-success"}} btn-sm">{{helper_isFriend($CurrentUser->id) ? "Unfriend this user!" : "Friend this user!"}}</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <h5 class="mt-2"><span class="float-right"></span><i class="fas fa-clock"></i> Recent Activity</h5>
                                <table class="table table-sm table-hover table-striped">
                                    <tbody>
                                    @if(sizeof($recent_activities) <= 0)
                                        <tr>
                                            <td>
                                            No activities yet!
                                            </td>
                                        </tr>
                                    @else
                                    @foreach($recent_activities as $activity)
                                        <tr>
                                            <td>
                                                <strong>{{isset($CurrentUser) ? $CurrentUser->name : Auth::user()->name}}</strong>  - {{$activity->entry}} -  <strong>{{ Carbon\Carbon::parse($activity->created_at)->diffForHumans()}}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <h5 class="mt-2"><span class="float-right"></span><i class="fas fa-comments"></i> User Wall</h5>
                                <table class="table table-sm table-hover table-striped">
                                    <tbody>
                                    @if(sizeof($Comments) <= 0)
                                        <td>
                                            No comments yet!
                                        </td>
                                    @else
                                        @foreach($Comments as $comment)
                                            <tr>
                                                <td>
                                                    <strong><i class="{{helper_isAdmin($comment->commenter_username) ? "fas fa-crown" : "" }}"> </i><a  style="color: {{helper_isAdmin($comment->commenter_username) ? "red" : "blue"}}" href="/public/profile/{{ $comment->userwall_username == $comment->commenter_username ? $comment->userwall_username : $comment->commenter_username}}/"> {{$comment->userwall_username == $comment->commenter_username ? $comment->userwall_username : $comment->commenter_username}}</a></strong>
                                                    : {{$comment->comment}} - <strong>{{ Carbon\Carbon::parse($comment->time)->diffForHumans()}}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <form role="form" action="/public/profile/{{isset($CurrentUser) ? $CurrentUser->username : Auth::user()->username}}/comment" method="POST">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <label for="comment">Comment:</label>
                                    <textarea class="form-control {{ $errors->has('comment') ? ' is-invalid' : '' }}" rows="3" name="comment" >{{Request::old('comment') != null ? Request::old('comment') : "" }}</textarea>
                                    @if ($errors->has('comment'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </span>
                                    @endif
                                    <input type="submit" class="btn btn-primary" value="Submit comment!">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="messages">
                        <div class="alert alert-info alert-dismissable">
                            <a class="panel-close close" data-dismiss="alert">×</a> This is an <strong>.alert</strong>. Use this to show important messages to the user.
                        </div>
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <td>
                                    <span class="float-right font-weight-bold">3 hrs ago</span> Here is your a link to the latest summary report from the..
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="float-right font-weight-bold">Yesterday</span> There has been a request on your account since that was..
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="float-right font-weight-bold">9/10</span> Porttitor vitae ultrices quis, dapibus id dolor. Morbi venenatis lacinia rhoncus.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="float-right font-weight-bold">9/4</span> Vestibulum tincidunt ullamcorper eros eget luctus.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="float-right font-weight-bold">9/4</span> Maxamillion ais the fix for tibulum tincidunt ullamcorper eros.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id = "subscriptions">
                        NOTHIN HERE YET! Probably a friend zone.
                    </div>
                    <div class="tab-pane {{Session::has('error_profile_settings') ? "active" : ""}}" id="edit" >
                        <form role="form" action="/public/profile/edit" method="POST">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Username</label>
                                <div class="col-lg-9">
                                    <input class="form-control " type="text" disabled value="{{Auth::user()->username }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">First name</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="{{Auth::user()->name }}" name="firstname">
                                    @if ($errors->has('firstname'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Last name</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="{{Auth::user()->lastname}}" name="lastname">
                                    @if ($errors->has('lastname'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                <div class="col-lg-9">
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" disabled value="{{Auth::user()->email}}" name ="email">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Job Title</label>
                                <div class="col-lg-9">
                                    <input class="form-control{{ $errors->has('jobtitle') ? ' is-invalid' : '' }}" type="text" value="{{Request::old('jobtitle') != null ? Request::old('jobtitle') : Auth::user()->jobtitle }}" name="jobtitle">
                                    @if ($errors->has('jobtitle'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('jobtitle') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Description of Self</label>
                                <div class="col-lg-9">
                                    <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" type="text" value="{{Request::old('description') != null ? Request::old('description') : Auth::user()->description }}" name="description">
                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Address</label>
                                <div class="col-lg-9">
                                    <input class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}"placeholder="Street" type="text" value="{{Request::old('street') != null ? Request::old('street') : Auth::user()->street }}" name="street">
                                    @if ($errors->has('street'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-6">
                                    <input class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" placeholder="City" type="text" value="{{Request::old('city') != null ? Request::old('city') : Auth::user()->city }}" name="city">
                                    @if ($errors->has('city'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" placeholder="State" type="text" value="{{Request::old('state') != null ? Request::old('state') : Auth::user()->state }}" name="state">
                                    @if ($errors->has('state'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Time Zone</label>
                                <div class="col-lg-9">
                                    <select id="user_time_zone" class="form-control" size="0">
                                        <option value="Hawaii">(GMT-10:00) Hawaii</option>
                                        <option value="Alaska">(GMT-09:00) Alaska</option>
                                        <option value="Pacific Time (US &amp; Canada)">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
                                        <option value="Arizona">(GMT-07:00) Arizona</option>
                                        <option value="Mountain Time (US &amp; Canada)">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
                                        <option value="Central Time (US &amp; Canada)" selected="selected">(GMT-06:00) Central Time (US &amp; Canada)</option>
                                        <option value="Eastern Time (US &amp; Canada)">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
                                        <option value="Indiana (East)">(GMT-05:00) Indiana (East)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Current Password</label>
                                <div class="col-lg-9">
                                    <input class="form-control{{ $errors->has('currentpassword') ? ' is-invalid' : '' }}" type="password" value="" name="currentpassword">
                                    @if ($errors->has('currentpassword'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('currentpassword') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">New Password</label>
                                <div class="col-lg-9">
                                    <input class="form-control{{ $errors->has('newpassword') ? ' is-invalid' : '' }}" type="password" value="" name="newpassword">
                                    @if ($errors->has('newpassword'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('newpassword') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Confirm password</label>
                                <div class="col-lg-9">
                                    <input class="form-control{{ $errors->has('newpassword_confirmed') ? ' is-invalid' : '' }}" type="password" value="" name="newpassword_confirmed">
                                    @if ($errors->has('newpassword_confirmed'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('newpassword_confirmed') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-9">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="reset" class="btn btn-secondary" value="Cancel">
                                    <input type="submit" class="btn btn-primary" value="Save Changes">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
