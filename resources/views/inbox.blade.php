@extends('layouts.app')

@section('content')

    <br>

    <div style="text-align: center">
        @if(Session::has('clear_message'))
        <div class="alert alert-success alert-dismissable">
            <a class="panel-close close" data-dismiss="alert">×</a>  {{Session::get('clear_message')}}
        </div>
        @endif
        <h1>Your Inbox</h1>
    </div>

    <br>
    <div style="padding-left: 5%; padding-right: 5%">
        <div class="row">
            <div class="col-sm-2">
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;Chat with friends!
                <div style="border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow: auto ; max-height: 500px">
                    <?php echo $Sidebar ?>
                </div>
            </div>
            <div class="col">
                <div style="text-align: center">
                    <h2>Recent Chats</h2>

                        <form id="form" method="post">
                            {{ csrf_field() }}
                            <div class="text-right" style="padding-right: 100px">
                                <a href="/public/inbox/clear" class = "btn btn-dark"> Clear Notifications</a>
                            </div>
                        </form>

                </div>
                <div id="chat_scroll" style="height:500px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
                    <?php echo $Messages?>
                </div>
                <br>
            </div>
        </div>
    </div>





@endsection

