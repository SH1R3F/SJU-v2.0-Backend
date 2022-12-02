<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Admin\ExcelController;
use App\Http\Resources\Admin\Course\CourseResource;
use App\Http\Requests\Admin\Course\StoreCourseRequest;
use App\Http\Resources\Admin\Course\EnrollersResource;
use App\Http\Requests\Admin\Course\UpdateCourseRequest;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-course', ['only' => ['index', 'show', 'enrollers', 'export']]);
        $this->middleware('permission:create-course', ['only' => 'store']);
        $this->middleware('permission:update-course', ['only' => ['update', 'togglePass', 'deleteEnroller']]);
        $this->middleware('permission:delete-course', ['only' => 'destroy']);
    }

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
     * Export a listing of the resource to Excel sheet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $courses = Course::filter($request)->sortData($request)->get();

        $cells = array(
            'A1' => 'م',
            'B1' => 'رقم البرنامج',
            'C1' => 'اسم البرنامج',
            'D1' => 'تاريخ البرنامج',
            'E1' => 'نوع الفعالية',
            'F1' => 'تصنيف الفعالية',
            'G1' => 'الفئة المستهدفة',
            'H1' => 'الموقع',
            'I1' => 'الحالة',
        );
        $cells_keys = array(
            'A' => 'counter',
            'B' => 'SN',
            'C' => 'name',
            'D' => 'date',
            'E' => 'type',
            'F' => 'category',
            'G' => 'gender',
            'H' => 'location',
            'I' => 'status',
        );

        // Build excel cells
        $counter = 2;
        foreach ($courses as $course) {
            foreach ($cells_keys as $key => $val) {
                switch ($val) {
                    case 'counter':
                        $cells[$key . $counter] = $counter - 1;
                        break;

                    case 'SN':
                        $cells[$key . $counter] = $course->SN;
                        break;

                    case 'name':
                        $cells[$key . $counter] = $course->name_ar;
                        break;

                    case 'date':
                        $cells[$key . $counter] = $course->date_from;
                        break;

                    case 'type':
                        $cells[$key . $counter] = $course->type ? $course->type->name_ar : '';
                        break;

                    case 'category':
                        $cells[$key . $counter] = $course->category ? $course->category->name_ar : '';
                        break;

                    case 'gender':
                        $cells[$key . $counter] = $course->gender ? $course->gender->name_ar : '';
                        break;

                    case 'location':
                        $cells[$key . $counter] = $course->location ? $course->location->name_ar : '';
                        break;

                    case 'status':
                        $cells[$key . $counter] = config('sju.courses.status')[$course->status];
                        break;
                }
            }
            $counter++;
        }

        // Create the excel file
        return app(ExcelController::class)->create('courses', $cells);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseRequest $request)
    {
        // Set SN
        $data = $request->all();
        $data['SN'] = $this->SN();
        $data['status'] = 1;

        $course = Course::create($data);

        // Update photos
        $photos = [];
        foreach ($request->images as $photo) {
            if (str_starts_with($photo, 'data:image')) {
                $name = upload_base64_image($photo, "course/images/{$course->id}");
                array_push($photos, asset("storage/course/images/{$course->id}/{$name}"));
            } else {
                array_push($photos, $photo);
            }
        }

        $course->update(['images' => $photos]);

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
     * @param  UpdateCourseRequest  $request
     * @param  Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        // Update photos
        $photos = [];
        foreach ($request->images as $photo) {
            if (str_starts_with($photo, 'data:image')) {
                $name = upload_base64_image($photo, "storage/course/images/{$course->id}");
                array_push($photos, asset("storage/course/images/{$course->id}/{$name}"));
            } else {
                array_push($photos, $photo);
            }
        }

        $data = $request->all();
        $data['images'] = $photos;

        // Store in database
        $course->update($data);

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
        // Fetching all enrollers in this course
        return response()->json([
            // 'total'     => $course->users->count(),
            'enrollers' => EnrollersResource::collection($course->users)
        ]);
    }

    public function togglePass(Course $course, $type, $id)
    {
        switch ($type) {
            case 'member':
                $user = $course->members->where('id', $id)->first();
                break;

            case 'subscriber':
                $user = $course->subscribers->where('id', $id)->first();
                break;

            case 'volunteer':
                $user = $course->volunteers->where('id', $id)->first();
                break;
        }
        $user->pivot->attendance = !$user->pivot->attendance;
        $user->pivot->save();

        return response()->json([
            'message' => __('messages.successful_update'),
        ], 200);
    }

    public function deleteEnroller(Course $course, $type, $id)
    {
        switch ($type) {
            case 'member':
                $user = $course->members->where('id', $id)->first();
                break;

            case 'subscriber':
                $user = $course->subscribers->where('id', $id)->first();
                break;

            case 'volunteer':
                $user = $course->volunteers->where('id', $id)->first();
                break;
        }
        $user->pivot->delete();

        return response()->json([
            'message' => __('messages.successful_delete'),
        ], 200);
    }

    private function SN()
    {
        $last = Course::orderBy('id', 'DESC')->first();
        if ($last) {
            $sn = intval(explode('-', $last->SN)[1]);

            $new = strval($sn + 1);
            switch (strlen($new)) {
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
