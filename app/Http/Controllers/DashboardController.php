<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Quizdetail; 
use App\Models\Quizresponse; 
use App\Models\Quizresponsedetail; 
 
use App\Http\Helpers\Viewdata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // >>> hard-code quiz for now. In future, may have more than 1. 
        $quizid = 1; 
        $v = new Viewdata();
       
        $v->userid  = $user->id; 
       $quizresponse  = Quizresponse::findorFail($quizid) ;

       if(is_Null($quizresponse)) { 
           $v->quizdone = false; 
       }
       else {
           $v->quizdone = true;
       }
         
        return Inertia::render('Dashboard',  $v->toArray());
    }

   
    

    public function update(Request $request, $quizid)
    {
        

        $answers = $request->input('answers'); 
       
        $responseKey = [
            'user_id'   => Auth::user()->id,
            'quiz_id'  => $quizid, 
            
        ];
        $responseRow = [
             
            'completed_at' =>date ("Y-m-d H:i:s")
        ];

        // Create if not there. Otherwise, Update it. 
        $response = Quizresponse::updateOrCreate($responseKey, $responseRow); 

        /**
         *  Write a response row for each question and answer. 
         *  Child to Quizresponse table. 
         */  

        foreach ($answers as $answer) {


            $responseDetailKey = [
                'quizresponse_id' => $response->id, 
                'quizquestion_number' => $answer['questionnumber'] ,
               
            ];


            $responseDetailRow = [
                
                'response' => $answer['answer'],
            ];


            $responseDetail = Quizresponsedetail::updateOrCreate($responseDetailKey, $responseDetailRow);    
            

        }
        
        return Redirect::route('quizzes.results', [$quizid])->with('success', 'Congratulations. You Completed The Quiz.');
    }

    public function results($quizid)
    { 
        // Get the Quiz data and return to our Chart container page: 
        $v = new Viewdata(); 
        
        $user = Auth::user();
        $v->userid  = $user->id; 
     
        $v->quiz = Quiz::findorFail($quizid) ;

        $quizresponse = Quizresponse::where('user_id', $user->id)->where('quiz_id', $quizid)->first(); 
  
        // Get sum of all responses by question category. 
        $v->catresults = \DB::table('quizresponsedetails as rd')
      
                        ->join('quizdetails as q', function ($join) use ($quizid) {
                            $join->on('rd.quizquestion_number', '=', 'q.questionnumber')
                                ->where('q.quiz_id',  '=', $quizid );
                        })
                        ->where('quizresponse_id', '=', $quizresponse->id) ->select(\DB::raw('sum(response) as score, category'))
                        ->groupBy('category')
                        ->get();    
                         

        // Get count of questions by subcategory:                     
        $subcats  = \DB::table('quizdetails')
                        ->where('quiz_id',  '=', $quizid )
                        ->select(\DB::raw('count(*) as count, subcategory'))
                        ->groupBy('subcategory')
                        ->get();    

        // Get sum of all results by question subcategory:                  
        $subcatresponses = \DB::table('quizresponsedetails as rd')
              ->join('quizdetails as q', function ($join) use ($quizid) {
                    $join->on('rd.quizquestion_number', '=', 'q.questionnumber')
                         ->where('q.quiz_id',  '=', $quizid );
                    })
              ->where('quizresponse_id', '=', $quizresponse->id) ->select(\DB::raw('sum(response) as score, subcategory'))
              ->groupBy('subcategory')
              ->get();    

        $v->subcatresults = []; 

        // Calculate the adjusted score using Paul's formula: 
        //  =  score/(subcategorycount*5) * 100 
        foreach($subcatresponses as $response) 
        {
            $subcatcount = 0; 
            $temp = []; 

                // Find the count for matching subcategory. 
                // Probably a more efficient way to do this - some sort of array lookup - 
                // but this is quick and dirty . 
                foreach($subcats as $subcat ) { 
                    if ($subcat->subcategory == $response->subcategory) {
                        $subcatcount  = $subcat->count; 
                        break;
                    }
                }
            $temp['score'] = $response->score /($subcatcount * 5) * 100;
            $temp['subcategory'] = $response->subcategory; 
            $temp['subcatcount'] = $subcatcount;
            $temp['unadjustedscore'] = $response->score; 
            
            // Add working array to results. 
            $v->subcatresults[] = $temp; 
        }      
         
        return Inertia::render('Quiz/Results',  $v->toArray());

    }
}