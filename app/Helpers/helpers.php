<?php
use Illuminate\Support\Facades\Auth;
use stdClass;

if (!function_exists('on_page')) {
    function on_page($path)
    {
        return request()->is(LaravelLocalization::setLocale() . '/'. $path);
    }
}

if (!function_exists('return_if')) {
    function return_if($condition, $value)
    {
        if ($condition) {
            return $value;
        }
    }
}

if (! function_exists('fontDetect')) {
    function fontDetect(string $content, $default = "zawgyi")
    {
        return SteveNay\MyanFont\MyanFont::fontDetect($content, $default);
    }
}

if (! function_exists('isMyanmarSar')) {
    function isMyanmarSar(string $content)
    {
        return SteveNay\MyanFont\MyanFont::isMyanmarSar($content);
    }
}

if (! function_exists('uni2zg')) {
    function uni2zg(string $content)
    {
        return SteveNay\MyanFont\MyanFont::uni2zg($content);
    }
}

if (! function_exists('zg2uni')) {
    function zg2uni(string $content)
    {
        return SteveNay\MyanFont\MyanFont::zg2uni($content);
    }
}

if (! function_exists('currentUserType')) {
    function currentUserType()
    {
        if (!$user_type = optional(auth()->user())->type) {
            $user_type = App\User::TYPE_GUEST;
        }

        return $user_type;
    }
}

if (! function_exists('profileUrl')) {
    function profileUrl($user, $css = null)
    {
        if ($user) {
            return '<a class="'. $css. '" href="'.route('profile.show', $user->username).'">'.$user->name.'</a>';
        }
    }
}

if (! function_exists('mm_search_string')) {
    function mm_search_string($content, $default = "zawgyi")
    {
        if (!is_null($content)) {
            // $font = strtolower(fontDetect($content));
            $locale = app()->getLocale();

            if ($locale == 'my-ZG') {
                $content = zg2uni($content);
            }
        }

        return $content;
    }
}

if (! function_exists('format_like_query')) {
    function format_like_query($string)
    {
        if (config('cms.search_operator') == 'RLIKE') {
            $rule = '[[:<:]]%s[[:>:]]';
            $value = sprintf($rule, $string);
        } else {
            // CREDITS: https://tommcfarlin.com/sprintf-and-like-in-sql/
            $rule = '%%%s%%';
            $value = sprintf($rule, $string);
        }

        return $value;
    }
}

if (! function_exists('get_lecture_from_query_string_or_resource')) {
    function get_lecture_from_query_string_or_resource($from_resource, $from_query_string)
    {
        if ($from_resource) {
            return $from_resource;
        } else if($from_query_string) {
            return $from_query_string;
        }

        return '';
        
    }
}

if (! function_exists('stripTagsFromArray')) {
    function stripTagsFromArray($arr)
    {
        if ($arr && count($arr)) {
            foreach($arr as $idx => $a) {
                $arr[$idx] = strip_tags($a);
            }
        }
        return $arr;
    }
}

if (! function_exists('checkIdEqualToNull')) {
    function checkIdEqualToNull($arr, $idToCheck)
    {
        if ($arr && count($arr)) { 
            foreach($arr as $idx => $a) {
                if($a[$idToCheck] == null) return true;
            }
        }
        return false;
    }
}

if (! function_exists('addTranslations')) {
    function addTranslations($arrObj)
    {
        if ($arrObj && count($arrObj)) { 
            foreach($arrObj as $idx => $a) {
               $arrObj[$idx] = trans($a); 
            }
        }
        return $arrObj;
    }
}

if (! function_exists('removeFakeEmails')) {
    function removeFakeEmails($arrObj)
    {
        if ($arrObj && count($arrObj)) { 
            $newObj =    $arrObj->filter(function ($item) {
                return ( strpos($item->email, "example") == false && strpos($item->email, "test") == false);
            })->values();
            return $newObj;
        }
        return $arrObj;
    }
}

if (! function_exists('changeLanguage')) {
    function changeLanguage($lang = "en")
    {
        app()->setLocale($lang);
        return app()->getLocale(); //\Lang::getLocale();
    }
}

if (! function_exists('setLanguageForSession')) {
    function setLanguageForSession($lang = "en")
    {
        $langs = ['en', 'my-MM', 'both'];
        if(in_array($lang, $langs)) {
            app()->setLocale($lang);
            session()->put('locale', $lang);
            return $lang;
        } else {
            return null;         
        } 
        //return session()->get('locale'); //\Lang::getLocale();
    }
}

if (! function_exists('getLoggedInUserLanguage')) {
    function getLoggedInUserLanguage()
    {   //echo 'get = '.session()->get('locale');
        if(session()->has('locale')) { //echo "has locale ". session('locale');
            app()->setLocale(session('locale'));
        } else { //echo "no  locale in session app locale is ". config('app.locale');
            app()->setLocale(config('app.locale'));    
        } 
        return app()->getLocale();
    }
}

if (! function_exists('calculateAssessmentScore')) {
    function calculateAssessmentScore($type, $ans, $rightAns)
    {   $score = 0;
        switch($type) {
            case 'multiple_choice': $score = $rightAns == $ans ? 1 : 0; break;
            case 'rearrange': $score = $rightAns == $ans ? 1 : 0; break;
            case 'matching': $score = $rightAns == $ans ? 1 : 0; break;
            default: $score = $rightAns == $ans ? 1 : 0; break;
        }
        return $score;
    }
}

