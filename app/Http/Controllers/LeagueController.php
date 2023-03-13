<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\League;
use App\Models\User;
use App\Models\LeaguePlayerMatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class LeagueController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $currentUser = auth()->user();
        $code = $this->generateRandomString();
        try{
            $league = League::create([
                'name' => $request->name,
                'owner_id' => $currentUser->id,
                'code'=> $code
            ]);
           $request->merge(["league_id" => $league->id]);
            $this->joinLeague($request);

            return response()->json([
                'status' => true,
                'message' => 'League created and joined successfully',
                'code' => $league->code
            ], 200);

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function joinLeague(Request $request): JsonResponse
    {
        $currentUser = auth()->user();
        $request->league_id ??
            $league = League::where("code", $request->code)->first();
        try{
            LeaguePlayerMatch::create([
                'league_id'=> $request->league_id ?? $league->id,
                'user_id'=>$currentUser->id
            ]);
            User::where("id", $currentUser->id )->update([
                "current_league_id" => $league->id
            ]);
            return response()->json([
                'status' => true,
                'message' => 'League joined successfully',
            ], 200);
        }catch(Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getLeagues(): JsonResponse
    {
        try{
        $leaguesQuery  = League::all();
            $leagues = [];
            foreach($leaguesQuery as $key => $league){
                $membersOfThisLeague = $league->Members;
                $players=[];
                foreach($membersOfThisLeague as $index => $member){
                    $players[$index] = [
                        "id" => $member->id,
                        "name" =>$member->name,
                        "email" =>$member->email,
                    ];
                }
                $user =$league->Owner;
                $owner = [
                    "id" => $user->id,
                    "name" => $user->name
                ];
                $league = [
                   "id" => $league->id,
                   "name"=> $league->name,
                   "owner" => $owner,
                   "members"=> $players
                ];
                $leagues[$key] = $league;
            }
            return response()->json([
                'status' => true,
                'message' => $leagues,
            ], 200);

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param $length
     * @return string
     * @throws Exception
     */
    function generateRandomString($length = 5): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
