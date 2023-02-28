<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\LeaguePlayerMatch;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        try{
            $allUsers = User::all();
            $allMatches = LeaguePlayerMatch::select(["user_id", "league_id"])->groupBy(["user_id", "league_id"])->get();

            $users = [];
            foreach($allUsers as $key => $user){
              
                $userMatchs = $allMatches->where("user_id", $user->id);
                
                $leagues = [];
                foreach($userMatchs as $index => $match){
                    $leagues[$index] = [
                        "id" => $match->league->id,
                        "name" => $match->league->name,
                        "code" => $match->league->code,
                    ];
                }
                $currentLeague = [
                    "id" => $user->CurrentLeague->id,
                    "name" => $user->CurrentLeague->name,
                    "code" => $user->CurrentLeague->code,
                  ];
                $user = [
                    "id" => $user->id,
                    "name" => $user->name,
                    "current_league" => $currentLeague,
                    "leagues"=>$leagues
                ];
                $users[$key] = $user;
            }
            return response()->json([
                'status' => true,
                'message' => $users,
            ], 200);

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
