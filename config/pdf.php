
<?php

return [
	// ...
	'font_path' => base_path('resources/fonts/'),
	'font_data' => [
		'examplefont' => [
			'R'  => 'ExampleFont-Regular.ttf',    // regular font
			'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
			'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
			'BI' => 'ExampleFont-Bold-Italic.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		]
		// ...add as many as you want.
		],
		'mode'                 => '',
		'format'               => 'A4',
		'default_font_size'    => '10',
		'default_font'         => 'Arial',
		'margin_left'          => 10,
		'margin_right'         => 10,
		'margin_top'           => 10,
		'margin_bottom'        => 10,
		'margin_header'        => 0,
		'margin_footer'        => 0,

		'orientation'          => 'L',
		'title'                => 'RCAT',
		'author'               => 'RCAT',
		'watermark'            => '',
		'show_watermark'       => false,
		'watermark_font'       => 'sans-serif',
		'display_mode'         => 'fullpage',
		'watermark_text_alpha' => 0.1,
		'auto_language_detection'  => true,
];
