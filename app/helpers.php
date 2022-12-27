<?php


if (!function_exists('slugMaker')) {
    function slugMaker($string)
    {
        // return str_replace(" ","-",$string);
        // // 2 APPROACH
        // $string = str_replace(array('[\', \']'), '', $string);
        // $string = preg_replace('/\[.*\]/U', '', $string);
        // // $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        // $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        // // $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        // // $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '-', $string);
        // return strtolower(trim($string, '-'));
        // 3rd APPROACH
        $separator = '-';
        // function ($string, ) {
        $_transliteration = [
            "/ö|œ/" => "e",
            "/ü/" => "e",
            "/Ä/" => "e",
            "/Ü/" => "e",
            "/Ö/" => "e",
            "/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/" => "",
            "/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/" => "",
            "/Ç|Ć|Ĉ|Ċ|Č/" => "",
            "/ç|ć|ĉ|ċ|č/" => "",
            "/Ð|Ď|Đ/" => "",
            "/ð|ď|đ/" => "",
            "/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/" => "",
            "/è|é|ê|ë|ē|ĕ|ė|ę|ě/" => "",
            "/Ĝ|Ğ|Ġ|Ģ/" => "",
            "/ĝ|ğ|ġ|ģ/" => "",
            "/Ĥ|Ħ/" => "",
            "/ĥ|ħ/" => "",
            "/Ì|Í|Î|Ï|Ĩ|Ī| Ĭ|Ǐ|Į|İ/" => "",
            "/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/" => "",
            "/Ĵ/" => "",
            "/ĵ/" => "",
            "/Ķ/" => "",
            "/ķ/" => "",
            "/Ĺ|Ļ|Ľ|Ŀ|Ł/" => "",
            "/ĺ|ļ|ľ|ŀ|ł/" => "",
            "/Ñ|Ń|Ņ|Ň/" => "",
            "/ñ|ń|ņ|ň|ŉ/" => "",
            "/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/" => "",
            "/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/" => "",
            "/Ŕ|Ŗ|Ř/" => "",
            "/ŕ|ŗ|ř/" => "",
            "/Ś|Ŝ|Ş|Ș|Š/" => "",
            "/ś|ŝ|ş|ș|š|ſ/" => "",
            "/Ţ|Ț|Ť|Ŧ/" => "",
            "/ţ|ț|ť|ŧ/" => "",
            "/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/" => "",
            "/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/" => "",
            "/Ý|Ÿ|Ŷ/" => "",
            "/ý|ÿ|ŷ/" => "",
            "/Ŵ/" => "",
            "/ŵ/" => "",
            "/Ź|Ż|Ž/" => "",
            "/ź|ż|ž/" => "",
            "/Æ|Ǽ/" => "E",
            "/ß/" => "s",
            "/Ĳ/" => "J",
            "/ĳ/" => "j",
            "/Œ/" => "E",
            "/ƒ/" => ""
        ];
        $quotedReplacement = preg_quote($separator, '/');
        $merge = [
            '/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/[\s\p{Zs}]+/mu' => $separator,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        ];
        $map = $_transliteration + $merge;
        unset($_transliteration);
        $string = preg_replace(array_keys($map), array_values($map), $string);
        $string = strtolower($string);
        return $string;
    }
}

if (!function_exists('persianConvert')) {
    function persianConvert($string, $toPersian = false)
    {
        $persinaDigits1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $persinaDigits2 = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠');
        $allPersianDigits = array_merge($persinaDigits1, $persinaDigits2);
        $replaces = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        // return $req->string;
        if ($toPersian) return str_replace($replaces, $allPersianDigits, $string); // to persian
        else return str_replace($allPersianDigits, $replaces, $string); // to english
    }
}
if (!function_exists('reqConvert')) {
    function reqConvert(&$req, ...$params)
    {
        // $persinaDigits1 = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        // $persinaDigits2 = array('٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠');
        // $allPersianDigits = array_merge($persinaDigits1, $persinaDigits2);
        // $replaces = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        // // return $req->string;
        // if ($toPersian) return str_replace($replaces, $allPersianDigits, $string); // to persian
        // else return str_replace($allPersianDigits, $replaces, $string); // to english
        foreach ($params as $par) {
            $req->$par = persianConvert($req->$par);
        }
    }
}
