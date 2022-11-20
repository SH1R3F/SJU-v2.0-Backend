<?php 

return [
  'custom_font_dir'  => base_path('public/fonts/'), // don't forget the trailing slash!
  'custom_font_data' => [
    'cairo' => [ // must be lowercase and snake_case
      'R'  => 'Cairo-Regular.ttf',    // regular font
      'B'  => 'Cairo-Bold.ttf',       // optional: bold font
      'I'  => 'Cairo-Italic.ttf',     // optional: italic font
      'BI' => 'Cairo-Bold-Italic.ttf' // optional: bold-italic font
    ]
  	// ...add as many as you want.
  ]
];