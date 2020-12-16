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

class QuizzesController extends Controller
{
    public function show(Request $request, $quizid)
    {
        $user = Auth::user();


        $v = new Viewdata();
       
        $v->userid  = $user->id; 
        $v->quiz = Quiz::findorFail($quizid) ;

        $v->questions = Quizdetail::where('quiz_id', $quizid)->get();    
         
        return Inertia::render('Quiz/Show',  $v->toArray());
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

    public function resultspie($quizid)
    { 
        // Get the Quiz data and return to our Chart container page: 
        $v = new Viewdata(); 
        
        $user = Auth::user();
        $v->userid  = $user->id; 
     
        $v->quiz = Quiz::findorFail($quizid) ;

        $quizresponse = Quizresponse::where('user_id', $user->id)->where('quiz_id', $quizid)->first(); 
  
        // Get sum of all responses by question category. 
        $catresults = \DB::table('quizresponsedetails as rd')
      
                        ->join('quizdetails as q', function ($join) use ($quizid) {
                            $join->on('rd.quizquestion_number', '=', 'q.questionnumber')
                                ->where('q.quiz_id',  '=', $quizid );
                        })
                        ->where('quizresponse_id', '=', $quizresponse->id) ->select(\DB::raw('sum(response) as score, category'))
                        ->groupBy('category')
                        ->get();    

        // Shuffle results around from alphabetical (Hands, Head, Heart) to 'head, heart and hands' as per Paul's request. 
        // This is a kludge approach vs creating new table w order of category in it
        $v->catresults = []; 
        $v->catresults[] = $catresults[1]; 
        $v->catresults[] = $catresults[2];
        $v->catresults[] = $catresults[0];
        
                        
                         

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

                  // ALso - each subcategory has a 2 digit number in front to provide a sort order. 
                // remove that before we send data to client: 
           
            $temp['score'] = $response->score /($subcatcount * 5) * 100;
            $temp['subcategory'] = substr($response->subcategory,2);
            $temp['subcatcount'] = $subcatcount;
            $temp['unadjustedscore'] = $response->score; 
            
            // Add working array to results. 
            $v->subcatresults[] = $temp; 
        }      
         
        return Inertia::render('Quiz/Results',  $v->toArray());

    }
}