@extends('layouts.app')

{{--WARNING, THIS CODE IS CANCER, FORMATTING SHOULD BE DONE HERE BUT I'M A BAD PROGRAMMER--}}
{{--$OUTPUT IS PASSED IN BY ViewVideo.php@getMyVideosView() (CONTROLLER@FUNCTION) --}}
@section('content')
    <br>
    <h1>My Videos:</h1>
    <br>

    <?php echo $output ?>
@endsection


<?php
/**
 * Created by PhpStorm.
 * User: MuscleNerd
 * Date: 3/3/2019
 * Time: 4:53 PM
 */
?>
