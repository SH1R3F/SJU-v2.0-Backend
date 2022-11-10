<?php 

if(!function_exists('get_arabic_day'))
{
    function get_arabic_day($day)
    {
        $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
        $replace = array ("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
        $ar_day = str_replace($find, $replace, $day);
        return $ar_day;
    }
}