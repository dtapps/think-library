<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddDdkOauthGoodsRecommendGetRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Integer, "channel_type")
	*/
	private $channelType;

	/**
	* @JsonProperty(String, "custom_parameters")
	*/
	private $customParameters;

	/**
	* @JsonProperty(Integer, "limit")
	*/
	private $limit;

	/**
	* @JsonProperty(String, "list_id")
	*/
	private $listId;

	/**
	* @JsonProperty(Integer, "offset")
	*/
	private $offset;

	/**
	* @JsonProperty(String, "pid")
	*/
	private $pid;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "channel_type", $this->channelType);
		$this->setUserParam($params, "custom_parameters", $this->customParameters);
		$this->setUserParam($params, "limit", $this->limit);
		$this->setUserParam($params, "list_id", $this->listId);
		$this->setUserParam($params, "offset", $this->offset);
		$this->setUserParam($params, "pid", $this->pid);

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
		return "pdd.ddk.oauth.goods.recommend.get";
	}

	public function setChannelType($channelType)
	{
		$this->channelType = $channelType;
	}

	public function setCustomParameters($customParameters)
	{
		$this->customParameters = $customParameters;
	}

	public function setLimit($limit)
	{
		$this->limit = $limit;
	}

	public function setListId($listId)
	{
		$this->listId = $listId;
	}

	public function setOffset($offset)
	{
		$this->offset = $offset;
	}

	public function setPid($pid)
	{
		$this->pid = $pid;
	}

}
