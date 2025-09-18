<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Quizdetail;
use App\Models\Quizresponse;
use App\Models\Quizresponsedetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizzesController extends Controller
{
    public function show(Request $request, $quizid)
    {
        $user = Auth::user();

        return Inertia::render('Quiz/Show', [
            'userid' => $user->id,
            'quiz' => Quiz::findOrFail($quizid),
            'questions' => $this->getQuestionsWithAnswers($quizid, $user->id),
            'answers' => $this->getAnswers($quizid, $user->id),
        ]);
    }

    /**
     * Get questions with existing answers merged in
     */
    private function getQuestionsWithAnswers($quizId, $userId)
    {
        $questions = Quizdetail::where('quiz_id', $quizId)->get();
        $existingAnswers = $this->getAnswersAsKeyValue($quizId, $userId);

        // Merge existing answers into questions
        foreach ($questions as $question) {
            $question->answer = $existingAnswers[$question->questionnumber] ?? '';
        }

        return $questions;
    }

    /**
     * Get existing answers for the user
     */
    private function getAnswers($quizId, $userId)
    {
        $quizResponse = Quizresponse::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->first();

        if (!$quizResponse) {
            return [];
        }

        return Quizresponsedetail::where('quizresponse_id', $quizResponse->id)
            ->get()
            ->toArray();
    }

    /**
     * Get answers as key-value array for easy lookup
     */
    private function getAnswersAsKeyValue($quizId, $userId)
    {
        $quizResponse = Quizresponse::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->first();

        if (!$quizResponse) {
            return [];
        }

        return Quizresponsedetail::where('quizresponse_id', $quizResponse->id)
            ->pluck('response', 'quizquestion_number')
            ->toArray();
    }

    public function update(Request $request, $quizid)
    {
        $answers = $request->input('answers');
        $userId = Auth::user()->id;

        // Validate answers
        $request->validate([
            'answers' => 'required|array',
            'answers.*.questionnumber' => 'required|integer',
            'answers.*.answer' => 'required|integer|between:1,5',
        ]);

        $responseKey = [
            'user_id' => $userId,
            'quiz_id' => $quizid,
        ];

        $responseRow = [
            'completed_at' => now(),
        ];

        // Create or update the quiz response
        $response = Quizresponse::updateOrCreate($responseKey, $responseRow);

        // Save each answer
        foreach ($answers as $answer) {
            $responseDetailKey = [
                'quizresponse_id' => $response->id,
                'quizquestion_number' => $answer['questionnumber'],
            ];

            $responseDetailRow = [
                'response' => $answer['answer'],
            ];

            Quizresponsedetail::updateOrCreate($responseDetailKey, $responseDetailRow);
        }

        return Redirect::route('quizzes.results', [$quizid])
            ->with('success', 'Congratulations. You completed the quiz.');
    }

    public function anotheruserpie(Request $request, $quizid, $userid)
    {
        $user = Auth::user();
        
        // Check if user is admin (current_team_id == 1)
        if ($user->current_team_id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        $anotherUser = User::findOrFail($userid);
        return $this->drawChart($quizid, $user, $anotherUser);
    }

    /**
     * For current user - directly called via route from page.
     */
    public function resultspie($quizid)
    {
        $user = Auth::user();
        return $this->drawChart($quizid, $user, $user);
    }

    /**
     * Generate chart data for quiz results
     * 
     * Scoring system:
     * - Main categories: 1=-4, 2=-2, 3=1, 4=2, 5=4
     * - Subcategories: Only count responses >= 3, formula: sum(responses)/(count*5)*100
     */
    private function drawChart($quizid, $user, $anotherUser)
    {
        $quiz = Quiz::findOrFail($quizid);

        $quizresponse = Quizresponse::where('user_id', $anotherUser->id)
            ->where('quiz_id', $quizid)
            ->first();

        if (!$quizresponse) {
            abort(404, 'Quiz response not found.');
        }

        // Get category results with adjusted scoring
        $catsorted = DB::table('quizresponsedetails as rd')
            ->join('quizdetails as q', function ($join) use ($quizid) {
                $join->on('rd.quizquestion_number', '=', 'q.questionnumber')
                    ->where('q.quiz_id', '=', $quizid);
            })
            ->join('quizcategories', 'category', 'catname')
            ->where('quizresponse_id', '=', $quizresponse->id)
            ->select(DB::raw('
                TRUNCATE(SUM(response), 0) as unadjusted_score,
                SUM(CASE
                    WHEN response = 1 THEN -4
                    WHEN response = 2 THEN -2
                    WHEN response = 3 THEN 1
                    WHEN response = 4 THEN 2
                    WHEN response = 5 THEN 4
                END) AS score,
                category, bgcolor, bordercolor
            '))
            ->groupBy('category', 'bgcolor', 'bordercolor')
            ->orderBy('score', 'DESC')
            ->get();

        $catresults = $catsorted;

        // Add ranking labels
        if (count($catsorted) >= 3) {
            $catsorted[0]->category = 'Primary Faith Element: ' . $catsorted[0]->category;
            $catsorted[1]->category = 'Secondary Faith Element: ' . $catsorted[1]->category;
            $catsorted[2]->category = 'Third Faith Element: ' . $catsorted[2]->category;
        }

        // Get subcategory results
        $subcatresponses = DB::table('quizresponsedetails as rd')
            ->join('quizdetails as q', function ($join) use ($quizid) {
                $join->on('rd.quizquestion_number', '=', 'q.questionnumber')
                    ->where('q.quiz_id', '=', $quizid);
            })
            ->join('quizsubcategories as qsc', 'subcategory', 'qsc.id')
            ->where('quizresponse_id', '=', $quizresponse->id)
            ->select(DB::raw('
                SUM(CASE WHEN response > 2 THEN response ELSE 0 END) as score,
                COUNT(*) as count,
                subcatdescription, bgcolor, bordercolor
            '))
            ->groupBy('subcatdescription', 'bgcolor', 'bordercolor')
            ->orderBy('score', 'DESC')
            ->get();

        $subcatresults = [];

        // Calculate adjusted scores for subcategories
        foreach ($subcatresponses as $response) {
            $adjustedScore = $response->count > 0 
                ? round(($response->score / ($response->count * 5)) * 100, 2)
                : 0;

            $subcatresults[] = [
                'score' => $adjustedScore,
                'subcategory' => $response->subcatdescription,
                'subcatcount' => $response->count,
                'unadjustedscore' => $response->score,
                'bgcolor' => $response->bgcolor,
                'bordercolor' => $response->bordercolor,
            ];
        }

        $subcatsorted = $subcatresults;

        return Inertia::render('Quiz/Resultsbarcharts', [
            'user' => $user,
            'anotherUser' => $anotherUser,
            'userid' => $anotherUser->id,
            'quiz' => $quiz,
            'catsorted' => $catsorted,
            'catresults' => $catresults,
            'subcatresults' => $subcatresults,
            'subcatsorted' => $subcatsorted,
        ]);
    }
}