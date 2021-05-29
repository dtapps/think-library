<?php
/**
 * 示例接口名称：pdd.ticket.goods.upload
*/
require_once dirname(__FILE__).'/Config.php';
require_once dirname(__FILE__)."/../vendor/autoload.php";

use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddTicketGoodsUploadRequest;
$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);

$request = new PddTicketGoodsUploadRequest();

$request->setCarouselGallery(array('str'));
$request->setCarouselVideo();
$request->setCatId(1);
$request->setCodeMode(1);
$request->setDetailGallery(array('str'));
$request->setGoodsCommitId(1);
$request->setGoodsDesc('str');
$request->setGoodsId(1);
$request->setGoodsName('str');
$request->setGoodsProperties();
$request->setIsSubmit(1);
$request->setMarketPrice(1);
$request->setOutGoodsSn('str');
$request->setReserveLimitRule('str');
$request->setSkuList();
$request->setSkuType(1);
$request->setSyncGoodsOperate(1);
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