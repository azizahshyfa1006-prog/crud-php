<?php
// PHP Barcode Image Generator (Classic Version)
$filepath = isset($_GET["filepath"]) ? $_GET["filepath"] : "";
$text = isset($_GET["text"]) ? $_GET["text"] : "0";
$size = isset($_GET["size"]) ? $_GET["size"] : "20";
$orientation = isset($_GET["orientation"]) ? $_GET["orientation"] : "horizontal";
$code_type = isset($_GET["codetype"]) ? $_GET["codetype"] : "code128";
$print = isset($_GET["print"]) && $_GET["print"] === "true" ? true : false;
$sizefactor = isset($_GET["sizefactor"]) ? $_GET["sizefactor"] : "1";

function barcode($filepath = "", $text = "0", $size = "20", $orientation = "horizontal", $code_type = "code128", $print = false, $sizefactor = 1) {
    $code_string = "";
    // Translate the text into barcode the correct $code_type
    if (in_array(strtolower($code_type), array("code128", "code128a"))) {
        $data = array(
            "A" => array("212222","222122","222221","121223","121322","131222","122213","122312","132212","221213","221312","231212","112232","122132","122231","113222","123122","123221","223211","221132","221231","213212","223112","312131","311222","321122","321221","312212","322112","322211","212123","212321","232121","111323","131123","131321","112313","132113","132311","211313","231113","231311","112133","112331","132131","113123","113321","133121","313121","211331","231131","213113","213311","213131","311123","311321","331121","312113","312311","332111","314111","221411","431111","111224","111422","121124","121421","141122","141221","112214","112412","122114","122411","142112","142211","241211","221114","413111","241112","134111","111242","121142","121241","114212","124112","124211","411212","421112","421211","212141","214121","412121","111143","111341","131141","114113","114311","411113","411311","113141","114131","311141","411131","211412","211214","211412","2331112")
        );
        $code_string = "212222"; // Start A
        for ($i = 0; $i < strlen($text); $i++) {
            $char = substr($text, $i, 1);
            if (isset($data["A"][$char])) {
                $code_string .= $data["A"][$char];
            }
        }
        $code_string .= "2331112"; // Stop
    } else {
        // Fallback default format
        $code_string = "212222212321232121111323131123131321112313132111";
    }

    $img_width = 1;
    for ($i = 0; $i < strlen($code_string); $i++) {
        $img_width += (int)(substr($code_string, $i, 1));
    }
    $img_width *= (int)$sizefactor;
    if ($print) {
        $img_height = (int)$size + 15;
    } else {
        $img_height = (int)$size;
    }

    $image = imagecreate($img_width, $img_height);
    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);

    imagefill($image, 0, 0, $white);
    if ($print) {
        imagestring($image, 5, 31, $img_height - 15, $text, $black);
    }

    $location = 0;
    for ($position = 1; $position <= strlen($code_string); $position++) {
        $cur_size = (int)(substr($code_string, $position - 1, 1)) * (int)$sizefactor;
        if (strtolower($orientation) == "horizontal") {
            if ($position % 2 == 0) {
                imagefilledrectangle($image, $location, 0, $location + $cur_size, (int)$size, $black);
            }
        }
        $location += $cur_size;
    }

    if ($filepath == "") {
        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    } else {
        imagepng($image, $filepath);
        imagedestroy($image);
    }
}

barcode($filepath, $text, $size, $orientation, $code_type, $print, $sizefactor);
?>