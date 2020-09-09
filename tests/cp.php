<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 6.0 for ThinkPhP 6.0
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 [ https://www.dtapp.net ]
// +----------------------------------------------------------------------
// | 官方网站: https://gitee.com/liguangchun/ThinkLibrary
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/liguangchun/ThinkLibrary
// | github 仓库地址 ：https://github.com/GC0202/ThinkLibrary
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

function sx()
{
    $a = [
        '鸡',
        '猴',
        '羊',
        '马',
        '蛇',
        '龙',
        '兔',
        '虎',
        '牛',
        '鼠',
        '猪',
        '狗',
    ];
    $n = 2005;
    $res[$n] = $a;
    for ($i = 1; $i <= 14; $i++) {
        array_unshift($a, array_pop($a));
        $res[$n + $i] = $a;
    }
    $file = 'cp_log.json';
    $fp = fopen($file, 'ab');
    fwrite($fp, json_encode($res, JSON_UNESCAPED_UNICODE));
    fclose($fp);

}

function rq()
{
    $ia = 1;
    $ya = 2004;

    $array = array();
    // 循环年份
    for ($i = 0; $i <= 14; $i++) {
        $ya = ++$ya;
        // 循环月份
        for ($ii = 1; $ii <= 12; $ii++) {
            $j = date("t", strtotime("$ya-$ii")); //获取当前月份天数
            $start_time = strtotime(date("$ya-$ii-01"));  //获取本月第一天时间戳
            for ($iii = 0; $iii < $j; $iii++) {
                // 判断星期几
                $weekarray = ["日", "一", "二", "三", "四", "五", "六"];
                $week = $weekarray[date('w', $start_time + $iii * 86400)]; //每隔一天赋值给数组
                if ($week === "一" || $week === "三" || $week === "五") {
                    $array[$ya][] = date('Y-m-d', $start_time + $iii * 86400);
                } // 赋值给数组
            }
        }
    }

    $file = 'cp_log_rq.json';
    $fp = fopen($file, 'ab');
    fwrite($fp, json_encode($array, JSON_UNESCAPED_UNICODE));
    fclose($fp);
}

/**
 * 随机返回
 * @param $number
 * @param $ary
 * @return array
 */
function uni($number, $ary)
{
    // @number 需要bai取多少du个元素zhi
    // @ary  原数组dao
    $final = [];
    while (count($final) < $number) {
        $element = $ary[array_rand($ary, 1)];
        in_array($element, $final, true) or $final[] = $element;
    }
    return $final;
}

/**
 * 开奖数字
 */
function mun()
{
    $mun = [];
    for ($i = 1; $i <= 49; $i++) {
        $ii = $i;
        if (strlen($ii) === 1) {
            $ii = "0{$ii}";
        } else {
            $ii = (string)$ii;
        }
        $mun[$i] = $ii;
    }
    $file = 'cp_log_mun.json';
    $fp = fopen($file, 'ab');
    fwrite($fp, json_encode($mun, JSON_UNESCAPED_UNICODE));
    fclose($fp);
}

/**
 * 开奖生肖
 */
function cx()
{
    $json_string_log = file_get_contents('cp_log.json');
    $data_log = json_decode($json_string_log, true);

    $array = [];
    foreach ($data_log as $key => $value) {
        foreach ($value as $k => $v) {
            $array[$key][$v] = clCx($k);
        }
    }

    $file = 'cp_log_cx.json';
    $fp = fopen($file, 'ab');
    fwrite($fp, json_encode($array, JSON_UNESCAPED_UNICODE));
    fclose($fp);
}

/**
 * 开奖生肖
 */
function cxFb()
{
    $json_string_log = file_get_contents('cp_log_cx.json');
    $data_log = json_decode($json_string_log, true);

    $array = [];
    foreach ($data_log as $key => $value) {
        foreach ($value as $k => $v) {
            foreach ($v as $kk => $vv) {
                $array[$key][$vv] = $k;
            }
        }
    }

    $file = 'cp_log_cx_fb.json';
    $fp = fopen($file, 'ab');
    fwrite($fp, json_encode($array, JSON_UNESCAPED_UNICODE));
    fclose($fp);
}

function clCx($k)
{
    switch ($k) {
        case 0:
            return [
                '01',
                '13',
                '25',
                '37',
                '49',
            ];
            break;
        case 1:
            return [
                '02',
                '14',
                '26',
                '38',
            ];
            break;
        case 2:
            return [
                '03',
                '15',
                '27',
                '39',
            ];
            break;
        case 3:
            return [
                '04',
                '16',
                '28',
                '40',
            ];
            break;
        case 4:
            return [
                '05',
                '17',
                '29',
                '41',
            ];
            break;
        case 5:
            return [
                '06',
                '18',
                '30',
                '42',
            ];
            break;
        case 6:
            return [
                '07',
                '19',
                '31',
                '43',
            ];
            break;
        case 7:
            return [
                '08',
                '20',
                '32',
                '44',
            ];
            break;
        case 8:
            return [
                '09',
                '21',
                '33',
                '45',
            ];
            break;
        case 9:
            return [
                '10',
                '22',
                '34',
                '46',
            ];
            break;
        case 10:
            return [
                '11',
                '23',
                '35',
                '47',
            ];
            break;
        case 11:
            return [
                '12',
                '24',
                '36',
                '48',
            ];
            break;
        default:
            return [];
            break;
    }
}

function kj()
{
    $json_string_rq = file_get_contents('cp_log_rq.json');
    $data = json_decode($json_string_rq, true);

    $json_string_log = file_get_contents('cp_log.json');
    $data_log = json_decode($json_string_log, true);

    $json_string_mun = file_get_contents('cp_log_mun.json');
    $data_mun = json_decode($json_string_mun, true);

    $json_string_cx = file_get_contents('cp_log_cx.json');
    $data_cx = json_decode($json_string_cx, true);

    $json_string_cx_fb = file_get_contents('cp_log_cx_fb.json');
    $data_cx_fb = json_decode($json_string_cx_fb, true);
    //循环
    $res = [];
    foreach ($data as $key => $value) {
        $times = 000;
        foreach ($value as $k => $v) {
            $times = ++$times;
            if (strlen($times) === 2) {
                $times = "0{$times}";
            } else if (strlen($times) === 1) {
                $times = "00{$times}";
            }
            $x['year'] = $key;//年份
            $x['times'] = "{$key}/{$times}";//TIMES
            $mun = uni(7, $data_mun);
            $special_code = $mun[6];
            array_pop($mun);
            $x['flat_yard'] = implode(',', $mun);//平码
            $x['special_code'] = $special_code;//特码
            $x['zodiac_sign'] = $data_cx_fb[$key][$special_code];//生肖
            $x['wave_road'] = getWaveInfo($special_code);//波路
            $x['single_and_double'] = judgeSingDual($special_code);//单双
            $x['five_elements'] = getFiveElementsInfo($special_code);//五行
            $x['kaye'] = getKayeInfo($data_cx_fb[$key][$special_code]);//家野
            $x['size'] = getSizeInfo($special_code);//大小
            $x['men_and_women'] = getGenderInfo($data_cx_fb[$key][$special_code]);//男女
            $x['world'] = getWorldInfo($data_cx_fb[$key][$special_code]);//天地
            $x['special_head'] = substr($special_code, 0, 1) . "头";//特头
            $x['mantissa'] = substr($special_code, -1) . "尾";//尾数
            $total_single_and_even_number = uni(1, $data_mun);
            $x['total_single_and_even_number'] = $total_single_and_even_number[0] . '合';//合数/单双
            $x['total_single_and_even_type'] = '合' . judgeSingDual($total_single_and_even_number[0]);//数/单双
            $x['draw_date'] = $v . ' 21:30:00';//开奖时间
            $res[] = $x;
        }
    }
    $file = 'cp_log_kj.json';
    $fp = fopen($file, 'ab');
    fwrite($fp, json_encode($res, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
    fclose($fp);
}

/**
 * 判断单双
 * 1为单，0为双
 * @param array|integer $numbers
 * @return int
 */
function judgeSingDual($numbers)
{
    if (is_array($numbers)) {
        $total = 0;
        foreach ($numbers as $n) {
            $total += (int)$n;
        }
        if ($total % 2 === 0) {
            return '单';
        }

        return '双';
    }

    if ($numbers % 2 === 0) {
        return '单';
    }

    return '双';
}

function getSizeInfo($str)
{
    if ($str <= 0 || $str >= 49) {
        return '';
    }
    $name = "小";
    if ($str >= 01 && $str <= 24) {
        return $name;
    }
    $name = "大";
    if ($str >= 25 && $str <= 48) {
        return $name;
    }
    return false;
}

function getWorldInfo($str)
{
    $name = "天";
    $day_data = ['兔', '马', '猴', '猪', '牛', '龙'];
    if (in_array((string)$str, $day_data, true)) {
        return $name;
    }

    $name = "地";
    $ground_data = ['蛇', '羊', '鸡', '狗', '鼠', '虎'];
    if (in_array((string)$str, $ground_data, true)) {
        return $name;
    }

    return false;
}

function getGenderInfo($str)
{
    $name = "男";
    $male_data = ['牛', '虎', '兔', '羊', '猴', '鸡'];
    if (in_array((string)$str, $male_data, true)) {
        return $name;
    }

    $name = "女";
    $female_data = ['鼠', '龙', '蛇', '马', '狗', '猪'];
    if (in_array((string)$str, $female_data, true)) {
        return $name;
    }

    return false;
}

function getKayeInfo($str)
{
    $name = "野兽";
    $wild_data = ['猴', '蛇', '龙', '兔', '虎', '鼠'];
    if (in_array((string)$str, $wild_data, true)) {
        return $name;
    }
    $name = "家畜";
    $livestock_data = ['羊', '马', '牛', '猪', '狗', '鸡'];
    if (in_array((string)$str, $livestock_data, true)) {
        return $name;
    }
    return false;
}

function getFiveElementsInfo($str)
{
    if ($str <= 0 || $str > 49) {
        return '';
    }

    $name = "土";
    $earth_data = ['01', '02', '21', '22', '29', '30', '39', '40', '43', '44'];
    if (in_array((string)$str, $earth_data, true)) {
        return $name;
    }

    $name = "火";
    $fire_data = ['03', '04', '11', '12', '13', '14', '33', '34', '41', '42'];
    if (in_array((string)$str, $fire_data, true)) {
        return $name;
    }

    $name = "金";
    $gold_data = ['05', '06', '19', '20', '27', '28', '35', '36', '37', '38'];
    if (in_array((string)$str, $gold_data, true)) {
        return $name;
    }

    $name = "水";
    $water_data = ['07', '08', '15', '16', '23', '24', '25', '26', '45', '46'];
    if (in_array((string)$str, $water_data, true)) {
        return $name;
    }

    $name = "木";
    $wood_data = ['09', '10', '17', '18', '31', '32', '47', '48'];
    if (in_array((string)$str, $wood_data, true)) {
        return $name;
    }
    return '';
}

function getWaveInfo($str)
{
    if ($str <= 0 || $str > 49) {
        return '';
    }

    $name = "红";
    $red_data = ['01', '02', '07', '08', '12', '13', '18', '19', '23', '24', '29', '30', '34', '35', '40', '45', '46'];
    if (in_array((string)$str, $red_data, true)) {
        return $name;
    }

    $name = "蓝";
    $blue_data = ['03', '04', '09', '10', '14', '15', '20', '25', '26', '31', '36', '37', '41', '42', '47', '48'];
    if (in_array((string)$str, $blue_data, true)) {
        return $name;
    }

    $name = "绿";
    $green_data = ['05', '06', '11', '16', '17', '21', '22', '27', '28', '32', '33', '38', '39', '43', '44', '49'];
    if (in_array((string)$str, $green_data, true)) {
        return $name;
    }

    return '';
}
