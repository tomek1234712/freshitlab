<?php

/*
 * debug function
 */

function tab($arr){
    echo '<pre style="border: 1px solid #797979;background: #EEEEEE;font-size: 13px;max-width: 100%;display: block;">';
    print_r($arr);
    echo '</pre>';
}

/*
 * Logs
 */

class Logging {
    // declare log file and file pointer as private properties
    private $log_file, $fp;
    // set log file (path and name)
    public function lfile($path) {
        $this->log_file = $path;
    }
    // write message to the log file
    public function lwrite($message) {
        // if file pointer doesn't exist, then open log file
        if (!is_resource($this->fp)) {
            $this->lopen();
        }
        // define script name
        $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        // define current time and suppress E_WARNING if using the system TZ settings
        // (don't forget to set the INI setting date.timezone)
        $time = @date('[d/M/Y:H:i:s]');
        // write current time, script name and message to the log file
        fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
    }
    // close log file (it's always a good idea to close a file when you're done with it)
    public function lclose() {
        fclose($this->fp);
    }
    // open log file (private method)
    private function lopen() {
        // in case of Windows set default log file
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = 'c:/php/logfile.txt';
        }
        // set default log file for Linux and other systems
        else {
            $log_file_default = '/tmp/logfile.txt';
        }
        // define log file from lfile method or use previously set default
        $lfile = $this->log_file ? $this->log_file : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
    }
}
//$log = new Logging();
//$log->lfile(THEME_DIR . '/log.txt');
//$log->lwrite(current_filter() .' => '.__FUNCTION__);
//$log->lwrite(print_r($update, true));
//$log->lclose();



function get_my_image($imgId, $size = 'full', $echo = true){
    $img = wp_get_attachment_image_src($imgId,$size);
    $output = "";
    if(isset($img[0]) && !empty($img[0])){
        $output = $img[0];
    }
    if($echo)
        echo $output;
    else
        return $output;
}

/*
 * Function print class
 */

function print_class_if($view,$echo = true){

    switch($view){
        case 'mobile':
            $class = 'hidden-lg hidden-md hidden-sm';
            break;
        default:
            $class = "";
            break;
    }

    if($echo)
        echo $class;
    else
        return $class;
}


function download_file( $id ){

    $fullPath = get_attached_file( $id );

    // Must be fresh start
    if( headers_sent() )
        die('Headers Sent');

    // Required for some browsers
    if(ini_get('zlib.output_compression'))
        ini_set('zlib.output_compression', 'Off');

    // File Exists?
    if( file_exists($fullPath) ){

        // Parse Info / Get Extension
        $fsize = filesize($fullPath);
        $path_parts = pathinfo($fullPath);
        $ext = strtolower($path_parts["extension"]);

        // Determine Content Type
        switch ($ext) {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/force-download";
        }

        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // required for certain browsers
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$fsize);
        ob_clean();
        flush();
        readfile( $fullPath );

    } else
        die('File Not Found');
}


function file_type_icon( $id ){

    $fullPath = get_attached_file( $id );

    $ctype="filee";

    if( file_exists($fullPath) ){

        $path_parts = pathinfo($fullPath);
        $ext = strtolower($path_parts["extension"]);

        switch ($ext) {
            case "pdf": $ctype="pdf"; break;
            case "zip": $ctype="zip"; break;
            case "doc": $ctype="doc"; break;
            case "docx": $ctype="doc"; break;
            case "xls": $ctype="xls"; break;
            case "ppt": $ctype="ppt"; break;
            case "png": $ctype="png"; break;
            case "jpeg":
            case "jpg": $ctype="jpg"; break;
            default: $ctype=$ext;
        }
    }

    return $ctype;

}

function dateDifference($date_1 , $date_2  )
{

    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    $endDate = array(
        date('d', strtotime($date_2)),
        date('m', strtotime($date_2)),
        date('Y', strtotime($date_2))
    );

    $startData = array(
        date('d', strtotime('-'. $interval->format('%a') .' day', strtotime($date_2))),
        date('m', strtotime('-'. $interval->format('%a') .' day', strtotime($date_2))),
        date('Y', strtotime('-'. $interval->format('%a') .' day', strtotime($date_2)))
    );

    return array_diff($startData, $endDate);
}

function prezentacja_func( $atts ) {

    $output = "";
    $prezentacja = get_field('prezentacja', get_the_ID());
    $loop = 0;
    if(!empty($prezentacja)){
        $output = "<div class='prezentacja'>";
            $outputSlider = "<ul id='slider_prezentacja'>";
            $outputSliderPager = "<div id='slider_pager_prezentacja'>";

            foreach($prezentacja as $slide):

                $outputSliderPager .= '<a data-slide-index="'.$loop.'" href="">'.$slide['nazwa_pozycji'].'</a>';


                $outputSlider .= "<li>";
                    $outputSlider .= '<img class="openPreview" data-popup="'.$loop.'" src="'.get_my_image($slide['grafika_glowna'], 'full', false).'" alt="" />';
                    $outputSlider .= '<div class="popup" data-popup="'.$loop.'">';
                        $outputSlider .= '<span class="popup-close" data-popup="'.$loop.'"></span>';
                        $outputSlider .= '<div class="popup-body">';

                        switch($slide['rozmieszczenie']){
                            case '1':
                                $outputSlider .= '<div class="img-left">';
                                    $outputSlider .= '<div class="popup-img"><img src="'.get_my_image($slide['grafika'], 'full', false).'" alt="" /></div>';
                                    $outputSlider .= '<div class="popup-desc">'.$slide['opis'].'</div>';
                                $outputSlider .= '</div>';
                                break;
                            case '2':
                                $outputSlider .= '<div class="img-right">';
                                    $outputSlider .= '<div class="popup-desc">'.$slide['opis'].'</div>';
                                    $outputSlider .= '<div class="popup-img"><img src="'.get_my_image($slide['grafika'], 'full', false).'" alt="" /></div>';
                                $outputSlider .= '</div>';
                                break;
                            case '3':
                                $outputSlider .= '<div class="img-top">';
                                    $outputSlider .= '<div class="popup-img"><img src="'.get_my_image($slide['grafika'], 'full', false).'" alt="" /></div>';
                                    $outputSlider .= '<div class="popup-desc">'.$slide['opis'].'</div>';
                                $outputSlider .= '</div>';
                                break;
                            case '4':
                                $outputSlider .= '<div class="img-bottom">';
                                    $outputSlider .= '<div class="popup-desc">'.$slide['opis'].'</div>';
                                    $outputSlider .= '<div class="popup-img"><img src="'.get_my_image($slide['grafika'], 'full', false).'" alt="" /></div>';
                                $outputSlider .= '</div>';
                                break;
                        }

                        $outputSlider .= '</div>';
                    $outputSlider .= '</div>';
                $outputSlider .= "</li>";

                $loop++;

            endforeach;

            $outputSlider .= "</ul>";
            $outputSliderPager .= "</div>";


        $output .= $outputSliderPager . $outputSlider . "</div>";
    }

    return $output;
}
add_shortcode( 'prezentacja', 'prezentacja_func' );
