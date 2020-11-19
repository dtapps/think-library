<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddDdkGoodsPromotionUrlGenerateRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(String, "custom_parameters")
	*/
	private $customParameters;

	/**
	* @JsonProperty(Boolean, "generate_mall_collect_coupon")
	*/
	private $generateMallCollectCoupon;

	/**
	* @JsonProperty(Boolean, "generate_qq_app")
	*/
	private $generateQqApp;

	/**
	* @JsonProperty(Boolean, "generate_schema_url")
	*/
	private $generateSchemaUrl;

	/**
	* @JsonProperty(Boolean, "generate_short_url")
	*/
	private $generateShortUrl;

	/**
	* @JsonProperty(Boolean, "generate_weapp_webview")
	*/
	private $generateWeappWebview;

	/**
	* @JsonProperty(Boolean, "generate_weiboapp_webview")
	*/
	private $generateWeiboappWebview;

	/**
	* @JsonProperty(Boolean, "generate_we_app")
	*/
	private $generateWeApp;

	/**
	* @JsonProperty(List<Long>, "goods_id_list")
	*/
	private $goodsIdList;

	/**
	* @JsonProperty(Boolean, "multi_group")
	*/
	private $multiGroup;

	/**
	* @JsonProperty(String, "p_id")
	*/
	private $pId;

	/**
	* @JsonProperty(String, "search_id")
	*/
	private $searchId;

	/**
	* @JsonProperty(Long, "zs_duo_id")
	*/
	private $zsDuoId;

	/**
	* @JsonProperty(List<String>, "room_id_list")
	*/
	private $roomIdList;

	/**
	* @JsonProperty(List<String>, "target_id_list")
	*/
	private $targetIdList;

	/**
	* @JsonProperty(Boolean, "generate_authority_url")
	*/
	private $generateAuthorityUrl;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "custom_parameters", $this->customParameters);
		$this->setUserParam($params, "generate_mall_collect_coupon", $this->generateMallCollectCoupon);
		$this->setUserParam($params, "generate_qq_app", $this->generateQqApp);
		$this->setUserParam($params, "generate_schema_url", $this->generateSchemaUrl);
		$this->setUserParam($params, "generate_short_url", $this->generateShortUrl);
		$this->setUserParam($params, "generate_weapp_webview", $this->generateWeappWebview);
		$this->setUserParam($params, "generate_weiboapp_webview", $this->generateWeiboappWebview);
		$this->setUserParam($params, "generate_we_app", $this->generateWeApp);
		$this->setUserParam($params, "goods_id_list", $this->goodsIdList);
		$this->setUserParam($params, "multi_group", $this->multiGroup);
		$this->setUserParam($params, "p_id", $this->pId);
		$this->setUserParam($params, "search_id", $this->searchId);
		$this->setUserParam($params, "zs_duo_id", $this->zsDuoId);
		$this->setUserParam($params, "room_id_list", $this->roomIdList);
		$this->setUserParam($params, "target_id_list", $this->targetIdList);
		$this->setUserParam($params, "generate_authority_url", $this->generateAuthorityUrl);

	}

	public function getVersion()
	{
		return "V1";
	}

	public function getDataType()
	{
		return "JSON";
	}

	public function getType()
	{
		return "pdd.ddk.goods.promotion.url.generate";
	}

	public function setCustomParameters($customParameters)
	{
		$this->customParameters = $customParameters;
	}

	public function setGenerateMallCollectCoupon($generateMallCollectCoupon)
	{
		$this->generateMallCollectCoupon = $generateMallCollectCoupon;
	}

	public function setGenerateQqApp($generateQqApp)
	{
		$this->generateQqApp = $generateQqApp;
	}

	public function setGenerateSchemaUrl($generateSchemaUrl)
	{
		$this->generateSchemaUrl = $generateSchemaUrl;
	}

	public function setGenerateShortUrl($generateShortUrl)
	{
		$this->generateShortUrl = $generateShortUrl;
	}

	public function setGenerateWeappWebview($generateWeappWebview)
	{
		$this->generateWeappWebview = $generateWeappWebview;
	}

	public function setGenerateWeiboappWebview($generateWeiboappWebview)
	{
		$this->generateWeiboappWebview = $generateWeiboappWebview;
	}

	public function setGenerateWeApp($generateWeApp)
	{
		$this->generateWeApp = $generateWeApp;
	}

	public function setGoodsIdList($goodsIdList)
	{
		$this->goodsIdList = $goodsIdList;
	}

	public function setMultiGroup($multiGroup)
	{
		$this->multiGroup = $multiGroup;
	}

	public function setPId($pId)
	{
		$this->pId = $pId;
	}

	public function setSearchId($searchId)
	{
		$this->searchId = $searchId;
	}

	public function setZsDuoId($zsDuoId)
	{
		$this->zsDuoId = $zsDuoId;
	}

	public function setRoomIdList($roomIdList)
	{
		$this->roomIdList = $roomIdList;
	}

	public function setTargetIdList($targetIdList)
	{
		$this->targetIdList = $targetIdList;
	}

	public function setGenerateAuthorityUrl($generateAuthorityUrl)
	{
		$this->generateAuthorityUrl = $generateAuthorityUrl;
	}

}
