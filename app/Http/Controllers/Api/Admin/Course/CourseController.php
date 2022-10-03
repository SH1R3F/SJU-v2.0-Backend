<?php

namespace App\Http\Controllers\Api\Admin\Course;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\SubscriberResource;
use App\Http\Resources\Admin\Course\CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses = Course::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Course::all()->count(),
          'courses' => CourseResource::collection($courses)
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
          'name_ar' => 'required|min:3',
          'name_en' => 'nullable|min:3',
          'region'  => 'required|min:3',
          'type_id' => 'required|integer|exists:types,id',
          'gender_id' => 'required|integer|exists:genders,id',
          'category_id' => 'required|integer|exists:categories,id',
          'location_id' => 'required|integer|exists:locations,id',
          'map_link' => 'nullable|url',
          'map_latitude' => 'nullable',
          'map_longitude' => 'nullable',
          'seats' => 'required|integer',
          'date_from' => 'required|date',
          'date_to' => 'required|date',
          'time_from' => 'required',
          'time_to' => 'required',
          'days' => 'required',
          'minutes' => 'required|integer',
          'percentage' => 'required|integer',
          'price' => 'required|integer',
          'photos' => 'nullable', // To be worked on with upload center
          'trainer' => 'required|min:3',
          'content' => 'required|min:10',
          'summary' => 'required|min:3',
          'zoom_link' => 'nullable',
          'youtube_link' => 'nullable',
          'template_id' => 'required|integer|exists:templates,id'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }
        // Set SN
        $request->merge(['SN' => $this->SN()]);
        // Encode days
        $request->merge(['days' => json_encode($request->days)]);

        // Upload Image
        // if ($request->image) {
        //   $base64Image  = explode(";base64,", $request->image);
        //   $explodeImage = explode("image/", $base64Image[0]);
        //   $imageCourse    = $explodeImage[1];
        //   $image_base64 = base64_decode($base64Image[1]);
        //   $imageName    = uniqid() . '.'.$imageCourse;
        //   Storage::disk('public')->put("courses/images/{$imageName}", $image_base64);
        //   $request->merge(['image' => $imageName]);
        // }
        // Store in database
        $course = Course::create($request->all());
        
        return response()->json([
          'message' => __('messages.successful_create'),
          'course'   => new CourseResource($course)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
      return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name_ar' => 'required|min:3',
          'name_en' => 'nullable|min:3',
          'region'  => 'required|min:3',
          'type_id' => 'required|integer|exists:types,id',
          'gender_id' => 'required|integer|exists:genders,id',
          'category_id' => 'required|integer|exists:categories,id',
          'location_id' => 'required|integer|exists:locations,id',
          'map_link' => 'nullable|url',
          'map_latitude' => 'nullable',
          'map_longitude' => 'nullable',
          'seats' => 'required|integer',
          'date_from' => 'required|date',
          'date_to' => 'required|date',
          'time_from' => 'required',
          'time_to' => 'required',
          'days' => 'required',
          'minutes' => 'required|integer',
          'percentage' => 'required|integer',
          'price' => 'required|integer',
          'photos' => 'nullable', // To be worked on with upload center
          'trainer' => 'required|min:3',
          'content' => 'required|min:10',
          'summary' => 'required|min:3',
          'zoom_link' => 'nullable',
          'youtube_link' => 'nullable',
          'template_id' => 'required|integer|exists:templates,id',
          'questionnaire_id' => 'nullable|integer|exists:questionnaires,id',
          'status' => 'required|integer'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // // Upload Image
        // if ($request->image) {

        //   if (str_starts_with($request->image, 'data:image')) {
        //     $base64Image  = explode(";base64,", $request->image);
        //     $explodeImage = explode("image/", $base64Image[0]);
        //     $imageCourse    = $explodeImage[1];
        //     $image_base64 = base64_decode($base64Image[1]);
        //     $imageName    = uniqid() . '.'.$imageCourse;
        //     // Delete the previous image
        //     Storage::disk('public')->delete("courses/images/{$course->image}");
        //     // Save the new image
        //     Storage::disk('public')->put("courses/images/{$imageName}", $image_base64);
        //     $request->merge(['image' => $imageName]);
        //   } else {
        //     $request->merge(['image' => $course->image]);
        //   }
          
        // } else if($course->image) {
        //   // Delete the previous image
        //   Storage::disk('public')->delete("courses/images/{$course->image}");
        // }


        // Store in database
        $course->update($request->all());

        return response()->json([
          'message' => __('messages.successful_update'),
          'course'  => new CourseResource($course)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        // Delete his files on desk
        // Storage::disk('public')->deleteDirectory("courses/namings/images/{$category->image}");

        // Delete database record
        $course->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }

    public function enrollers(Course $course)
    {
      /**
       * UNFINISHED WORK: Till we add members, volunteers and add the table connects them to courses..
       */
      $subscribers = Subscriber::all();
      return response()->json([
        'total'     => Subscriber::all()->count(),
        'enrollers' => SubscriberResource::collection($subscribers)
      ]);
    }

    private function SN()
    {
      $last = Course::orderBy('id', 'DESC')->first();
      if ($last) {
        $sn = intval(explode('-', $last->SN)[1]);

        $new = strval($sn+1);
        switch(strlen($new)) {
          case 1:
            $new = "000{$new}";
            break;
          case 2:
            $new = "00{$new}";
            break;
          case 3:
            $new = "0{$new}";
            break;
        }
        return 'SJU-' . $new;
      }
      return 'SJU-0001';
    }

}
