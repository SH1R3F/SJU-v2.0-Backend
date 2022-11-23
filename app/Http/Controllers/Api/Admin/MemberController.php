<?php

namespace App\Http\Controllers\Api\Admin;

use Mpdf\Mpdf;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\MemberResource;
use App\Http\Controllers\Api\Admin\ExcelController;

class MemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-member', [ 'only' => ['index', 'show', 'card', 'export']]);
        $this->middleware('permission:create-member', [ 'only' => 'store']);
        $this->middleware('permission:update-member', [ 'only' => ['update', 'toggleActivate', 'accept', 'unaccept', 'toggleApprove', 'toggleRefuse']]);
        $this->middleware('permission:delete-member', [ 'only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('api-admins')->user();
        $members = Member::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        if ($admin->branch_id) {
          // This admin is associated with a branch. Only show him members of this branch!
          $members = Member::where('branch', $admin->branch_id)->filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);
        }

        return response()->json([
          'total'   => Member::filter($request)->get()->count(),
          'members' => MemberResource::collection($members),
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
        $admin = Auth::guard('api-admins')->user();
        $members = Member::filter($request)->sortData($request)->get();

        if ($admin->branch_id) {
          // This admin is associated with a branch. Only show him members of this branch!
          $members = Member::where('branch', $admin->branch_id)->filter($request)->sortData($request)->get();
        }

        if ($request->approved === -2) { // Refused
            $cells = array(
                'A1' => 'م',
                'B1' => 'رقم الهوية',
                'C1' => 'رقم العضوية',
                'D1' => 'البريد الإلكتروني',
                'E1' => 'اسم العضو',
                'F1' => 'فئة العضوية',
                'G1' => 'المدينة',
                'H1' => 'الجوال',
                'I1' => 'سبب الرفض',
                'J1' => 'تاريخ التسجيل',
            );
            
            $cells_keys = array(
                'A' => 'counter',
                'B' => 'national_id',
                'C' => 'membership_number',
                'D' => 'email',
                'E' => 'name',
                'F' => 'type',
                'G' => 'city',
                'H' => 'mobile',
                'I' => 'refusal_reason',
                'J' => 'created_at',
            );
        } else {
            $cells = array(
                'A1' => 'م',
                'B1' => 'رقم الهوية',
                'C1' => 'رقم العضوية',
                'D1' => 'البريد الإلكتروني',
                'E1' => 'اسم العضو',
                'F1' => 'فئة العضوية',
                'G1' => 'المدينة',
                'H1' => 'الجوال',
                'I1' => 'حالة العضو',
                'J1' => 'حالة الدفع',
                'K1' => 'الجهة',
            );

            $cells_keys = array(
                'A' => 'counter',
                'B' => 'national_id',
                'C' => 'membership_number',
                'D' => 'email',
                'E' => 'name',
                'F' => 'type',
                'G' => 'city',
                'H' => 'mobile',
                'I' => 'state',
                'J' => 'status',
                'K' => 'employer',
            );
        }

        // Build excel cells
        $counter = 2;
        foreach ($members as $member) {
          foreach ($cells_keys as $key => $val) {
            switch ($val) {
              case 'counter':
                $cells[$key . $counter] = $counter - 1;
                break;
                
              case 'national_id':
                $cells[$key . $counter] = $member->national_id;
                break;
                
              case 'membership_number':
                $cells[$key . $counter] = $member->membership_number;
                break;
                
              case 'email':
                $cells[$key . $counter] = $member->email;
                break;
                
              case 'name':
                $cells[$key . $counter] = $member->fullName;
                break;
                
              case 'type':
                $cells[$key . $counter] = config('sju.members.types')[$member->subscription->type];
                break;
                              
              case 'city':
                $cells[$key . $counter] = config('sju.branches')[$member->branch];
                break;
                
              case 'mobile':
                $cells[$key . $counter] = $member->mobile;
                break;
                
              case 'state':
                $cells[$key . $counter] = membership_status($member);
                break;
                
              case 'status':
                $status = $member->invoices()->orderBy('id', 'DESC')->first() ? $member->invoices()->orderBy('id', 'DESC')->first()->status : 0;
                $cells[$key . $counter] = config('sju.members.invoice_status')[$status];
                break;
                
              case 'employer':
                $cells[$key . $counter] = $member->employer;
                break;
                
              case 'refusal_reason':
                $cells[$key . $counter] = $member->refusal_reason === 'unsatisfy' ? 'غير مستوفي للشروط' : $member->refusal_reason;
                break;
                
              case 'created_at':
                $cells[$key . $counter] = $member->created_at;
                break;
                
            }
          }
          $counter++;
        }

        // Create the excel file
        return app(ExcelController::class)->create('members', $cells);

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

      $admin = Auth::guard('api-admins')->user();
      if ($admin->branch_id) {
        // This admin is associated with a branch. Only allow him to see members of his branch!
        if ($member->branch !== $admin->branch_id) {
          abort (403);
        }
      }

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
      
        $admin = Auth::guard('api-admins')->user();
        if ($admin->branch_id) {
          // This admin is associated with a branch. Only allow him to see members of his branch!
          if ($member->branch !== $admin->branch_id) {
            abort (403);
          }
        }

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
          // 'national_image'                 => 'nullable',
          // 'employer_letter'                => 'nullable',
          // 'newspaper_license'              => 'nullable',
          // 'job_contract'                   => 'nullable',
          // 'updated_personal_information'   => 'nullable|boolean',
          // 'updated_profile_image'          => 'nullable|boolean',
          // 'updated_national_image'         => 'nullable|boolean',
          // 'updated_employer_letter'        => 'nullable|boolean',
          // 'updated_experiences_and_fields' => 'nullable|boolean',

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

        $data = $request->all();

        // Member email
        if ($request->memberEmail) {
          $data['email'] = $request->memberEmail;
        }

        // Hash password
        if ($request->password) {
          $data['password'] = Hash::make($request->password);
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
            $data['profile_image'] = "members/{$member->id}/profile_image/{$imageName}";

          }
        } else if($member->profile_image) { // If member had avatar then deleted.
          // Delete file from disk
          Storage::disk('public')->delete("members/{$member->id}/profile_image/{$member->profile_image}");
          // Null db value
          $data['profile_image'] = null;
        }

        // Uploading NationalImageFile
        if ($request->hasFile('NationalImageFile')) {
          // Save the new file
          $file = $request->file('NationalImageFile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("members/{$member->id}/national_image/", $name, 'public');
          $data['national_image'] = "members/{$member->id}/national_image/{$name}";

          // Delete the previous if exists
          Storage::disk('public')->delete("members/{$member->id}/national_image/{$member->national_image}");
        } else {
          $data['national_image'] = $member->national_image;
        }

        // Uploading EmployerLetterFile
        if ($request->hasFile('EmployerLetterFile')) {
          // Save the new file
          $file = $request->file('EmployerLetterFile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("members/{$member->id}/employer_letter/", $name, 'public');
          $data['employer_letter'] = "members/{$member->id}/employer_letter/{$name}";

          // Delete the previous if exists
          Storage::disk('public')->delete("members/{$member->id}/employer_letter/{$member->employer_letter}");
        } else {
          $data['employer_letter'] = $member->employer_letter;
        }

        // Uploading JobContractFile
        if ($request->hasFile('JobContractFile')) {
          // Save the new file
          $file = $request->file('JobContractFile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("members/{$member->id}/job_contract/", $name, 'public');
          $data['job_contract'] = "members/{$member->id}/job_contract/{$name}";

          // Delete the previous if exists
          Storage::disk('public')->delete("members/{$member->id}/job_contract/{$member->job_contract}");
        } else {
          $data['job_contract'] = $member->job_contract;
        }

        // Uploading Newspaper license
        if ($request->hasFile('NewspaperLicenseFile')) {
          // Save the new file
          $file = $request->file('NewspaperLicenseFile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("members/{$member->id}/newspaper_license/", $name, 'public');
          $data['newspaper_license'] = "members/{$member->id}/newspaper_license/{$name}";

          // Delete the previous if exists
          Storage::disk('public')->delete("members/{$member->id}/newspaper_license/{$member->employer_letter}");
        } else {
          $data['newspaper_license'] = $member->newspaper_license;
        }

        // Update
        $member->update($data);

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

      $admin = Auth::guard('api-admins')->user();
      if ($admin->branch_id) {
        // This admin is associated with a branch. Only allow him to see members of his branch!
        if ($member->branch !== $admin->branch_id) {
          abort (403);
        }
      }
      
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

      $admin = Auth::guard('api-admins')->user();
      if ($admin->branch_id) {
        // This admin is associated with a branch. Only allow him to see members of his branch!
        if ($member->branch !== $admin->branch_id) {
          abort (403);
        }
      }
      
      $member->approved = 1;
      $member->active = -1;
      $member->save();

      // Set notification
      $member->notifications()->create([
        'title' => 'الاشتراك في العضوية',
        'note'  => 'تم إلفاء موافقة الأدمن'
      ]);

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

      $admin = Auth::guard('api-admins')->user();
      if ($admin->branch_id) {
        // This admin is associated with a branch. Only allow him to see members of his branch!
        if ($member->branch !== $admin->branch_id) {
          abort (403);
        }
      }

      $member->approved = 1;
      $member->active = 1;
      $member->save();

      // Set notification
      $member->notifications()->create([
        'title' => 'الاشتراك في العضوية',
        'note'  => 'تمت موافقة الأدمن'
      ]);

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

      $admin = Auth::guard('api-admins')->user();
      if ($admin->branch_id) {
        // This admin is associated with a branch. Only allow him to see members of his branch!
        if ($member->branch !== $admin->branch_id) {
          abort (403);
        }
      }

      if ($member->approved === 1) {
        $member->approved = 0;
        $member->notifications()->create([
          'title' => 'الاشتراك في العضوية',
          'note'  => 'تمت إلغاء موافقة الفرع'
        ]);
      } else if (!$member->approved) {
        $member->approved = 1;
        $member->notifications()->create([
          'title' => 'الاشتراك في العضوية',
          'note'  => 'تمت موافقة الفرع'
        ]);
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

      $admin = Auth::guard('api-admins')->user();
      if ($admin->branch_id) {
        // This admin is associated with a branch. Only allow him to see members of his branch!
        if ($member->branch !== $admin->branch_id) {
          abort (403);
        }
      }

      if ($member->approved === -2) { // Unrefuse
        $member->approved = 0;
        $member->refusal_reason = null;

        // Set notification
        $member->notifications()->create([
          'title' => 'الاشتراك في العضوية',
          'note'  => 'تم إلغاء الرفض'
        ]);


      } else { // Refuse
        $member->approved = -2;
        if ($request->reason === 'unsatisfy') {
          $member->refusal_reason = 'unsatisfy';
        } else {
          $member->refusal_reason = $request->reason_text;
        }

        // Set notification
        $member->notifications()->create([
          'title' => 'الاشتراك في العضوية',
          'note'  => 'تم رفض الطلب'
        ]);

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

      $admin = Auth::guard('api-admins')->user();
      if ($admin->branch_id) {
        // This admin is associated with a branch. Only allow him to see members of his branch!
        if ($member->branch !== $admin->branch_id) {
          abort (403);
        }
      }

      // Delete his files on desk
      Storage::disk('public')->deleteDirectory("members/{$member->id}");
      
      // Delete database record
      $member->delete();
      return response()->json([
        'message' => __('messages.successful_delete')
      ], 200);
    }
    
    /**
     * Generate the card pdf of the member.
     *
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function card(Member $member)
    {

        $admin = Auth::guard('api-admins')->user();
        if ($admin->branch_id) {
          // This admin is associated with a branch. Only allow him to see members of his branch!
          if ($member->branch !== $admin->branch_id) {
            abort (403);
          }
        }
        

        // Mpdf Work
        $mpdf = new Mpdf([
          'mode' => 'ar',
          'format' => [145, 244], // 550 * 923 px
          'margin_left' => 0, 
          'margin_right' => 0, 
          'margin_top' => 0, 
          'margin_bottom' => 0,
          'margin_footer' => 0, 
          
          'fontDir' => [base_path('public/fonts/')],
          'fontdata' => [ // lowercase letters only in font key
            'almarai' => [ // must be lowercase and snake_case
              'R'  => 'Almarai-Regular.ttf',    // regular font
              'B'  => 'Almarai-Bold.ttf',       // optional: bold font
              'I'  => 'Almarai-Italic.ttf',     // optional: italic font
              'BI' => 'Almarai-Bold-Italic.ttf', // optional: bold-italic font
              'useOTL' => 0xFF,
              'useKashida' => 75,
            ]
          ],
          'default_font' => 'almarai',
          'unAGlyphs' => true,

        ]);
        $mpdf->AddFontDirectory(base_path('public/fonts/'));
        $mpdf->SetDirectionality('rtl');
        $path = public_path("pdf/card.pdf");
        $mpdf->SetDocTemplate($path, 1);

        // Refactor to add the member picture !
        $html = <<<EOD
          <div style="padding-top: 588px; text-align: center;">
            <h6 style='font-size: 26px; font-family: almarai; margin: 0;'>{$member->fullName}</h6>            
            <h6 style='font-size: 26px; font-family: almarai; margin: 0;'>{$member->fullName_en}</h6>            
          </div>
          <div style='font-size: 25px; font-weight: bold; font-family: almarai; margin-top: 34px; text-align: center;'>
            {$member->membership_number}
          </div>
          <div style='color: #FFFFFF; font-size: 20px; font-weight: bold; font-family: almarai; margin-top: 85px; margin-right: 15px; text-align: center;'>
            {$member->subscription->end_date}
          </div>
        EOD;


        try {
          $mpdf->WriteHTML($html);
          // Store output
          Storage::disk('public')->put("members/{$member->id}/card/card.pdf", 1);
          $path = storage_path("/app/public/members/{$member->id}/card/card.pdf");
          $mpdf->Output($path, \Mpdf\Output\Destination::FILE);
          
          return response()->json([
            'path' => asset("storage/members/{$member->id}/card/card.pdf")
          ]);

        } catch (\Exception $e) {
          return [
            'error' => $e->getMessage()
          ];
        }
        

    }
}
