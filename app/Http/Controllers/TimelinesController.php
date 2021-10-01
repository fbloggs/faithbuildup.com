<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Quizdetail;
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

        $v->userid  = $user->id;
        $v->user = new \stdClass();
        $v->user->family = $user->family;
        $v->user->profession = $user->profession;
        $v->user->hobbies = $user->hobbies;
        $v->user->scripture = $user->scripture;

        $v->faithevents = $this->getFaithevents();

        return Inertia::render('Timeline/Show',  $v->toArray());
    }

    public function update(Request $request)
    {
        $answers = $request->input('answers');

        $responseKey = [
            'user_id'   => Auth::user()->id,


        ];
        $responseRow = [

            'completed_at' =>date ("Y-m-d H:i:s")
        ];

        // Create if not there. Otherwise, Update it.
        $response = Timelineresponse::updateOrCreate($responseKey, $responseRow);

        /**
         *  Write a response row for each question and answer.
         *  Child to Quizresponse table.
         */

        foreach ($answers as $answer) {


            $responseDetailKey = [
                'timelineresponse_id' => $response->id,
                'index' => $answer['index'] ,

            ];

            // Only process rows with a valid, non-zero response:
            if($answer['response'] || $answer['response'] > 0 ) {
                $responseDetailRow = [
                    'description' => $answer['description'],
                    'response' => $answer['response'],
                    'faithstrength' => $answer['faithstrength'],
                ];

                $responseDetail = Timelineresponsedetail::updateOrCreate($responseDetailKey, $responseDetailRow);
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

    public function resultschart()
    {
        // Get the Quiz data and return to our Chart container page:
        $v = new Viewdata();

        $user = Auth::user();
        $v->userid  = $user->id;

        $v->user = new \stdClass();
        $v->user->name = $user->name;
        $v->user->family = $user->family;
        $v->user->profession = $user->profession;
        $v->user->hobbies = $user->hobbies;
        $v->user->scripture = $user->scripture;


        // >>> Condition quiz menu option for faith timeline too. Should really be abstracted
        // >>> into a service or something.
        // >>> hard-code quiz for now. In future, may have more than 1.
        $quizid = 1;

       // $quizresponse  = Quizresponse::find(['quiz_id' => $quizid, 'user_id' => $user->id] ) ;
       $quizresponse  = \DB::table('quizresponses')->where('quiz_id', $quizid)->where('user_id', $user->id)->exists();

       if(!($quizresponse)) {
           $v->quizdone = false;
       }
       else {
           $v->quizdone = true;
       }

        $timelineresponse = Timelineresponse::where('user_id', $user->id)->first();

        // Get results:
        $timelinedetails = Timelineresponsedetail::where('timelineresponse_id', $timelineresponse->id)->get();

        $chartLifeEvents = Array();
        $chartFaithStrengths = Array();
        $chartLabels = Array();
        foreach ($timelinedetails  as $timelinedetail) {
            $chartLifeEvents[] = $timelinedetail->response;
            $chartFaithStrengths[] = $timelinedetail->faithstrength;
            $chartLabels[] = $timelinedetail->description;
        }

        $v->chartLifeEvents = $chartLifeEvents;
        $v->chartFaithStrengths = $chartFaithStrengths;
        $v->chartLabels = $chartLabels;


        return Inertia::render('Timeline/Results',  $v->toArray());

    }

    private function getFaithevents() {
        $eventCount = 0;
        $user = Auth::user();
        // Check if we already have existing data for this user. If so, reuse.
        $timelineresponse = Timelineresponse::where('user_id', $user->id)->first();

        if (!$timelineresponse) {

        $faithevents = Array();
        $faithevents = $this->loadEvents($eventCount, $faithevents);

    }
    else {

        $faithevents = Timelineresponsedetail::where('timelineresponse_id', $timelineresponse->id)->get()->toArray();
        $eventCount = count($faithevents);
        $faithevents  = $this->loadEvents($eventCount, $faithevents);

    }

        return $faithevents;
    }

    /**
     * Add any blank missing events to array, based on current array size.
     * So, if 0 (no events), load 20 (max)
     * If more than zero, fill remainder with blank entries
     *
     * @param [int] $eventCount
     * @return void
     */
    private function loadEvents( $eventCount, $faithevents)
    {



        while ($eventCount < 20) {
        $faithevents[$eventCount] =   array('index'=> $eventCount, 'description'=> 'Life Event '. $eventCount, 'response' => '0');
        $eventCount++;
       }

       return $faithevents; 
    }
}
