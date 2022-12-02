<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Question;
use App\Http\Controllers\Controller;
use App\Models\Course\Questionnaire;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Course\QuestionRequest;

class QuestionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-questionnaire', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-questionnaire', ['only' => 'store']);
        $this->middleware('permission:update-questionnaire', ['only' => 'update']);
        $this->middleware('permission:delete-questionnaire', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Questionnaire $questionnaire)
    {

        return $questionnaire->questions()->orderBy('order', 'ASC')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return $question;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  QuestionRequest  $request
     * @param  Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request, Questionnaire $questionnaire)
    {
        $request->merge(['order' => Question::orderBy('order', 'DESC')->first() ? Question::orderBy('order', 'DESC')->first()->order + 1 : 1]);
        // Store in database
        $questionnaire->questions()->create($request->all());
        return response()->json([
            'message'       => __('messages.successful_create'),
        ], 200);
    }

    /**
     * Update the specified resources order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {

        foreach ($request->questions as $k => $question) {
            Question::where('id', $question['id'])->update(['order' => $k + 1]);
        }

        return response()->json([
            'message' => __('messages.successful_update')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  QuestionRequest  $request
     * @param  Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        // Store in database
        $question->update($request->all());

        return response()->json([
            'message'       => __('messages.successful_update'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ], 200);
    }
}
