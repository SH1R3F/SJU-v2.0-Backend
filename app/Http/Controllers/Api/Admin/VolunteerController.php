<?php

namespace App\Http\Controllers\Api\Admin;

use Carbon\Carbon;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\VolunteerResource;
use App\Http\Requests\Admin\StoreVolunteerRequest;
use App\Http\Controllers\Api\Admin\ExcelController;
use App\Http\Requests\Admin\UpdateVolunteerRequest;
use App\Http\Resources\Admin\Course\CourseResource;

class VolunteerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-volunteer', ['only' => ['index', 'show', 'export']]);
        $this->middleware('permission:create-volunteer', ['only' => 'store']);
        $this->middleware('permission:update-volunteer', ['only' => 'update']);
        $this->middleware('permission:delete-volunteer', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $volunteers = Volunteer::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
            'total'       => Volunteer::filter($request)->get()->count(),
            'volunteers' => VolunteerResource::collection($volunteers)
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

        $volunteers = Volunteer::filter($request)->sortData($request)->get();
        $cells = array(
            'A1' => 'م',
            'B1' => 'اسم المتدرب',
            'C1' => 'البريد الإلكتروني',
            'D1' => 'حالة المشترك',
        );
        $cells_keys = array(
            'A' => 'counter',
            'B' => 'name',
            'C' => 'email',
            'D' => 'status',
        );

        // Build excel cells
        $counter = 2;
        foreach ($volunteers as $volunteer) {
            foreach ($cells_keys as $key => $val) {
                switch ($val) {
                    case 'counter':
                        $cells[$key . $counter] = $counter - 1;
                        break;

                    case 'email':
                        $cells[$key . $counter] = $volunteer->email;
                        break;

                    case 'name':
                        $cells[$key . $counter] = $volunteer->fullName;
                        break;

                    case 'status':
                        $cells[$key . $counter] = config('sju.status')[$volunteer->email_verified_at ? 1 : 0];
                        break;
                }
            }
            $counter++;
        }

        // Create the excel file
        return app(ExcelController::class)->create('volunteers', $cells);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreVolunteerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVolunteerRequest $request)
    {
        // Hash password
        $request->merge(['password' => Hash::make($request->password)]);

        // Update database
        $volunteer = Volunteer::create($request->all());
        event(new Registered($volunteer));

        return response()->json([
            'message' => __('messages.successful_create'),
            'volunteer' => new VolunteerResource($volunteer)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function show(Volunteer $volunteer)
    {
        return new VolunteerResource($volunteer);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function courses(Request $request, Volunteer $volunteer)
    {
        $courses = $volunteer->courses()->withPivot('attendance')->get();;
        return response()->json([
            'total'   => $courses->count(),
            'courses' => CourseResource::collection($courses)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateVolunteerRequest  $request
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVolunteerRequest $request, Volunteer $volunteer)
    {
        // Volunteer email
        if ($request->volunteerEmail) {
            $request->merge(['email' => $request->volunteerEmail]);
        }

        // Hash password
        if ($request->password) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        // Update Avatar
        if ($request->avatar) {
            if (str_starts_with($request->avatar, 'data:image')) {
                $base64Image  = explode(";base64,", $request->avatar);
                $explodeImage = explode("image/", $base64Image[0]);
                $imageType    = $explodeImage[1];
                $image_base64 = base64_decode($base64Image[1]);
                $imageName    = uniqid() . '.' . $imageType;
                Storage::disk('public')->put("volunteers/{$volunteer->id}/images/{$imageName}", $image_base64);
                $request->merge(['image' => "volunteers/{$volunteer->id}/images/{$imageName}"]);
            } else {
                $request->merge(['image' => $volunteer->image]);
            }
        } else if ($volunteer->image) { // If volunteer had avatar then deleted.
            // Delete file from disk
            Storage::disk('public')->delete("volunteers/{$volunteer->id}/images/{$volunteer->image}");
            // Null db value
            $request->merge(['image' => null]);
        }


        // Update
        $volunteer->update($request->all());

        return response()->json([
            'message' => __('messages.successful_update')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Volunteer $volunteer)
    {
        // Delete his files on desk
        Storage::disk('public')->deleteDirectory("volunteers/{$volunteer->id}");

        // Delete database record
        $volunteer->delete();
        return response()->json([
            'message' => __('messages.successful_delete')
        ], 200);
    }

    /**
     * Toggle verification of the specified resource from storage.
     *
     * @param  Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function toggle(Volunteer $volunteer)
    {
        $volunteer->email_verified_at = $volunteer->email_verified_at ? NULL : Carbon::now();
        $volunteer->save();
        return response()->json([
            'message' => __('messages.successful_update')
        ], 200);
    }
}
