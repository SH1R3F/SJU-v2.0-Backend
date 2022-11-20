<?php

namespace App\Http\Controllers\Api;

use Mpdf\Mpdf;
use Endroid\QrCode\QrCode;
use App\Traits\LoggedInUser;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Traits\UserTypeClass;
use App\Models\Course\Certificate;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;

class CertificateController extends Controller
{

    use LoggedInUser, UserTypeClass;

    public function __construct()
    {
        $this->middleware('permission:read-course', [ 'only' => 'showForAdmin']);
    }

    /**
     * Display the specified resource or create it [for admin].
     *
     * @param  Course $event
     * @param  String $type of user
     * @param  String $id of user
     * @return \Illuminate\Http\Response
     */
    public function showForAdmin(Course $event, $type, $id)
    {
        $class = $this->userTypeClass($type);
        $user = $class->findOrFail($id);
        $certificate = $user->certificates()->where('course_id', $event->id)->first();

        if ($certificate) {
            return response()->json([
                'type' => 'certificate',
                'certificate' => asset("storage/courses/certificates/{$certificate->code}.pdf")
            ]); 
        }

        // Otherwise create it
        return $this->create($event, $user);
    }
  
    /**
     * Display the specified resource or create it.
     *
     * @param  Course $event
     * @return \Illuminate\Http\Response
     */
    public function show(Course $event)
    {
        $user = $this->loggedInUser()['user'];
        $certificate = $user->certificates()->where('course_id', $event->id)->first();

        if ($certificate) {
            return response()->json([
                'type' => 'certificate',
                'certificate' => asset("storage/courses/certificates/{$certificate->code}.pdf")
            ]); 
        }

        // Otherwise create it
        return $this->create($event, $user);
    }


