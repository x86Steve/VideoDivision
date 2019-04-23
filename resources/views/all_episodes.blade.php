@extends('layouts.app')

@section('content')

    <h1 style="font-size:60px;"><span class="bigger"> {{($series_title)}}</span></h1>

    <div class="panel-body" align="center">
        <table class="table table-bordered" style="width:500px">
            <tr>
                <th>Episode Title</th>
                <th>Watch Now</th>
            </tr>
            @if($series->count())
                @foreach($series as $episode)
                <tr>
                    <td>
                        <strong>S:{{$episode->Season_Number}}E:{{$episode->Episode_Number}}</strong> {{$episode->Episode_Title}}
                    </td>
                    <th width="150px">
                        <a href="/public/watch/{{$episode->Show_ID}}/season/{{$episode->Season_Number}}/episode/{{$episode->Episode_Number}}" class="btn btn-dark">Watch!</a>
                    </th>
                </tr>
                @endforeach
            @endif
        </table>
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
