<?php

namespace App\Http\Controllers\Api\Admin\Course;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Models\Course\Template;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\Course\TemplateResource;

class TemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read-template', [ 'only' => ['index', 'show']]);
        $this->middleware('permission:create-template', [ 'only' => 'store']);
        $this->middleware('permission:update-template', [ 'only' => 'update']);
        $this->middleware('permission:delete-template', [ 'only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $templates = Template::filter($request)->sortData($request)->offset($request->perPage * $request->page)->paginate($request->perPage);

        return response()->json([
          'total'   => Template::all()->count(),
          'templates' => TemplateResource::collection($templates)
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
          'name'         => 'required|min:3',
          'templatefile' => 'required|file',
          'language'     => 'required',
          'layout'       => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Upload Template file
        if ($request->hasFile('templatefile')) {
          // Save the new file
          $file = $request->file('templatefile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("/courses/templates/", $name, 'public');
          $request->merge(['file' => $name]);
        }

        // Store in database
        $template = Template::create($request->all());

        return response()->json([
          'message' => __('messages.successful_create'),
          'template' => new TemplateResource($template)
        ], 200);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        return new TemplateResource($template);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        // Validation
        $validator = Validator::make($request->all(), [
          'name'         => 'required|min:3',
          'language'     => 'required',
          'layout'       => 'required'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $data = $request->all();

        Storage::disk('public')->delete("courses/templates/{$template->file_preview}");
        if ($request->hasFile('templatefile')) {
          Storage::disk('public')->delete("courses/templates/{$template->file}");
          $file = $request->file('templatefile');
          $name = uniqid() . '.' . $file->extension();
          $file->storeAs("/courses/templates/", $name, 'public');
          $data['file'] = $name;

          $name = 'preview_' . uniqid() . '.' . $file->extension();
          $file->storeAs("/courses/templates/", $name, 'public');
          $data['file_preview'] = $name;
        }
        else { // In case we didn't upload nothing
          // Copy the file template
          $name = 'preview_' . $template->file;
          Storage::copy("/public/courses/templates/" . $template->file, "/public/courses/templates/" . $name);
          $data['file_preview'] = $name;
        }
        
        // Work on template preview?
        $this->preview($request, $data['file_preview']);

        // Store in database
        $template->update($data);

        return response()->json([
          'message' => __('messages.successful_update'),
          'template' => new TemplateResource($template),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        // Delete files on desk
        Storage::disk('public')->deleteDirectory("courses/templates/{$template->file}");

        // Delete database record
        $template->delete();
        return response()->json([
          'message' => __('messages.successful_delete')
        ], 200);

    }

    
    /**
     * Customize template file preview with our requirement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $file_preview
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request, $file_preview)
    {

        // Get our data from the request
        $layout                  = $request->layout;
        $certcode                = $request->certcode;
        $certcode_margins_top    = $request->code_margin_top;
        $certcode_margins_right  = $request->code_margin_right;
        $certcode_margins_bottom = $request->code_margin_bottom;
        $certcode_margins_left   = $request->code_margin_left;

        $istitle      = $request->with_title;
        $male_title   = $request->male_title;
        $female_title = $request->female_title;

        $mode         = $request->language;

        if ($istitle == 1) {
            $or = ($mode == 'ar') ? 'أو' : 'Or';
            $setMaleOrFemaleTitle = "{{$male_title} {$or} {$female_title}}";
        } else {
            $setMaleOrFemaleTitle = '';
        }

        $dir           = ($mode == 'ar') ? 'rtl' : 'ltr';
        $validatetxt   = ($mode == 'ar') ? 'للتحقق من صحة الشهادة' : 'Validate certificate';
        $position_lang = ($mode == 'ar') ? 'right' : 'left';

        // Mpdf Work
        $mpdf = new Mpdf([
          'mode'          => $mode,
          'format'        => $layout,
          'margin_left'   => 0,
          'margin_right'  => 0,
          'margin_top'    => 0,
          'margin_bottom' => 0,
          'margin_header' => 0,
          'margin_footer' => 0
        ]);

        $mpdf->AddFontDirectory(public_path('/fonts'));
        $mpdf->SetDirectionality($dir);
        $path = storage_path("/app/public/courses/templates/{$file_preview}");
        $mpdf->SetDocTemplate($path, 1);
        $html = '';
        $fields = json_decode($request->fields);
        
        // I STOPPED HERE
        // IT SOME HOW MAKES MY BACKEND UNRESPONSIVE !


        // // If fields exist
        foreach ($fields as $field) {
            if ($field->name === '{free_text}') {
              $text = $field->free_text;
            } elseif ($field->name === '{اسم_المتدرب}') {
              $text = $setMaleOrFemaleTitle . ' ' . $field->name;
            }
            $position_y     = empty(trim($field->position_y)) ? '' : 'top: ' . $field->position_y . 'mm; ';
            $position_fixed = empty(trim($field->position_fixed)) ? '' : $field->position_fixed;
            $position_x     = empty(trim($field->position_x)) ? '' : ($position_lang . ': ' . $field->position_x . 'mm; ');
            $font_size      = empty(trim($field->font_size)) ? '25px' : $field->font_size;
            $font_color     = empty(trim($field->font_color)) ? '#000000' : $field->font_color;
            $font_type      = empty(trim($field->font_type)) ? 'gess;' : $field->font_type . ';';

            $html .= "<div style='position: absolute; $position_y $position_fixed $position_x'><span style='font-weight: bold; font-size: $font_size; font-family: $font_type; color: $font_color;'>$text</span></div>";
        }

        // If certificate validation exists
        if ($certcode != 'none') {
            $certcode_margins_top = (!empty($certcode_margins_top)) ? $certcode_margins_top : 0;
            $certcode_margins_right = (!empty($certcode_margins_right)) ? $certcode_margins_right : 0;
            $certcode_margins_bottom = (!empty($certcode_margins_bottom)) ? $certcode_margins_bottom : 0;
            $certcode_margins_left = (!empty($certcode_margins_left)) ? $certcode_margins_left : 0;
            $certcode_pos = $certcode;

            $qr_image = asset('/images/qrcode.jpg');
            // Google says file get contents is not working on php artisan serve, so it's commented till we upload on server !
            $img = ""; //"<img src='$qr_image' style='width: 80px'/>";
            $validate_url = config('app.frontend_url') . '/certval';

            $html .= "<div style='text-align:center;margin-top: $certcode_margins_top;margin-right: $certcode_margins_left; margin-bottom: $certcode_margins_bottom; margin-left: $certcode_margins_right; position: absolute; $certcode_pos'>$img<br><span style='font-size: 12px'>$validatetxt</span><br><span style='font-size: 12px;font-weight: bold'>THE-CODE</span><br><span  style='font-size: 12px'>$validate_url</span></div>";
        }

        try {
          $mpdf->WriteHTML($html);
          $mpdf->Output($path, \Mpdf\Output\Destination::FILE);
        } catch (\Exception $e) {
          dd($e);
        }

    }

}
