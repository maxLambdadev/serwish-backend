<?php

namespace App\Helpers;

class Beautifier
{

    public static $charArr = [
        0 => 'a',
        1 => 'b',
        2 => 'g',
        3 => 'd',
        4 => 'e',
        5 => 'v',
        6 => 'z',
        7 => 't',
        8 => 'i',
        9 => 'k',
        10 => 'l',
        11 => 'm',
        12 => 'n',
        13 => 'o',
        14 => 'p',
        15 => 'zh',
        16 => 'r',
        17 => 's',
        18 => 't',
        19 => 'u',
        20 => 'p',
        21 => 'q',
        22 => 'gh',
        23 => 'y',
        24 => 'sh',
        25 => 'ch',
        26 => 'c',
        27 => 'dz',
        28 => 'w',
        29 => 'ch',
        30 => 'x',
        31 => 'j',
        32 => 'h',
        33 => '-',
        34 => ' ',
    ];

    public static array $array = [
       0 =>'ა',
       1 =>'ბ',
       2 =>'გ',
       3 =>'დ',
       4 =>'ე',
       5 =>'ვ',
       6 =>'ზ',
       7 =>'თ',
       8 =>'ი',
       9 =>'კ',
       10 =>'ლ',
       11 =>'მ',
       12 =>'ნ',
       13 =>'ო',
       14 =>'პ',
       15 =>'ჟ',
       16 =>'რ',
       17 =>'ს',
       18 =>'ტ',
       19 =>'უ',
       20 =>'ფ',
       21 =>'ქ',
       22 =>'ღ',
       23 =>'ყ',
       24 =>'შ',
       25 =>'ჩ',
       26 =>'ც',
       27 =>'ძ',
       28 =>'წ',
       29 =>'ჭ',
       30 =>'ხ',
       31 =>'ჯ',
       32 =>'ჰ',
       33 =>' ',
       34 =>'-',
    ];


    public static function exception(string $message){

    }

    public static function getStatus($item, $key = null, $texts = []){
        $status = '';

//        if ($item->reviewer !== null){
//            $status = '<a href="'.route('manager.users.show',['id'=>$item->reviewer->id]).'"/>' . $item->reviewer->name . '</a> &nbsp;';
//        }
        if (count($texts) != 3){
            $texts = ["განსახილველი","განხილვის პროცესში", "გამოქვეყნებული"];
        }

        switch ($key ? $item->{$key} : $item->review_status){
            case 'started':
                $status .= '<span class="badge bg-danger">'.$texts[0].'</span>';
                break;
            case 'in_review':
            case 'in_progress':
                $status .= '<span class="badge bg-info">'.$texts[1].'</span>';
                break;
            case 'published':
            case 'order_closed':
                $status .= '<span class="badge bg-success">'.$texts[2].'</span>';
                break;
        }

        return $status;
    }

    public static function getOrderStatus($status){
        $status = match ($status) {
            'started' => '<span class="badge bg-info"> ჯგუფი შეიქმნა</span>',
            'in_progress' => '<span class="badge bg-gradient-indigo"> შეკვეთა დაიწყო</span>',
            'order_closed' => '<span class="badge bg-success"> შეკვეთა დასრულდა</span>',
        };
        return $status;
    }

    public static function getPaymentStatus($status){
        $status = match ($status) {
            'in_progress' => '<span class="badge bg-gradient-indigo"> შეკვეთა მუშავდება</span>',
            'declined' => '<span class="badge bg-danger"> შეკვეთა უარყოფილია</span>',
            'approved' => '<span class="badge bg-success"> შეკვეთა დადასტურდა</span>',
        };
        return $status;
    }

    public static function geoToEng(string $text){
        $arr = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        $newText = '';

        foreach($arr as $a){
            $search = array_search($a, self::$array);
            if (isset(self::$array[$search]) && self::$array[$search] !== "" && $search !== false){
                $newText .= self::$charArr[$search];
            }else{
                $newText .= $a;
            }

        }

        return $newText;
    }

    public static function getRoleColor($name){
        if ($name == "administrator") return 'green';
        if ($name == "client") return 'black';

        return 'red';
    }
}
