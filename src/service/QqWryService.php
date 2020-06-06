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

namespace DtApp\ThinkLibrary\service;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use think\App;

/**
 * 纯真数据库
 * Class QqWryService
 * @package DtApp\ThinkLibrary\service
 */
class QqWryService extends Service
{
    /**
     * QQWry.Dat文件指针
     * @var resource
     */
    private $fp;

    /**
     * 第一条IP记录的偏移地址
     *
     * @var int
     */
    private $firstIp;

    /**
     * 最后一条IP记录的偏移地址
     * @var int
     */
    private $lastIp;

    /**
     * IP记录的总条数（不包含版本信息记录）
     * @var int
     */
    private $totalIp;

    /**
     * 不存在
     * @var string
     */
    private $unknown = '未知';

    /**
     * 构造函数，打开 QQWry.Dat 文件并初始化类中的信息
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->fp = 0;
        if (($this->fp = fopen(__DIR__ . '/bin/qqwry.dat', 'rb')) !== false) {
            $this->firstIp = $this->getLong();
            $this->lastIp = $this->getLong();
            $this->totalIp = ($this->lastIp - $this->firstIp) / 7;
        }
        parent::__construct($app);
    }

    /**
     * 设置未知的返回字段
     * @param string $unknown
     * @return QqWryService
     */
    public function setUnknown(string $unknown = '未知')
    {
        $this->unknown = $unknown;
        return $this;
    }

    /**
     * 获取省信息
     * @param string $ip
     * @return mixed
     * @throws DtaException
     */
    public function getProvince(string $ip = '')
    {
        return $this->getLocation($ip)['state'];
    }

    /**
     * 获取城市信息
     * @param string $ip
     * @return mixed
     * @throws DtaException
     */
    public function getCity(string $ip = '')
    {
        return $this->getLocation($ip)['city'];
    }

    /**
     * 获取地区信息
     * @param string $ip
     * @return mixed
     * @throws DtaException
     */
    public function getArea(string $ip = '')
    {
        return $this->getLocation($ip)['area'];
    }

    /**
     * 获取运营商信息
     * @param string $ip
     * @return mixed
     * @throws DtaException
     */
    public function getExtend(string $ip = '')
    {
        return $this->getLocation($ip)['extend'];
    }

