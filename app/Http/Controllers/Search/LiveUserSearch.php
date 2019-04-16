<?php

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class LiveUserSearch extends SearchController
{
    //Used to route to view live_search
    function getGridView()
    {
        return view('live_user_search')->with('isGridView', 1);
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
                $data = DB::table('users')
                    ->where('Name', 'like', "$query")
                    ->orderBy('Name', 'asc') //FIX CHANGE THIS LATER TO USERNAME
                    ->get();
            }
            else {
                $data = DB::table('users')
                    ->orderBy('Name', 'asc') //FIX CHANGE THIS LATER TO USERNAME
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
        $output ="";
        if($request -> ajax())
        {
            $query = $request -> get('query');

            if ($query != '')
            {
                $data = DB::table('users')
                    ->where('users.username', 'like', "%$query%")
                    ->select('users.*')
                    ->get();
            }
            else {
                $data = DB::table('users')->select('users.*')->get();
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
                    $user_img = asset('avatars') . '//' . $row->avatar;

                    $output .= "<div class=\"col-md-".$bootstrapColWidth."\">";
                    $output .= "<a href=\"/public/profile/$row->username\">
                    <img src=$user_img alt=\"Visit User's Profile!\" width=\"195\" height=\"280\" border=\"0\">
                    </a> <h5>".substr($row->username, 0, 22). "</h5>
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
                $output = '<tr><td align="center" colsapn="5"> No Users matching your search.</td></tr>';
            }

            $data = array(
                'table_data' => $output,
                'total_data' => $total_row
            );

            echo json_encode($data);
        }
    }
}
