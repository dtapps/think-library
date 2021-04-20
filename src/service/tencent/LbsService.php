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

namespace DtApp\ThinkLibrary\service\tencent;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 腾讯地图
 * https://lbs.qq.com/service/webService/webServiceGuide/webServiceOverview
 * Class Lbs
 * @package DtApp\ThinkLibrary\service\tencent
 */
class LbsService extends Service
{
    /**
     * 开发者密钥（Key）
     * @var string
     */
    private $key = "";

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 待请求的链接
     * @var string
     */
    private $api_url = '';

    /**
     * @param string $key
     * @return $this
     */
    public function key(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * 请求参数
     * @param array $param
     * @return $this
     */
    public function param(array $param): self
    {
        $this->param = $param;
        return $this;
    }

    /**
     * IP定位
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceIp
     * @return $this
     */
    public function ip(): self
    {
        $this->api_url = 'https://apis.map.qq.com/ws/location/v1/ip';
        return $this;
    }

    /**
     * 行政区划 - 获取全部行政区划数据
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceDistrict
     * @return $this
     */
    public function districtList(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/district/v1/list";
        return $this;
    }

    /**
     * 行政区划 - 获取全部行政区划数据
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceDistrict
     * @return $this
     */
    public function districtGetChildren(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/district/v1/getchildren";
        return $this;
    }

    /**
     * 行政区划 - 获取全部行政区划数据
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceDistrict
     * @return $this
     */
    public function districtSearch(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/district/v1/search";
        return $this;
    }

    /**
     * 坐标转换 - 实现从其它地图供应商坐标系或标准GPS坐标系，批量转换到腾讯地图坐标系。
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceTranslate
     * @return $this
     */
    public function translate(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/coord/v1/translate";
        return $this;
    }

    /**
     * 批量距离计算（矩阵） - 距离矩阵（DistanceMatrix），用于批量计算一组起终点的路面距离（或称导航距离），可应用于网约车派单、多目的地最优路径智能计算等场景中，支持驾车、步行、骑行多种交通方式，满足不同应用需要。
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceMatrix
     * @return $this
     */
    public function matrix(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/distance/v1/matrix";
        return $this;
    }

    /**
     * 路线规划（驾车/公交/步骑） - 腾讯地图Direction API，提供多种交通方式的路线计算能力，包括：
     * 1. 驾车（driving）：支持结合实时路况、少收费、不走高速等多种偏好，精准预估到达时间（ETA）；
     * 2. 步行（walking）：基于步行路线规划。
     * 3. 骑行（bicycling）：基于自行车的骑行路线；
     * 4. 公交（transit）：支持公共汽车、地铁等多种公共交通工具的换乘方案计算；
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceRoute
     * @return $this
     */
    public function route(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/direction/v1/driving/";
        return $this;
    }

    /**
     * 地址解析（地址转坐标） - 本接口提供由地址描述到所述位置坐标的转换，与逆地址解析的过程正好相反。
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceGeocoder
     * @return $this
     */
    public function geoCoder(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/geocoder/v1/";
        return $this;
    }

    /**
     * 逆地址解析（坐标位置描述） - 本接口提供由坐标到坐标所在位置的文字描述的转换。输入坐标返回地理位置信息和附近poi列表。目前应用于物流、出行、O2O、社交等场景。服务响应速度快、稳定，支撑亿级调用。
     * 1）满足传统对省市区、乡镇村、门牌号、道路及交叉口、河流、湖泊、桥、poi列表的需求。
     * 2）业界首创，提供易于人理解的地址描述：海淀区中钢国际广场(欧美汇购物中心北)。
     * 3）提供精准的商圈、知名的大型区域、附近知名的一级地标、代表当前位置的二级地标等。
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceGcoder
     * @return $this
     */
    public function gCoder(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/geocoder/v1/";
        return $this;
    }

    /**
     * 关键词输入提示 - 用于获取输入关键字的补完与提示，帮助用户快速输入。本接口为纯HTTP数据接口，需配合前端程序实现Autocomplete（自动完成）的效果。
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceSuggestion
     * @return $this
     */
    public function suggestion(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/place/v1/suggestion";
        return $this;
    }

    /**
     * 地点搜索 - 地点搜索（search接口），提供三类范围条件的搜索功能：
     * 指定城市的地点搜索：如在北京搜索餐馆；
     * 圆形区域的地点搜索：一般用于指定位置的周边（附近）地点搜索，如，搜索颐和园附近的酒店；
     * 矩形区域的地点搜索：在地图应用中，往往用于视野内搜索，因为显示地图的区域是个矩形。
     * https://lbs.qq.com/service/webService/webServiceGuide/webServiceSuggestion
     * @return $this
     */
    public function search(): self
    {
        $this->api_url = "https://apis.map.qq.com/ws/place/v1/search";
        return $this;
    }

    /**
     * @return array|mixed
     * @throws DtaException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        if (empty($this->key)) {
            throw new DtaException('开发密钥不能为空');
        }
        if (empty($this->api_url)) {
            throw new DtaException('请检查需要调用的接口');
        }
        $this->param['key'] = $this->key;
        $this->http();
        // 正常
        if (is_array($this->output)) {
            return $this->output;
        }
        if (is_object($this->output)) {
            $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }

    /**
     * 网络请求
     */
    private function http(): void
    {
        //组织参数
        $strParam = $this->createStrParam();
        $result = file_get_contents($this->api_url . "?{$strParam}");
        $result = json_decode($result, true);
        $this->output = $result;
    }

    /**
     * 组参
     * @return string
     */
    private function createStrParam(): string
    {
        $strParam = '';
        foreach ($this->param as $key => $val) {
            if ($key !== '' && $val !== '') {
                $strParam .= $key . '=' . urlencode($val) . '&';
            }
        }
        return $strParam;
    }
}