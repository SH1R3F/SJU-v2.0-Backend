<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\Admin\MemberResource;
use App\Http\Resources\Admin\Course\CourseResource;

class MemberController extends Controller
{


    /**
     * Display the data of the default page for members.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Upcoming events
        $upcoming = Course::whereIn('status', [1, 2, 3, 4])->where('date_from', '>', Carbon::now())->get();

        // Enrolled events
        $enrolled = Auth::guard('api-members')->user()->courses()->get();

        return response()->json([
            'upcomingEvents' => CourseResource::collection($upcoming),
            'enrolledEvents' => CourseResource::collection($enrolled)
        ]);
    }


    /**
     * Display the notifications for current member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notifications(Request $request)
    {

        // Last 10 notifications
        $notifications = Auth::guard('api-members')->user()->notifications()->orderBy('id', 'DESC')->take(10)->get();

        return response()->json([
            'notifications' => $notifications
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateMemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberRequest $request)
    {
        $member = Auth::guard('api-members')->user();
        $member->update($request->validated());

        return response()->json([
            'message' => __('messages.successful_update'),
            'user'    => new MemberResource($member)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateExperiences(Request $request)
    {
        $member = Auth::guard('api-members')->user();
        // Validation
        $validator = Validator::make($request->all(), [
            'experiences' => 'required',
            'fields'      => 'required',
            'languages'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $experiences = [];
        foreach ($request->experiences as $experience) {
            if ($experience['name'] && isset($experience['years'])) {
                array_push($experiences, $experience);
            }
        }
        $fields = [];
        foreach ($request->fields as $field) {
            if ($field['name']) {
                array_push($fields, $field);
            }
        }
        $languages = [];
        foreach ($request->languages as $language) {
            if ($language['name'] && isset($language['level'])) {
                array_push($languages, $language);
            }
        }
        $member->update([
            'experiences_and_fields' => [
                'experiences' => $experiences,
                'fields' => $fields,
                'languages' => $languages,
            ]
        ]);

        return response()->json([
            'message' => __('messages.successful_update')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePicture(Request $request)
    {
        $member = Auth::guard('api-members')->user();
        // Validation
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update Avatar
        if (!empty($request->image)) {
            // Delete previous
            if ($member->profile_image) Storage::disk('public')->delete($member->profile_image);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path("storage/members/{$member->id}/profile_image"), $imageName);
            $member->profile_image = "members/{$member->id}/profile_image/{$imageName}";
            $member->save();
        }

        return response()->json([
            'message' => __('messages.successful_update'),
            'image' => asset("storage/{$member->profile_image}")
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateID(Request $request)
    {
        $member = Auth::guard('api-members')->user();
        // Validation
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update Avatar
        if (!empty($request->image)) {
            // Delete previous
            if ($member->national_image) Storage::disk('public')->delete($member->national_image);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path("storage/members/{$member->id}/national_image"), $imageName);
            $member->national_image = "members/{$member->id}/national_image/{$imageName}";
            $member->save();
        }

        return response()->json([
            'message' => __('messages.successful_update'),
            'image' => asset("storage/{$member->national_image}")
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatement(Request $request)
    {
        $member = Auth::guard('api-members')->user();

        // Validation
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update Avatar
        if (!empty($request->image)) {
            // Delete previous
            if ($member->employer_letter) Storage::disk('public')->delete($member->employer_letter);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path("storage/members/{$member->id}/employer_letter"), $imageName);
            $member->employer_letter = "members/{$member->id}/employer_letter/{$imageName}";
            $member->save();
        }

        return response()->json([
            'message' => __('messages.successful_update'),
            'image' => asset("storage/{$member->employer_letter}")
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateContract(Request $request)
    {
        $member = Auth::guard('api-members')->user();

        // Only for members with subscription type === 3 [Affiliate members]
        if ($member->subscription->type !== 3) {
            return abort(404);
        }
        // Validation
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update Avatar
        if (!empty($request->image)) {
            // Delete previous
            if ($member->job_contract) Storage::disk('public')->delete($member->job_contract);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path("storage/members/{$member->id}/job_contract"), $imageName);
            $member->job_contract = "members/{$member->id}/job_contract/{$imageName}";
            $member->save();
        }

        return response()->json([
            'message' => __('messages.successful_update'),
            'image' => asset("storage/{$member->job_contract}")
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLicense(Request $request)
    {
        $member = Auth::guard('api-members')->user();

        // Only for members with newspaper type === 2 [E-newspaper]
        if ($member->newspaper_type !== 2) {
            return abort(404);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update Avatar
        if (!empty($request->image)) {
            // Delete previous
            if ($member->newspaper_license) Storage::disk('public')->delete($member->newspaper_license);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path("storage/members/{$member->id}/newspaper_license"), $imageName);
            $member->newspaper_license = "members/{$member->id}/newspaper_license/{$imageName}";
            $member->save();
        }

        return response()->json([
            'message' => __('messages.successful_update'),
            'image' => asset("storage/{$member->newspaper_license}")
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:6',
            'new_password'     => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $member = Auth::guard('api-members')->user();
        if (Hash::check($request->current_password, $member->password)) {

            $member->password = Hash::make($request->new_password);
            $member->save();
            return response()->json([
                'message' => __('messages.successful_update'),
            ], 200);
        } else {
            return response(['message' => __('messages.password_incorrect')], 422);
        }
    }

    /**
     * Request a membership for current authenticated member.
     * Make him approved = 0 & active = -1 by default
     * So he appears in admin panel applications
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function requestMembership(Request $request)
    {
        $member = Auth::guard('api-members')->user();
        $member->approved = 0;
        $member->save();

        // Set him a notification !
        $member->notifications()->create([
            'title' => 'الاشتراك في العضوية',
            'note'  => 'تم رفع الطلب'
        ]);

        return response()->json([
            'message' => __('messages.successful_register'),
            'approved' => 0
        ]);
    }
}
