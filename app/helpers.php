<?php 

use App\Models\SiteOption;

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

if(!function_exists('sendSMS'))
{
    function sendSMS($numbers, $message, $return = 0)
    {
        // Fetch sms settings options
        $settings = SiteOption::where('key', 'sms_settings')->first();
        if (!$settings) {
          return response()->json(['message' => __('messages.error_on_our_side')], 422);
        }

        $key          = array_search('Service provider', array_column($settings->value, 'key'));
        $sms_provider = $settings->value[$key]['value'];

        $userKey  = array_search('Username', array_column($settings->value, 'key'));
        $username = $settings->value[$key]['value'];

        $passKey  = array_search('Password', array_column($settings->value, 'key'));
        $password = $settings->value[$key]['value'];

        $senderKey = array_search('Sender', array_column($settings->value, 'key'));
        $sender    = $settings->value[$key]['value'];

        if ($sms_provider === 1) { // mobily.ws
            // Result messages
            $results = [
                0 => "لم يتم الاتصال بالخادم",
                1 => "تمت عملية الإرسال بنجاح",
                2 => "رصيدك 0 , الرجاء إعادة التعبئة حتى تتمكن من إرسال الرسائل",
                3 => "رصيدك غير كافي لإتمام عملية الإرسال",
                4 => "رقم الجوال (إسم المستخدم) غير صحيح",
                5 => "كلمة المرور الخاصة بالحساب غير صحيحة",
                6 => "صفحة الانترنت غير فعالة , حاول الارسال من جديد",
                7 => "نظام المدارس غير فعال",
                8 => "تكرار رمز المدرسة لنفس المستخدم",
                9 => "انتهاء الفترة التجريبية",
                10 => "عدد الارقام لا يساوي عدد الرسائل",
                11 => "اشتراكك لا يتيح لك ارسال رسائل لهذه المدرسة. يجب عليك تفعيل الاشتراك لهذه المدرسة",
                12 => "إصدار البوابة غير صحيح",
                13 => "الرقم المرسل به غير مفعل أو لا يوجد الرمز BS في نهاية الرسالة",
                14 => "غير مصرح لك بالإرسال بإستخدام هذا المرسل",
                15 => "الأرقام المرسل لها غير موجوده أو غير صحيحه",
                16 => "إسم المرسل فارغ، أو غير صحيح",
                17 => "نص الرسالة غير متوفر أو غير مشفر بشكل صحيح",
                18 => "تم ايقاف الارسال من المزود",
                19 => "لم يتم العثور على مفتاح نوع التطبيق",
            ];

            $url = "www.mobily.ws/api/msgSend.php";
            $applicationType = "68";
            $sender = urlencode($sender);
            $domainName = $_SERVER['SERVER_NAME'];
            $params = "mobile={$username}&password={$password}&numbers={$numbers}&sender={$sender}&msg={$message}&timeSend=0&dateSend=0&applicationType={$applicationType}&domainName={$domainName}&msgId=0&deleteKey=6546542&lang=3";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($ch);

            if ($result != 1) {
                return $result;
            }
            return true;

        } else { // hisms.ws

            // Result messages
            $results = [
                0 => "لم يتم الاتصال بالخادم",
                1 => "رقم الجوال (إسم المستخدم) غير صحيح",
                2 => "كلمة المرور الخاصة بالحساب غير صحيحة",
                3 => "تم الارسال",
                4 => "لا يوجد ارقام",
                5 => "لا يوجد رسالة",
                6 => "اسم المرسل خطأ",
                7 => "اسم المرسل غير مفعل",
                8 => "الرسالة تحتوي على كلمة ممنوعة",
                9 => "لا يوجد رصيد",
                404 => "لم يتم ادخال جميع المدخلات",
                403 => "تم تجاوز عدد المحاولات المسموحة",
                504 => "الحساب معطل",
            ];
            $url = "https://www.hisms.ws/api.php";
            $params = "send_sms&username={$username}&password={$password}&numbers={$numbers}&sender={$sender}&message={$message}";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($ch);
    
            if ($result != 3) {
                return $result;
            }
            return true;
        }  
    }
}