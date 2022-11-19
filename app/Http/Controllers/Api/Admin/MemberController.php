<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\MemberResource;

class MemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-member', [ 'only' => ['index', 'show']]);
        $this->middleware('permission:create-member', [ 'only' => 'store']);
        $this->middleware('permission:update-member', [ 'only' => ['update', 'toggleActivate', 'accept', 'unaccept', 'toggleApprove', 'toggleRefuse']]);
        $this->middleware('permission:delete-member', [ 'only' => 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $members = Member::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        return response()->json([
          'total'   => Member::filter($request)->get()->count(),
          'members' => MemberResource::collection($members),
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
          // Personal information
          'national_id'          => 'required|min:3|unique:members,national_id',
          'mobile'               => 'required|unique:members,national_id',
          'fname_ar'             => 'required|min:3',
          'sname_ar'             => 'required|min:3',
          'tname_ar'             => 'required|min:3',
          'lname_ar'             => 'required|min:3',
          'fname_en'             => 'required|min:3',
          'sname_en'             => 'required|min:3',
          'tname_en'             => 'required|min:3',
          'lname_en'             => 'required|min:3',
          'gender'               => 'required|in:0,1',
          'birthday_meladi'      => 'required|date',
          'birthday_hijri'       => 'required|date',
          'nationality'          => 'required|integer',
          'qualification'        => 'required|min:3',
          'major'                => 'required|min:3',
          'journalist_job_title' => 'required|min:3',
          'journalist_employer'  => 'required|min:3',
          'newspaper_type'       => 'required|integer',
          'job_title'            => 'required|min:3',
          'employer'             => 'required|min:3',
          'worktel'              => 'required',
          'worktel_ext'          => 'required',
          'fax'                  => 'required',
          'fax_ext'              => 'required',
          'post_box'             => 'required|min:3',
          'post_code'            => 'required|min:3',
          'city'                 => 'required|integer',
          'memberEmail'          => 'required|email|unique:members,email',
          'password'             => 'required|min:6',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Member email
        if ($request->memberEmail) {
          $request->merge(['email' => $request->memberEmail]);
        }

        // Hash password
        $request->merge(['password' => Hash::make($request->password)]);

        // Create in database
        $member = Member::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'member' => new MemberResource($member)
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
      return new MemberResource($member);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          // Account information
          'memberEmail' => 'nullable|email|unique:members,email,' . $member->id,
          'password'    => 'nullable|min:6|confirmed',

          // Personal information
          'national_id'          => 'nullable|min:3',
          'source'               => 'nullable|min:3',
          'date'                 => 'nullable|date|min:3',
          'membership_number'    => 'nullable|min:3',
          'fname_ar'             => 'nullable|min:3',
          'sname_ar'             => 'nullable|min:3',
          'tname_ar'             => 'nullable|min:3',
          'lname_ar'             => 'nullable|min:3',
          'fname_en'             => 'nullable|min:3',
          'sname_en'             => 'nullable|min:3',
          'tname_en'             => 'nullable|min:3',
          'lname_en'             => 'nullable|min:3',
          'gender'               => 'nullable|in:0,1',
          'birthday_meladi'      => 'nullable|date',
          'birthday_hijri'       => 'nullable|date',
          'nationality'          => 'nullable',
          'qualification'        => 'nullable|min:3',
          'major'                => 'nullable|min:3',
          'journalist_job_title' => 'nullable|min:3',
          'journalist_employer'  => 'nullable|min:3',
          'newspaper_type'       => 'nullable',
          'job_title'            => 'nullable|min:3',
          'employer'             => 'nullable|min:3',
          
          // Contact information
          'worktel'         => 'nullable',
          'worktel_ext'     => 'nullable',
          'fax'             => 'nullable',
          'fax_ext'         => 'nullable',
          'mobile'          => 'nullable',
          'post_box'        => 'nullable|min:3',
          'post_code'       => 'nullable|min:3',
          'city'            => 'nullable',

          // Experiences and fields


          // Files and update requests
          'national_image'                 => 'nullable',
          'employer_letter'                => 'nullable',
          'newspaper_license'              => 'nullable',
          'updated_personal_information'   => 'nullable|boolean',
          'updated_profile_image'          => 'nullable|boolean',
          'updated_national_image'         => 'nullable|boolean',
          'updated_employer_letter'        => 'nullable|boolean',
          'updated_experiences_and_fields' => 'nullable|boolean',

          // Membership options
          'membership_type'       => 'nullable|integer',
          'membership_start_date' => 'nullable|date',
          'membership_end_date'   => 'nullable|date',
          'invoice_id'            => 'nullable',
          'invoice_status'        => 'nullable|integer',
          'active'                => 'nullable|integer',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        } 

        // Member email
        if ($request->memberEmail) {
          $request->merge(['email' => $request->memberEmail]);
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
            $imageName    = uniqid() . '.'.$imageType;
            Storage::disk('public')->put("members/{$member->id}/profile_image/{$imageName}", $image_base64);
            $request->merge(['profile_image' => $imageName]);

          } else {
            $request->merge(['profile_image' => $member->profile_image]);
          }
        } else if($member->profile_image) { // If member had avatar then deleted.
          // Delete file from disk
          Storage::disk('public')->delete("members/{$member->id}/profile_image/{$member->profile_image}");
          // Null db value
          $request->merge(['profile_image' => null]);
        }

        // Uploading NationalImageFile
        if ($request->hasFile('NationalImageFile')) {
          // Save the new file
          $file = $request->file('NationalImageFile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("members/{$member->id}/national_image/", $name, 'public');
          $request->merge(['national_image' => $name]);

          // Delete the previous if exists
          Storage::disk('public')->delete("members/{$member->id}/national_image/{$member->national_image}");
        }

        // Uploading EmployerLetterFile
        if ($request->hasFile('EmployerLetterFile')) {
          // Save the new file
          $file = $request->file('EmployerLetterFile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("members/{$member->id}/employer_letter/", $name, 'public');
          $request->merge(['employer_letter' => $name]);

          // Delete the previous if exists
          Storage::disk('public')->delete("members/{$member->id}/employer_letter/{$member->employer_letter}");
        }

        // Uploading Newspaper license
        if ($request->hasFile('NewspaperLicenseFile')) {
          // Save the new file
          $file = $request->file('NewspaperLicenseFile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("members/{$member->id}/newspaper_license/", $name, 'public');
          $request->merge(['newspaper_license' => $name]);

          // Delete the previous if exists
          Storage::disk('public')->delete("members/{$member->id}/newspaper_license/{$member->employer_letter}");
        }

        // Update
        $member->update($request->all());

        if ($request->membership_type) {
          $member->subscription->update([
            'type'       => $request->membership_type,
            'start_date' => $request->membership_start_date,
            'end_date'   => $request->membership_end_date,
          ]);
        }
        if ($request->invoice_id) {
          $member->invoices()->orderBy('id', 'DESC')->first()->update([
            'invoice_number' => $request->invoice_id,
            'status'         => $request->invoice_status,
          ]);
        }

        return response()->json([
          'message' => __('messages.successful_update')
        ], 200);

    }

    /**
     * Toggle activate member.
     * Set his active to 0 to deactivate, or to 1 to activate!
     * When member activation is 0 he cannot login
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function toggleActivate(Request $request, Member $member)
    {
      if ($member->active === 1) {
        $member->active = 0;
      } else {
        $member->active = 1;
      }
      $member->save();
      return response()->json([
        'message' => __('messages.successful_update')
      ], 200);
    }

    /**
     * UnAccept member after branch approves him.
     * Set his active back to -1, and the approved value should be 1 by branch acceptance!
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function unaccept(Request $request, Member $member)
    {
      $member->approved = 1;
      $member->active = -1;
      $member->save();
      return response()->json([
        'message' => __('messages.successful_update')
      ], 200);
    }

    /**
     * Accept member after branch approves him.
     * Set his active to 1, and the approved value should be 1 by branch acceptance!
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, Member $member)
    {
      $member->approved = 1;
      $member->active = 1;
      $member->save();
      return response()->json([
        'message' => __('messages.successful_update')
      ], 200);
    }

    /**
     * Branchly toggle approve member.
     * Set his approved value to 1, and active is already -1 by default!
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function toggleApprove(Request $request, Member $member)
    {
      if ($member->approved === 1) {
        $member->approved = 0;
      } else if (!$member->approved) {
        $member->approved = 1;
      }
      $member->save();
      return response()->json([
        'message' => __('messages.successful_update')
      ], 200);
    }

    /**
     * Branchly toggle refuse member.
     * Set his approved value to -2, and active is already -1 by default!
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function toggleRefuse(Request $request, Member $member)
    {
      if ($member->approved === -2) { // Unrefuse
        $member->approved = 0;
        $member->refusal_reason = null;
      } else { // Refuse
        $member->approved = -2;
        if ($request->reason === 'unsatisfy') {
          $member->refusal_reason = 'unsatisfy';
        } else {
          $member->refusal_reason = $request->reason_text;
        }
      }
      $member->save();
      return response()->json([
        'message' => __('messages.successful_update')
      ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        // Delete his files on desk
        Storage::disk('public')->deleteDirectory("members/{$member->id}");

        // Delete database record
        $member->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);
    }
}
