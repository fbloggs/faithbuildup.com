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
                        ->join('quizcategories', 'category', 'catname' )
                        ->where('quizresponse_id', '=', $quizresponse->id) ->select(\DB::raw('truncate(sum(response),0) as score, category, bgcolor, bordercolor'))
                        ->groupBy('category', 'bgcolor', 'bordercolor')->orderBy('score', 'DESC')
                        ->get();    

        // Shuffle results around from alphabetical (Hands, Head, Heart) to 'head, heart and hands' as per Paul's request. 
        // This is a kludge approach vs creating new table w order of category in it
        // DJK 20201219 - Don't shuffle -use sorted arrangement
        // $v->catresults = []; 
        //$v->catresults[] = $catresults[1]; 
        //$v->catresults[] = $catresults[2];
        //$v->catresults[] = $catresults[0];

        $v->catresults = $catresults; 

        $v->catsorted = $v->catresults; 
                        
        //  usort($v->catsorted, function($a, $b) { return strcmp($b->score, $a->score); });                 
        
        $v->catsorted[0]->category = 'Primary Faith Element: ' . $v->catsorted[0]->category;
        $v->catsorted[1]->category = 'Secondary Faith Element: ' . $v->catsorted[1]->category;
        $v->catsorted[2]->category = 'Third Faith Element: ' . $v->catsorted[2]->category;


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
                    ->join('quizsubcategories as qsc', 'subcategory', 'qsc.id')
              ->where('quizresponse_id', '=', $quizresponse->id) ->select(\DB::raw('sum(response) as score, count(*) as count,  subcatdescription, bgcolor, bordercolor'))
              ->groupBy('subcatdescription', 'bgcolor', 'bordercolor')->orderBy('score', 'DESC')
              ->get();    

             

        $v->subcatresults = []; 

        // Calculate the adjusted score using Paul's formula: 
        //  =  score/(subcategorycount*5) * 100 
        foreach($subcatresponses as $response) 
        {
           
            $temp['score'] = round($response->score /($response->count * 5) * 100, 2);
            $temp['subcategory'] = $response->subcatdescription;
            $temp['subcatcount'] = $response->count;
            $temp['unadjustedscore'] = $response->score; 
            $temp['bgcolor'] = $response->bgcolor; 
            $temp['bordercolor'] = $response->bordercolor; 
            
            // Add working array to results. 
            $v->subcatresults[] = $temp; 

            
        }   
        // DJK 20201219 - I added an orderBy above, so now original array is already sorted. 
        // No need to sort again   
            $v->subcatsorted = $v->subcatresults; 
           // usort($v->subcatsorted, function($a, $b) { return strcmp($b['score'], $a['score']); });
         
        return Inertia::render('Quiz/Results',  $v->toArray());

    }
}