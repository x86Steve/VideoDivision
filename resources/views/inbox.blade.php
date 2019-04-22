@extends('layouts.app')

@section('content')

    <br>

    <div style="text-align: center">
        <h1>Your Inbox</h1>
    </div>

    <br>
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
            </div>
            <div id="chat_scroll" style="height:500px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
                <?php echo $Messages?>
            </div>
            <br>
        </div>
    </div>





@endsection

