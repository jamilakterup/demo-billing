<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'sans-serif',
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'Laravel mPDF',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => true,
    'show_watermark_image'     => true,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => 'bg/pad.jpg',
    'watermark_image_alpha'    => '',
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'custom_font_dir'          => '',
    'custom_font_data'         => [],
    'auto_language_detection'  => false,
    'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
    'custom_font_dir'          => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data'         => [
        'examplefont' => [ // must be lowercase and snake_case
            'R'  => 'ExampleFont-Regular.ttf',    // regular font
            'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
            'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
            'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
        ],
        'siliguri' => [
            'R'  => 'Hind_Siliguri_Light-Regular.ttf',    // regular font
            'B'  => 'Hind_Siliguri-Bold.ttf',       // optional: bold font
            'I'  => 'Hind_Siliguri_Medium-Regular.ttf',     // optional: italic font
            'BI' => 'Hind_Siliguri_SemiBold-Regular.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'solaimanlipi'      => [
            'R'  => 'SolaimanLipi_29-05-06.ttf',        // regular font
            'B'  => 'SolaimanLipi_Bold_10-03-12.ttf',   // optional: bold font
            'I'  => 'SolaimanLipi_29-05-06.ttf',        // optional: italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'kalpurush'      => [
            'R'  => 'kalpurush.ttf',        // regular font
            'B'  => 'kalpurush.ttf',   // optional: bold font
            'I'  => 'kalpurush.ttf',        // optional: italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'inter'      => [
            'R'  => 'Inter-Regular.ttf',        // regular font
            'B'  => 'Inter-Bold.ttf',   // optional: bold font

        ],

    ]
];
