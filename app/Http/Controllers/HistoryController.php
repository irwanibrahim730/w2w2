<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\History;
use App\User;

class HistoryController extends Controller

{


    public function all (Request $request)
    
    {
         
        $history = History::all();
        $historyarray = array();

        foreach ($history->sortByDesc('created_at') as $histories)
        {
            $user_id = $histories->user_id;
            $user = User::where('user_id',$user_id)->get();

            foreach ($user as $users){

            $temparray = [
                'id' => $histories->id,
                'user_id' => $histories->user_id,
                'user_fname' => $users->user_fname,
                'user_lname' => $users->user_lname,
                'type' => $histories->type,
                'name' => $histories->name,
            ];

            array_push($historyarray,$temparray);

            }


        }

        return response()->json($historyarray);

    }



    public function listid (Request $request)
    {

        $user_id = $request->input('user_id');

        $history = History::where('user_id',$user_id)->get();

        $historyarray = array();

        foreach ($history->sortByDesc('created_at') as $histories)
        {
            $user_id = $histories->user_id;
            $user = User::where('user_id',$user_id)->get();

            foreach ($user as $users){

            $tempremarks = "-";
            if($histories->remarks != null){
                $tempremarks = $histories->remarks;
            }

            $temparray = [
                'id' => $histories->id,
                'user_id' => $histories->user_id,
                'user_fname' => $users->user_fname,
                'user_lname' => $users->user_lname,
                'type' => $histories->type,
                'name' => $histories->name,
                'remarks' => $tempremarks,
            ];

            array_push($historyarray,$temparray);

            }


        }

        return response()->json($historyarray);



    }


    public function delete(Request $request)
    {
 
       $id = $request->input('id');

       $history = History::where('id',$id)->first();
       $history->delete();

       return response()->json('history deleted');

    }


   


}