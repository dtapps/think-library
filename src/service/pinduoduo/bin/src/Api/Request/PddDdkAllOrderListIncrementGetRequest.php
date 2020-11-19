<?php
namespace Com\Pdd\Pop\Sdk\Api\Request;

use Com\Pdd\Pop\Sdk\PopBaseHttpRequest;
use Com\Pdd\Pop\Sdk\PopBaseJsonEntity;

class PddDdkAllOrderListIncrementGetRequest extends PopBaseHttpRequest
{
    public function __construct()
	{

	}
	/**
	* @JsonProperty(Long, "end_update_time")
	*/
	private $endUpdateTime;

	/**
	* @JsonProperty(Integer, "page")
	*/
	private $page;

	/**
	* @JsonProperty(Integer, "page_size")
	*/
	private $pageSize;

	/**
	* @JsonProperty(Long, "start_update_time")
	*/
	private $startUpdateTime;

	/**
	* @JsonProperty(Integer, "query_order_type")
	*/
	private $queryOrderType;

	protected function setUserParams(&$params)
	{
		$this->setUserParam($params, "end_update_time", $this->endUpdateTime);
		$this->setUserParam($params, "page", $this->page);
		$this->setUserParam($params, "page_size", $this->pageSize);
		$this->setUserParam($params, "start_update_time", $this->startUpdateTime);
		$this->setUserParam($params, "query_order_type", $this->queryOrderType);

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
		return "pdd.ddk.all.order.list.increment.get";
	}

	public function setEndUpdateTime($endUpdateTime)
	{
		$this->endUpdateTime = $endUpdateTime;
	}

	public function setPage($page)
	{
		$this->page = $page;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
	}

	public function setStartUpdateTime($startUpdateTime)
	{
		$this->startUpdateTime = $startUpdateTime;
	}

	public function setQueryOrderType($queryOrderType)
	{
		$this->queryOrderType = $queryOrderType;
	}

}
