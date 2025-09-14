<?php
/**
 * CRUD for password accounts. List, Create, Update, Delete.
 */
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quizresponse;
use App\Models\Timelineresponse;

use App\Http\Helpers\Viewdata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $v = new Viewdata();
        $v->userid  = $user->id;

       // $v->accounts = \DB::table('accounts')->where('userid', $user->id)->paginate(15);
       $v->filters = request()->only('search');
       $v->users  = User::orderByEmail()
                ->filter(request()->only('search'))
                ->paginate();

        foreach ($v->users as &$user)
        {
            $user->quizDone = Quizresponse::quizExists($user->id);
            $user->timeLineDone = Timelineresponse::timeLineExists($user->id);
        }



        return Inertia::render('Users/Index',  $v->toArray());
    }

    public function showquiz($userid) {
        // >>> Stub to show quiz.

    }

   }