if (! function_exists('hasDuplicatesInArray')) {
    function hasDuplicatesInArray($arr)
    {   
        $hasDuplicates = false;
        $uniqueValues = array_count_values($arr);
        foreach($uniqueValues as $val) {
            if($val > 1) $hasDuplicates = true;
        }
        return $hasDuplicates;
    }
}

if (! function_exists('hasDuplicatesInKeyPairArray')) {  
    function hasDuplicatesInKeyPairArray($arr)
    {   
        $hasDuplicates = false;
       
        foreach($arr as $key => $value) {
            $uniqueValues = array_count_values($value); 
            foreach($uniqueValues as $val) {
                if($val > 1) $hasDuplicates = true;
            }
        }
        return $hasDuplicates;
    }
}

if (! function_exists('hasDuplicatesInLectureOrderArray')) {  
    function hasDuplicatesInLectureOrderArray($arr)
    {   
        $hasDuplicates = false;
       
        foreach($arr as $key1 => $val) {
            foreach($val as $key => $value) {
                $uniqueValues = array_count_values($value); 
                foreach($uniqueValues as $val) {
                    if($val > 1) $hasDuplicates = true;
                }
            }
        }
        return $hasDuplicates;
    }
}

if (! function_exists('changeDateFormatToCarbon')) {
    function changeDateFormatToCarbon($dateString)
    {   
        $temp = explode('/', $dateString);
        return $temp[2]."-".$temp[1]."-".$temp[0];
    }
}

if (! function_exists('convertArrayOfObjectToKeyValue')) {
    function convertArrayOfObjectToKeyValue($arr)
    {   
        $keys = array();
        $values = array();
        foreach ($arr as $key => $value)
        {   
            foreach($value as $idx => $val) {
                array_push($keys, $idx);
                array_push($values, $val);
            }           
        }
        $object2 = new stdClass();
        foreach ($keys as $idx => $key2) { //can use array_combine($keys,$values) but it will return as an array
            $object2->$key2 = $values[$idx]; 
        }
        return $object2; //return as obj with key value pairs
        
       // return array_combine($keys,$values); // return as array with key value pairs
    }
}

if (! function_exists('convertObjectArrayToArrayOfObjects')) {
    function convertObjectArrayToArrayOfObjects($objects)
    {   
        $final = array();
        foreach ($objects as $obj)
        {              
            if( isset($obj->custom_properties) && is_array($obj->custom_properties) && count($obj->custom_properties) === 0 )  {
                unset($obj->custom_properties);
            }     //remove the empty custom_properties array from the object                
            array_push($final, $obj);      
        }      
        return $final;
    }
}

if (! function_exists('changeToRelatedResourceGetRequestFormat')) {
    function changeToRelatedResourceGetRequestFormat($keywordString)
    {   
        $temp = explode(',', $keywordString);
        return ['keywords' => $temp];
    }
}

if (! function_exists('changeMcAnswerFormat')) {
    function changeMcAnswerFormat($arr)
    {   
        $final = [];
        foreach ($arr as $a)
        {   
            $object = new stdClass();
            $object->title = $a;
            $object->isSelected = false;
            array_push($final, $object);
        }
        return $final;
    }
}

if (! function_exists('changeBlankParagraphFormat')) {
    function changeBlankParagraphFormat($arr, $optKeywords)
    {   
        $sentences = [];
        $blanks = [];
        foreach ($arr as $obj)
        {   
            foreach ($obj as $key => $val) {
                if(strpos($key, 'sentence_') !== false ) {
                    array_push($sentences, $val);
                }
                if(strpos($key, 'blank_') !== false ) {
                    array_push($blanks, $val);
                }
            }                    
        }
        if($optKeywords) {
            foreach ($optKeywords as $keys) {
                array_push($sentences, '');
                array_push($blanks, $keys);
            }     
        }
        $count = count($sentences) > count($blanks) ? count($sentences) : count($blanks);
        $final = [];
        for ($i =0; $i < $count; $i++) {
            $object = new stdClass();
            if(isset($sentences[$i])) {
                $object->sentence = $sentences[$i];
            }
            if(isset($blanks[$i])) {
                $object->blank = $blanks[$i];
            }
            array_push($final, $object);
        }
        // $object = new stdClass();
            // $object->title = $a;
            // $object->isSelected = false;
            // array_push($final, $object);
        return $final;
    }
}

if (! function_exists('filterFindValueOnly')) {
    function filterFindValueOnly($completed, $value)
    {   
        $final = [];
        foreach ($completed as $obj)
        {   
            foreach($obj as $key => $val) {
                if(strpos($key, $value) !== false ) {
                    array_push($final, $obj);
                }
            }
        }
        return $final;
    }
}

if (! function_exists('convertObjectToArray')) {
    function convertObjectToArray($obj)
    {   
        $final = array();
        foreach ($obj as $key => $val)
        {                      
            array_push($final, $val);      
        }      
        return $final;
    }
}

if (! function_exists('removeArrLevel')) {
    function removeArrLevel($array)
    {   
        $result = array();
        foreach ($array as $key => $value) {
          if (is_array($value)) {
            $result = array_merge($result, $value);
          }
        }
        return $result;
    }
}