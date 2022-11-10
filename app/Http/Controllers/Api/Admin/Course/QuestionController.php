<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Question;
use App\Http\Controllers\Controller;
use App\Models\Course\Questionnaire;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-questionnaire', [ 'only' => ['index', 'show']]);
        $this->middleware('permission:create-questionnaire', [ 'only' => 'store']);
        $this->middleware('permission:update-questionnaire', [ 'only' => 'update']);
        $this->middleware('permission:delete-questionnaire', [ 'only' => 'destroy']);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Questionnaire $questionnaire)
    {

        // Validation
        $validator = Validator::make($request->all(), [
          'question' => 'required|min:3',
          'type'     => 'sometimes',
          'answer1'  => 'required_if:type,true',
          'color1'   => 'required_if:type,true',
          'answer2'  => 'required_if:type,true',
          'color2'   => 'required_if:type,true',
          'answer3'  => 'required_if:type,true',
          'color3'   => 'required_if:type,true',
          'answer4'  => 'required_if:type,true',
          'color4'   => 'required_if:type,true',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $request->merge(['order' => Question::orderBy('order', 'DESC')->first() ? Question::orderBy('order', 'DESC')->first()->order + 1 : 1]);

        // Store in database
        $question = $questionnaire->questions()->create($request->all());

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

      foreach($request->questions as $k => $question) {
        Question::where('id', $question['id'])->update(['order' => $k + 1]);
      }

      return response()->json([
        'message' => __('messages.successful_update')
      ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {

        // Validation
        $validator = Validator::make($request->all(), [
          'question' => 'required|min:3',
          'type'     => 'sometimes',
          'answer1'  => 'required_if:type,true',
          'color1'   => 'required_if:type,true',
          'answer2'  => 'required_if:type,true',
          'color2'   => 'required_if:type,true',
          'answer3'  => 'required_if:type,true',
          'color3'   => 'required_if:type,true',
          'answer4'  => 'required_if:type,true',
          'color4'   => 'required_if:type,true',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

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
