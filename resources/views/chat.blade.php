@extends('layouts.app')

@section('content')

    <br>
    <br>
    <h1>Your chat with: <img src="other_img" class="right" width="95" height="95" border="0" hspace="20"><?php echo $other_info->username ?></h1>

    <br>
    <?php echo $chat ?>
    <form id="form" method="post">
     {{ csrf_field() }}

     <input type="hidden" id="Receiver_ID" name="Receiver_ID" value="<?php echo $receiver_id?>">
     <textarea class="form-control" id="Message" name="Message" required placeholder="Please enter your message." cols="20" rows="5"></textarea>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <button type="submit" class="btn btn-dark btn-sm">Send Message!</button>
 </form>


@endsection

<script>
window.onload=function () {
    var objDiv = document.getElementById("chat_scroll");
    objDiv.scrollTop = objDiv.scrollHeight;
    }
</script>





