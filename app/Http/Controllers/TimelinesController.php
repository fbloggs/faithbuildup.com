<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Quizdetail;
use App\Models\Quizresponse;
use App\Models\Timelineresponse;
use App\Models\Timelineresponsedetail;
use App\Models\User;

use App\Http\Helpers\Viewdata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TimelinesController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();

        $v = new Viewdata();
        // Condition quiz menu option for faith timeline too.
        $v->quizdone = Quizresponse::quizExists($user->id);

        $v->user = $user;
        $v->userid = $user->id;
        //  $v->user = new \stdClass();
        //  $v->user->family = $user->family;
        //  $v->user->profession = $user->profession;
        //   $v->user->hobbies = $user->hobbies;
        //   $v->user->scripture = $user->scripture;

        $v->faithevents = $this->getFaithevents();
        $v->answes = $this->getAnswers();   // Get answers for the current user, if any.

        return Inertia::render('Timeline/Show', $v->toArray());
    }

    public function showuser($user_id)
    {

        $user = User::get($user_id)->first();

    }

    public function update(Request $request)
    {
        $answers = $request->input('answers');

        $responseKey = [
            'user_id' => Auth::user()->id,
        ];
        $responseRow = [

            'completed_at' => date("Y-m-d H:i:s")
        ];

        // Create if not there. Otherwise, Update it.
        $response = Timelineresponse::updateOrCreate($responseKey, $responseRow);

        /**
         *  First, delete all existing responses so we start afresh (in case user decides to omit one)
         *  Write a response row for each question and answer.
         *  Child to Quizresponse table.
         */
        $deletedRows = Timelineresponsedetail::where('timelineresponse_id', $response->id)->delete();

        $index = 1;
        foreach ($answers as $answer) {
            $responseDetailKey = [
                'timelineresponse_id' => $response->id,
                'index' => $index,

            ];



            // Only process rows with a non-null or undefined value for both response and faithstrength
            if (($answer['response'] >= -100 && $answer['response'] <= 100) && ($answer['faithstrength'] >= -100 && $answer['faithstrength'] <= 100)) {
                $responseDetailRow = [
                    'description' => $answer['description'],
                    'response' => $answer['response'],
                    'faithstrength' => $answer['faithstrength'],
                ];

                $responseDetail = Timelineresponsedetail::updateOrCreate($responseDetailKey, $responseDetailRow);
                $index++;
            }
        }

        // Update the user now:
        $id = Auth::user()->id;
        $user = User::find($id);

        $user->family = $request->input('family');
        $user->profession = $request->input('profession');
        $user->hobbies = $request->input('hobbies');
        $user->scripture = $request->input('scripture');

        $user->save();

        return Redirect::route('timelines.results')->with('success', 'Congratulations. You Completed The Faith Timeline.');
    }

    public function anotheruser(Request $request, $userid)
    {
        // If the logged in user has a current_team_id of 1, it means they are an admin and can access other people's results.
        $user = Auth::user();
        if ($user->current_team_id == 1) {
            $anotherUser = User::findorFail($userid);
            return $this->drawChart($user, $anotherUser);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function resultschart(Request $request)
    {
        // Get the Quiz data and return to our Chart container page:
        $user = Auth::user();
        return $this->drawChart($user, $user);

    }

    private function drawChart($user, $anotherUser)
    {
        $v = new Viewdata();
        $v->user = $user;
        $v->userid = $user->id;

        $v->anotherUser = new \stdClass();
        $v->anotherUser->name = $anotherUser->name;
        $v->anotherUser->family = $anotherUser->family;
        $v->anotherUser->profession = $anotherUser->profession;
        $v->anotherUser->hobbies = $anotherUser->hobbies;
        $v->anotherUser->scripture = $anotherUser->scripture;


        // Condition quiz menu option for faith timeline too.
        $v->quizdone = Quizresponse::quizExists($user->id); // For current logged in user.

        $timelineresponse = Timelineresponse::where('user_id', $anotherUser->id)->first();

        // Get results:
        $timelinedetails = Timelineresponsedetail::where('timelineresponse_id', $timelineresponse->id)->get();

        $chartLifeEvents = array();
        $chartFaithStrengths = array();
        $chartLabels = array();
        foreach ($timelinedetails as $timelinedetail) {
            $chartLifeEvents[] = $timelinedetail->response;
            $chartFaithStrengths[] = $timelinedetail->faithstrength;
            $chartLabels[] = $timelinedetail->description;
        }

        $v->chartLifeEvents = $chartLifeEvents;
        $v->chartFaithStrengths = $chartFaithStrengths;
        $v->chartLabels = $chartLabels;

        return Inertia::render('Timeline/Results', $v->toArray());

    }

    private function getFaithevents()
    {
       
        $faithevents = array();
        $user = Auth::user();
        // Check if we already have existing data for this user. If so, reuse.
        $timelineresponse = Timelineresponse::where('user_id', $user->id)->first();

        if (!$timelineresponse) {

           
            $faithevents = $this->loadEvents(0, $faithevents);

        } else {

            $faithevents = Timelineresponsedetail::where('timelineresponse_id', $timelineresponse->id)->get()->toArray();
            
            $faithevents = $this->loadEvents(count($faithevents), $faithevents);


        }

        return $faithevents;
    }

    private function getAnswers()
    {
        $user = Auth::user();
        // Check if we already have existing data for this user. If so, reuse.
        $timelineresponse = Timelineresponse::where('user_id', $user->id)->first();

        if (!$timelineresponse) {
            return array();
        } else {
            $answers = Timelineresponsedetail::where('timelineresponse_id', $timelineresponse->id)->get()->toArray();
            return $answers;
        }
    }           

    /**
     * Add any blank missing events to array, based on current array size.
     * So, if 0 (no events), load 20 (max)
     * If more than zero, fill remainder with blank entries
     *
     * @param [int] $eventCount
     * @return [Array] $faithevents
     * 
     */
    private function loadEvents($eventCount, $faithevents)
    {

        while ($eventCount < 20) {
            $eventCount++;
            $faithevents[] = array('index' => $eventCount, 'description' => 'Life Event ' . $eventCount, 'response' => null, 'faithstrength' => null);

        }

        return $faithevents;
    }
}
