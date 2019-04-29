<?php

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use DB;

class LiveSearch extends SearchController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //Used to route to view live_search
    function getGridView()
    {
        return view('live_search')->with('isGridView', 1);
    }

    //used to route to view live_search
    function getTableView()
    {
        return view('live_search')->with('isGridView', 0);
    }

    //Formats live search in table format
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

    //formats live search in grid format
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
                $counter = 0;
                $numOfCols = 3;
                $bootstrapColWidth = floor(12 / $numOfCols * 1.22);

                $output .= " <div class=\"container-fluid\">
            <div class=\"row\">";


                foreach ($data as $row)
                {
                    $video_id = $row -> Video_ID;

                    $output .= "<div class=\"col-md-".$bootstrapColWidth."\">";
                    $output .= "<a href=\"/public/video_details?video=".$video_id."\">
                    <img src=\"http://videodivision.net/assets/images/thumbnails/".$video_id.".jpg\" alt=\"Check Out video details!\" width=\"195\" height=\"280\" border=\"0\">
                    </a> <h5>".substr($row->Title, 0, 22)."</h5>
                    <br><br></div>";

                    $counter = $counter + 1;

                    if (($counter % $numOfCols) === 0)
                    {
                        $output .= "</div><div class=\"row\">";

                    }
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
