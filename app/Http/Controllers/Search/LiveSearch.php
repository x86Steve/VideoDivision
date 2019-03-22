<?php

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class LiveSearch extends SearchController
{
    function getGridView()
    {
        return view('live_search')->with('isGridView', 1);
    }

    function getTableView()
    {
        return view('live_search')->with('isGridView', 0);
    }

    function table(Request $request)
    {
        if($request -> ajax())
        {
            $output = '';
            $query = $request -> get('query');

            if ($query != '')
            {
                $data = ($this->queryAll($query));
            }
            else {
                $data = DB::table('Video')
                    ->orderBy('Title', 'asc')
                    ->get();
            }

            $total_row = $data -> count();

            if($total_row > 0 )
            {
                foreach ($data as $row)
                {
                    $video_id = $row -> Video_ID;

                    $output .= "
                    <tr>
                    <td>".$row->Title."</td>
                    <td>".$row->Year."</td>
                    <td>".$row->Subscription."</td>
                    <td>".$row->Current_Rating."</td>
                    <td><a href=\"/public/video_details?video=".$video_id."\">
        <button type=\"submit\" class=\"btn btn-dark\">Details</button>
    </a></td>                    
                    </tr>";
                }

            }
            else
            {
                $output = '<tr><td align="center" colsapn="5"> No Movies or Shows matching your search.</td></tr>';
            }

            $data = array(
                'table_data' => $output,
                'total_data' => $total_row
            );

            echo json_encode($data);
        }
    }

    function grid(Request $request)
    {
        if($request -> ajax())
        {
            $output = '';
            $query = $request -> get('query');

            if ($query != '')
            {
                $data = ($this->queryAll($query));
            }
            else {
                $data = DB::table('Video')
                    ->orderBy('Title', 'asc')
                    ->get();
            }

            $total_row = $data -> count();

            if($total_row > 0 )
            {
                $counter = 1;
                $mod = ceil(($total_row+1)/3);
                $output .= " 
        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-sm\">";

                foreach ($data as $row)
                {
                    if (($counter % $mod) === 0)
                    {
                        $output .= "</div>
                <div class=\"col-sm\"> ";

                    }
                    $video_id = $row -> Video_ID;

                    $output .= "<a href=\"/public/video_details?video=".$video_id."\">
                    <img src=\"http://videodivision.net/assets/images/thumbnails/".$video_id.".jpg\" alt=\"Check Out video details!\" width=\"195\" height=\"280\" border=\"0\">
                    </a> <br><br><br>";

                    $counter = $counter + 1;
                }
                $output .= "</div>
            </div>
        </div>";

            }
            else
            {
                $output = '<tr><td align="center" colsapn="5"> No Movies or Shows matching your search.</td></tr>';
            }

            $data = array(
                'table_data' => $output,
                'total_data' => $total_row
            );

            echo json_encode($data);
        }
    }
}