    /**
     * 根据所给 IP 地址或域名返回所在地区信息
     * @param string $ip
     * @return mixed|null
     * @throws DtaException
     */
    public function getLocation(string $ip = '')
    {
        if (empty($ip)) $ip = get_ip();
        if (strpos($ip, 'http://') === 0) {
            $ip = substr($ip, 7);
            $ip = gethostbyname($ip);
        }
        static $locationData = [];
        if (!isset($locationData[$ip])) {
            if (!$this->fp) throw new DtaException('数据库文件不存在!');            // 如果数据文件没有被正确打开，则直接返回错误
            $location['ip'] = $ip;   // 将输入的域名转化为IP地址
            $ip = $this->packIp($location['ip']);   // 将输入的IP地址转化为可比较的IP地址
            // 不合法的IP地址会被转化为255.255.255.255
            // 对分搜索
            $l = 0;                         // 搜索的下边界
            $u = $this->totalIp;            // 搜索的上边界
            $findip = $this->lastIp;        // 如果没有找到就返回最后一条IP记录（QQWry.Dat的版本信息）
            while ($l <= $u) {              // 当上边界小于下边界时，查找失败
                $i = floor(($l + $u) / 2);  // 计算近似中间记录
                fseek($this->fp, $this->firstIp + $i * 7);
                $beginip = strrev(fread($this->fp, 4));     // 获取中间记录的开始IP地址
                // strrev函数在这里的作用是将little-endian的压缩IP地址转化为big-endian的格式
                // 以便用于比较，后面相同。
                if ($ip < $beginip) {       // 用户的IP小于中间记录的开始IP地址时
                    $u = $i - 1;            // 将搜索的上边界修改为中间记录减一
                } else {
                    fseek($this->fp, $this->getLong3());
                    $endip = strrev(fread($this->fp, 4));   // 获取中间记录的结束IP地址
                    if ($ip > $endip) {     // 用户的IP大于中间记录的结束IP地址时
                        $l = $i + 1;        // 将搜索的下边界修改为中间记录加一
                    } else {                  // 用户的IP在中间记录的IP范围内时
                        $findip = $this->firstIp + $i * 7;
                        break;              // 则表示找到结果，退出循环
                    }
                }
            }
            //获取查找到的IP地理位置信息
            fseek($this->fp, $findip);
            $location['beginip'] = long2ip($this->getLong());   // 用户IP所在范围的开始地址
            $offset = $this->getLong3();
            fseek($this->fp, $offset);
            $location['endip'] = long2ip($this->getLong());     // 用户IP所在范围的结束地址
            $byte = fread($this->fp, 1);    // 标志字节
            switch (ord($byte)) {
                case 1:                     // 标志字节为1，表示国家和区域信息都被同时重定向
                    $countryOffset = $this->getLong3();         // 重定向地址
                    fseek($this->fp, $countryOffset);
                    $byte = fread($this->fp, 1);    // 标志字节
                    switch (ord($byte)) {
                        case 2:             // 标志字节为2，表示国家信息又被重定向
                            fseek($this->fp, $this->getLong3());
                            $location['all'] = $this->getString();
                            fseek($this->fp, $countryOffset + 4);
                            $location['extend'] = $this->getExtendString();
                            break;
                        default:            // 否则，表示国家信息没有被重定向
                            $location['all'] = $this->getString($byte);
                            $location['extend'] = $this->getExtendString();
                            break;
                    }
                    break;
                case 2:                     // 标志字节为2，表示国家信息被重定向
                    fseek($this->fp, $this->getLong3());
                    $location['all'] = $this->getString();
                    fseek($this->fp, $offset + 8);
                    $location['extend'] = $this->getExtendString();
                    break;
                default:                    // 否则，表示国家信息没有被重定向
                    $location['all'] = $this->getString($byte);
                    $location['extend'] = $this->getExtendString();
                    break;
            }
            // CZ88.NET表示没有有效信息
            if (trim($location['all']) == 'CZ88.NET') $location['all'] = $this->unknown;
            if (trim($location['extend']) == 'CZ88.NET') $location['extend'] = '';
            $location['all'] = iconv("gb2312", "UTF-8//IGNORE", $location['all']);
            $location['extend'] = iconv("gb2312", "UTF-8//IGNORE", $location['extend']);
            $location['extend'] = $location['extend'] === null ? '' : $location['extend'];
            $parseData = $this->parseLocation($location['all']);
            $location['state'] = $parseData[0];
            $location['city'] = $parseData[1];
            $location['area'] = $parseData[2];

            // 全部地址
            $res['location_all'] = $location['all'];
            // 运营商
            $res['isp']['name'] = $location['extend'];
            // IP
            $res['ip']['ipv4'] = $location['ip'];
            $res['ip']['beginip'] = $location['beginip'];
            $res['ip']['endip'] = $location['endip'];
            $res['ip']['trueip'] = ip2long($location['ip']);
            $res['ip']['ipv6'] = $this->getNormalizedIP($location['ip']);
            $getAdCodeLatLng = $this->getNameAdCodeLatLng($location['state'], $location['city'], $location['area']);
            // 省份
            $res['province'] = $getAdCodeLatLng['province'];
            // 城市
            $res['city'] = $getAdCodeLatLng['city'];
            // 地区
            $res['district'] = $getAdCodeLatLng['district'];
            $locationData[$ip] = $location;
        }
        return $res;
    }

    /**
     * 解析省市区县
     * @param $location
     * @return array
     * @example '江苏省苏州市吴江市' , '江苏省苏州市吴中区' , '江苏省苏州市昆山市' , '黑龙江省鸡西市' , '广西桂林市' , '陕西省西安市户县' , '河南省开封市通许县' ,'内蒙古呼伦贝尔市海拉尔区','甘肃省白银市平川区','孟加拉','上海市' , '北京市朝阳区' ,'美国' ,'香港' ,  俄罗斯' ,'IANA'
     */
    private function parseLocation($location)
    {
        $state = $city = $area = $this->unknown;
        if (preg_match('/^(.+省)?(新疆|内蒙古|宁夏|西藏|广西|香港|澳门)?(.+市)?(.+市)?(.+(县|区))?/', $location, $preg)) {
            if (count($preg) == 4) {        //匹配 "浙江省杭州市"
                $state = $preg[1] ? $preg[1] : ($preg[2] ? $preg[2] : $preg[3]);
                $city = $preg[3];
            } else if (count($preg) == 7) { //匹配 "浙江省杭州市江干区"
                $state = $preg[1] ? $preg[1] : ($preg[2] ? $preg[2] : $preg[3]);
                $city = $preg[3];
                $area = $preg[5];
            } else if (count($preg) == 3) { //匹配 "香港"
                $state = $preg[1] ? $preg[1] : $preg[2];
                $city = $state;
            } else if (count($preg) == 2) {  //匹配 "浙江省"
                $state = $preg[1] ? $preg[1] : $this->unknown;
            }
        }
        return [$state, $city, $area];
    }

    /**
     * 返回读取的长整型数
     * @return mixed
     */
    private function getLong()
    {
        //将读取的little-endian编码的4个字节转化为长整型数
        $result = unpack('Vlong', fread($this->fp, 4));
        return $result['long'];
    }

