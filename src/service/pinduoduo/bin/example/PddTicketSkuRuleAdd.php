<?php
/**
 * 示例接口名称：pdd.ticket.sku.rule.add
*/
require_once dirname(__FILE__).'/Config.php';
require_once dirname(__FILE__)."/../vendor/autoload.php";

use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddTicketSkuRuleAddRequest;
$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);

$request = new PddTicketSkuRuleAddRequest();

$request->setBookerInfoLimitation();
$request->setBookingNotice();
$request->setOrderLimitation();
$request->setOutRuleId('str');
$request->setProviderContactInfo();
$request->setRefundLimitations();
$request->setRuleName('str');
$request->setTravelerInfoLimitation();
$request->setValidLimitation();
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