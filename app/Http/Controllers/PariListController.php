<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bet;
use Illuminate\Support\Facades\Auth;

class PariListController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pari = $this->selectPari($user);

        return view('user.pari', ['auth_status' => Auth::check(), 'pari' => $pari]);
    }

    public function selectPari($user)
    {
        return Bet::select('id','sum_bet', 'coefficient', 'status', 'event_name', 'event_team', 'event_result')->where('user_id_better', $user->id)->orderBy('id', 'DESC')->get();
    }
}
