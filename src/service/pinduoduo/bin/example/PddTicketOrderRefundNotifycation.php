<?php
/**
 * 示例接口名称：pdd.ticket.order.refund.notifycation
*/
require_once dirname(__FILE__).'/Config.php';
require_once dirname(__FILE__)."/../vendor/autoload.php";

use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddTicketOrderRefundNotifycationRequest;
$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);

$request = new PddTicketOrderRefundNotifycationRequest();

$request->setOrderNo('str');
$request->setRefundAmount(1);
$request->setRejectReason('str');
$request->setSerialNo('str');
$request->setStatus(1);
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