    /**
     * Create a new resource.
     *
     * @param  Course  $event
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function create(Course $event, $user)
    {
        $template = $event->template;
        if (!$template) {
          return response()->json([
            'message' => 'Event has no certificate'
          ], 404);
        }

        // Get our data from the template
        $layout                  = $template->layout;
        $certcode                = $template->certcode;
        $certcode_margins_top    = $template->code_margin_top;
        $certcode_margins_right  = $template->code_margin_right;
        $certcode_margins_bottom = $template->code_margin_bottom;
        $certcode_margins_left   = $template->code_margin_left;

        $istitle      = $template->with_title;
        $male_title   = $template->male_title;
        $female_title = $template->female_title;

        $mode         = $template->language;

        if ($istitle == 1) {

            if ($user->gender == 1) { // Female
              $setMaleOrFemaleTitle = $female_title;
            } else { // Male
              $setMaleOrFemaleTitle = $male_title;
            }
        } else {
            $setMaleOrFemaleTitle = '';
        }

        $dir           = ($mode == 'ar') ? 'rtl' : 'ltr';
        $validatetxt   = ($mode == 'ar') ? 'للتحقق من صحة الشهادة' : 'Validate certificate';
        $position_lang = ($mode == 'ar') ? 'right' : 'left';


        $getcoursename = ($mode == 'ar')? $event->name_ar : strtoupper($event->name_en);
        $getdayname    = ($mode == 'ar')? get_arabic_day(date('D', strtotime($event->date_from))):strtoupper(date('l', strtotime($event->date_from)));
        $getuserename  = ($mode == 'ar')? $user->fullName : strtoupper($user->fullName_en);
        $getcoursedate = ($mode == 'ar')? date('Y/m/d', strtotime($event->date_from)) : date('d/m/Y', strtotime($event->date_from));
        
        $replacetitles = array(
            'course_name'   => $getcoursename,
            'user_fullname' => $getuserename,
            'course_day'    => $getdayname,
            'course_date'   => $getcoursedate,
            'course_min'    => $event->minutes ,
        );



        // Mpdf Work
        $mpdf = new Mpdf([
          'mode'          => $mode,
          'format'        => $layout,
          'margin_left'   => 0,
          'margin_right'  => 0,
          'margin_top'    => 0,
          'margin_bottom' => 0,
          'margin_header' => 0,
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
        $mpdf->AddFontDirectory(storage_path('/fonts'));
        $mpdf->SetDirectionality($dir);
        $path = storage_path("/app/public/courses/templates/{$template->file}");
        $mpdf->SetDocTemplate($path, 1);
        $html = '';
        
        $fields = json_decode($template->fields);

        foreach ($fields as $field) {
            $text = $field->name;
            if ($field->name === '{free_text}') {
              $text = $field->free_text;
            }
            if ($field->name === '{اسم_المتدرب}') {
              $text = $setMaleOrFemaleTitle . ' ' . $field->name;
            }
            $position_y     = empty(trim($field->position_y)) ? '' : 'top: ' . $field->position_y . 'mm; ';
            $position_fixed = empty(trim($field->position_fixed)) ? '' : $field->position_fixed;
            $position_x     = empty(trim($field->position_x)) ? '' : ($position_lang . ': ' . $field->position_x . 'mm; ');
            $font_size      = empty(trim($field->font_size)) ? '25px' : $field->font_size;
            $font_color     = empty(trim($field->font_color)) ? '#000000' : $field->font_color;
            $font_type      = empty(trim($field->font_type)) ? 'xbriyaz;' : $field->font_type . ';';
            $position = $position_fixed == 'width: 100%; text-align:center;' ? $position_y . $position_fixed :  $position_y . $position_x;
            $html .= "<div style='position: absolute; $position'><span style='font-weight: bold; font-size: $font_size; font-family: $font_type; color: $font_color;'>$text</span></div>";
        }
        $fieldnames = [
          'free_text'     => '{free_text}',
          'user_fullname' => '{اسم_المتدرب}',
          'course_name'   => '{اسم_الفعالية}',
          'course_day'    => '{اليوم}',
          'course_date'   => '{الموافق}',
          'course_min'    => '{المدة}',
        ];

        foreach ($fieldnames as $key => $value) {
            if($key != 'free_text') {
                $html = str_replace($value, $replacetitles[$key], $html);
            }
        }
        
        $getcertcode = uniqid();
        $qrCode = new QrCode(config('app.frontend_url') . '/certval/' . $getcertcode);


        // If certificate validation exists
        if ($certcode != 'none') {
            $certcode_margins_top = (!empty($certcode_margins_top)) ? $certcode_margins_top : 0;
            $certcode_margins_right = (!empty($certcode_margins_right)) ? $certcode_margins_right : 0;
            $certcode_margins_bottom = (!empty($certcode_margins_bottom)) ? $certcode_margins_bottom : 0;
            $certcode_margins_left = (!empty($certcode_margins_left)) ? $certcode_margins_left : 0;
            $certcode_pos = $certcode;

            $qr_image = asset('/images/qrcode.jpg');
            // Google says file get contents is not working on php artisan serve, so it's commented till we upload on server !
            $img = "";// "<img src='" . $qrCode->writeDataUri() . "' style='width: 80px'/>";
            $validate_url = config('app.frontend_url') . '/certval';

            $html .= "<div style='text-align:center;margin-top: $certcode_margins_top;margin-right: $certcode_margins_left; margin-bottom: $certcode_margins_bottom; margin-left: $certcode_margins_right; position: absolute; $certcode_pos'>$img<br><span style='font-size: 12px'>$validatetxt</span><br><span style='font-size: 12px;font-weight: bold'>$getcertcode</span><br><span  style='font-size: 12px'>$validate_url</span></div>";
        }

        try {
          $mpdf->WriteHTML($html);

          // Store output
          return $this->store($mpdf, $getcertcode, $event->id, $user);
        } catch (\Exception $e) {
          return [
            'error' => $e->getMessage()
          ];
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Mpdf  $mpdf
     * @param  String  $getcertcode
     * @param  Integer  $course_id
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function store($mpdf, $getcertcode, $course_id, $user)
    {
        $path = storage_path("/app/public/courses/certificates/{$getcertcode}.pdf");
        $mpdf->Output($path, \Mpdf\Output\Destination::FILE);

        // Save to database or update if exists
        if ($user->certificates()->where('course_id', $course_id)->count()) {
          $user->certificates()->where('course_id', $course_id)->update([
            'code'        => $getcertcode,
            'certificate' => $getcertcode . '.pdf',
          ]);
        } else {
          $user->certificates()->create([
            'code'        => $getcertcode,
            'course_id'   => $course_id,
            'certificate' => $getcertcode . '.pdf',
          ]);
        }

        return response()->json([
          'type' => 'certificate',
          'certificate' => asset("storage/courses/certificates/{$getcertcode}.pdf")
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
