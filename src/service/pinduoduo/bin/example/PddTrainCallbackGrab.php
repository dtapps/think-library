<?php
/**
 * 示例接口名称：pdd.train.callback.grab
*/
require_once dirname(__FILE__).'/Config.php';
require_once dirname(__FILE__)."/../vendor/autoload.php";

use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddTrainCallbackGrabRequest;
$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);

$request = new PddTrainCallbackGrabRequest();

$request->setArriveStation('str');
$request->setArriveStationCode('str');
$request->setArriveTime('str');
$request->setCode(1);
$request->setCrhOrderId('str');
$request->setDepartStation('str');
$request->setDepartStationCode('str');
$request->setDepartTime('str');
$request->setMsg('str');
$request->setOrderId('str');
$request->setOrderTicketPrice(1);
$request->setOrderTime('str');
$request->setPassengers();
$request->setPddOrderId('str');
$request->setTicketNum(1);
$request->setTrainDate('str');
$request->setTrainNo('str');
$request->setTravelTime('str');
$request->setVendorTime('str');
$request->setEndTime('str');
$request->setChannel(1);
$request->setIdCardCheckIn(1);
$request->setGateNo('str');
$request->setDistance('str');
try{
	$response = $client->syncInvoke($request);
} catch(Com\Pdd\Pop\Sdk\PopHttpException $e){
	echo $e->getMessage();
	exit;
}
$content = $response->getContent();
if(isset($content['error_response'])){
	echo "异常返回";
}
echo json_encode($content,JSON_UNESCAPED_UNICODE);