    /**
     * 返回读取的3个字节的长整型数
     * @return mixed
     */
    private function getLong3()
    {
        //将读取的little-endian编码的3个字节转化为长整型数
        $result = unpack('Vlong', fread($this->fp, 3) . chr(0));
        return $result['long'];
    }

    /**
     * 返回压缩后可进行比较的IP地址
     * @param $ip
     * @return false|string
     */
    private function packIp($ip)
    {
        // 将IP地址转化为长整型数，如果在PHP5中，IP地址错误，则返回False，
        // 这时intval将Flase转化为整数-1，之后压缩成big-endian编码的字符串
        return pack('N', intval(ip2long($ip)));
    }

    /**
     * 返回读取的字符串
     *
     * @access private
     * @param string $data
     * @return string
     */
    private function getString($data = "")
    {
        $char = fread($this->fp, 1);
        while (ord($char) > 0) {        // 字符串按照C格式保存，以\0结束
            $data .= $char;             // 将读取的字符连接到给定字符串之后
            $char = fread($this->fp, 1);
        }
        return $data;
    }

    /**
     * 返回地区信息
     * @return string
     */
    private function getExtendString()
    {
        $byte = fread($this->fp, 1);    // 标志字节
        switch (ord($byte)) {
            case 0:                     // 没有区域信息
                $area = "";
                break;
            case 1:
            case 2:                     // 标志字节为1或2，表示区域信息被重定向
                fseek($this->fp, $this->getLong3());
                $area = $this->getString();
                break;
            default:                    // 否则，表示区域信息没有被重定向
                $area = $this->getString($byte);
                break;
        }
        return $area;
    }

    /**
     * 析构函数，用于在页面执行结束后自动关闭打开的文件。
     */
    public function __destruct()
    {
        if ($this->fp) fclose($this->fp);
        $this->fp = 0;
    }

    /**
     * ipv4转换ipv6
     * @param $ip
     * @return bool|false|string|string[]|null
     */
    protected function getNormalizedIP($ip)
    {
        if (!is_string($ip)) return '';
        if (preg_match('%^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$%', $ip, $match)) {
            $IPParts = array();
            for ($i = 1; $i <= 4; $i++) {
                $IPPart = (int)$match[$i];
                if ($IPPart > 255) return '';
                $IPParts[$i] = str_pad(decHex($IPPart), 2, '0', STR_PAD_LEFT);
            }
            return '0000:0000:0000:0000:0000:ffff:' . $IPParts[1] . $IPParts[2] . ':' . $IPParts[3] . $IPParts[4];
        }
        return '';
    }

    /**
     * 解析CODE
     * @param $province_name
     * @param $city_name
     * @param $district_name
     * @return array
     */
    private function getNameAdCodeLatLng($province_name, $city_name, $district_name)
    {
        // 名称
        $province['name'] = $province_name;
        $city['name'] = $city_name;
        $district['name'] = $district_name;
        // adcode
        $province['adcode'] = '';
        $city['adcode'] = '';
        $district['adcode'] = '';
        // lat
        $province['lat'] = '';
        $city['lat'] = '';
        $district['lat'] = '';
        // lng
        $province['lng'] = '';
        $city['lng'] = '';
        $district['lng'] = '';

        if (!empty($province_name)) {
            $json_province = json_decode(file_get_contents(__DIR__ . '/bin/province.json'), true);
            foreach ($json_province['rows'] as $key => $value) {
                if ($value['name'] == $province_name) {
                    $province['name'] = $value['name'];
                    $province['adcode'] = $value['adcode'];
                    $province['lat'] = $value['lat'];
                    $province['lng'] = $value['lng'];
                }
            }
        }
        if (!empty($city_name)) {
            $json_city = json_decode(file_get_contents(__DIR__ . '/bin/city.json'), true);
            foreach ($json_city['rows'] as $key => $value) {
                if ($value['name'] == $city_name) {
                    $city['name'] = $value['name'];
                    $city['adcode'] = $value['adcode'];
                    $city['lat'] = $value['lat'];
                    $city['lng'] = $value['lng'];
                }
            }
        }
        if (!empty($district_name)) {
            $json_district = json_decode(file_get_contents(__DIR__ . '/bin/district.json'), true);
            foreach ($json_district['rows'] as $key => $value) {
                if ($value['name'] == $district_name) {
                    $district['name'] = $value['name'];
                    $district['adcode'] = $value['adcode'];
                    $district['lat'] = $value['lat'];
                    $district['lng'] = $value['lng'];
                }
            }
        }
        return [
            'province' => $province,
            'city' => $city,
            'district' => $district
        ];
    }
}
