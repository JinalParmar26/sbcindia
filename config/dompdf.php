<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf
    'public_path' => null,      // Override the public path if needed
    'remote_enabled' => true,
    
    /*
    |--------------------------------------------------------------------------
    | Defines
    |--------------------------------------------------------------------------
    |
    | The location of the DOMPDF font directory
    |
    | The location of the directory where DOMPDF will store fonts and font metrics
    | Note: This directory must exist and be writable by the webserver process.
    | *Please note the trailing slash.*
    |
    | Notes regarding fonts:
    | Additional .afm font metrics can be added by executing load_font.php from command line.
    |
    | Only the original "Base 14 fonts" are present on all pdf viewers. Additional fonts must
    | be embedded in the pdf file or the PDF may not display correctly. This can significantly
    | increase file size unless font subsetting is enabled. Before embedding a font please
    | review your rights under the font license.
    |
    | Any font specification in the source HTML is translated to the closest font available
    | in the font directory.
    |
    | The pdf standard "Base 14 fonts" are:
    | Courier, Courier-Bold, Courier-Oblique, Courier-BoldOblique,
    | Helvetica, Helvetica-Bold, Helvetica-Oblique, Helvetica-BoldOblique,
    | Times-Roman, Times-Bold, Times-Italic, Times-BoldItalic,
    | Symbol, ZapfDingbats.
    |
    */
    'font_dir' => storage_path('fonts/'),
    
    /*
    |--------------------------------------------------------------------------
    | Enabled Remote
    |--------------------------------------------------------------------------
    |
    | A ratio applied to the fonts. Set it to 1 for a 1:1 ratio. This is useful
    | if you want to make your PDF have a different size than the HTML.
    |
    */
    'font_cache' => storage_path('fonts/'),
    
    /*
    |--------------------------------------------------------------------------
    | Temporary Directory
    |--------------------------------------------------------------------------
    |
    | The location of a temporary directory.
    |
    */
    'temp_dir' => sys_get_temp_dir(),
    
    /*
    |--------------------------------------------------------------------------
    | Root Directory
    |--------------------------------------------------------------------------
    |
    | The root directory of the project. Can be useful for subdirectories.
    |
    */
    'root_dir' => base_path(),
    
    /*
    |--------------------------------------------------------------------------
    | Log File
    |--------------------------------------------------------------------------
    |
    | The location of the log file.
    |
    */
    'log_output_file' => storage_path('logs/dompdf.log'),
    
    /*
    |--------------------------------------------------------------------------
    | Enable embedded PHP
    |--------------------------------------------------------------------------
    |
    | WARNING: This can pose a security risk. Do not use this in production.
    |
    */
    'enable_php' => false,
    
    /*
    |--------------------------------------------------------------------------
    | Enable inline CSS
    |--------------------------------------------------------------------------
    |
    | Enable inline CSS.
    |
    */
    'enable_css_float' => false,
    
    /*
    |--------------------------------------------------------------------------
    | Enable remote
    |--------------------------------------------------------------------------
    |
    | Whether to enable loading of remote stylesheets and images.
    |
    */
    'enable_remote' => true,
    
    /*
    |--------------------------------------------------------------------------
    | Paper Size
    |--------------------------------------------------------------------------
    |
    | Like the Bootstrap grid system, this allows you to specify the number of
    | columns in the grid and their names.
    |
    */
    'paper_size' => 'a4',
    
    /*
    |--------------------------------------------------------------------------
    | Paper Orientation
    |--------------------------------------------------------------------------
    |
    | The orientation of the paper (portrait or landscape).
    |
    */
    'paper_orientation' => 'portrait',
    
    /*
    |--------------------------------------------------------------------------
    | Custom Options
    |--------------------------------------------------------------------------
    |
    | Custom options for dompdf. This array will be passed to the DomPDF
    | constructor.
    |
    */
    'defines' => [
        'font_dir' => storage_path('fonts/'),
        'font_cache' => storage_path('fonts/'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),
        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_font' => 'serif',
        'dpi' => 96,
        'enable_php' => false,
        'enable_javascript' => true,
        'enable_remote' => true,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => false,
    ],
];
