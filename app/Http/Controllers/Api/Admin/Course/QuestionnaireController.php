<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course\Questionnaire;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\Course\QuestionnaireResource;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $questionnaires = Questionnaire::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Questionnaire::all()->count(),
          'questionnaires' => QuestionnaireResource::collection($questionnaires)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name_ar'         => 'required|min:3',
          'name_en'         => 'nullable|min:3',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Store in database
        $questionnaire = Questionnaire::create($request->all());

        return response()->json([
          'message'       => __('messages.successful_create'),
          'questionnaire' => new QuestionnaireResource($questionnaire)
        ], 200);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function show(Questionnaire $questionnaire)
    {
        return new QuestionnaireResource($questionnaire);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Questionnaire $questionnaire)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name_ar'         => 'required|min:3',
          'name_en'         => 'nullable|min:3',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Store in database
        $questionnaire->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update'),
          'questionnaire' => new QuestionnaireResource($questionnaire)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Questionnaire  $questionnaire
     * @return \Illuminate\Http\Response
     */
    public function destroy(Questionnaire $questionnaire)
    {
        // Delete database record
        $questionnaire->delete();
    }
}
