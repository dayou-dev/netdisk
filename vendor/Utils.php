<?php
include_once 'autoload.php';

class Utils {

    private static $broker = "tcp://47.103.155.7:61613";
	private static $user = "frogbt";
	private static $password = "123qwe";

	private static $topic_binance = "app.service.binance";
	private static $topic_task = "app.service.task";
    private static $topic_wechat = "app.service.wechat";
    
	private static $action_exchange_order = 0;
	private static $action_exchange_sync = 1; 

	private static $action_wallet_sync = 1; 

	public static function sendWechatMessage($records) {
		$client = new \Stomp\Client(self::$broker);
		$client->setLogin(self::$user,self::$password);
		$client->connect();
		foreach($records as $key=>$record){
			$json = json_encode($record);
			$map = array("action"=>$action,"body"=> $json);
			$message = json_encode($map);
			$client->send("/topic/" . self::$topic_wechat, $message);
		}
		$client->disconnect();
	}
	// 发送闪兑下单消息
	public static function sendExchangeOrderMessage($json) {
		self::sendExchangeMessage(self::$action_exchange_order,$json);
	}
	// 闪兑队列消息
	public static function sendExchangeMessage($action,$json) {
		self::sendTextMessage(self::$topic_binance,$action,$json);
	}

	// 发送钱包消息 
	public static function sendWalletMessage($json) {
		self::sendTaskMessage(self::$action_wallet_sync,$json);
	}
	// 作业队列消息
	public static function sendTaskMessage($action,$json) {
		self::sendTextMessage(self::$topic_task,$action,$json);
	}

	public static function sendTextMessage($topic,$action,$json) {
		$map = array("action"=>$action,"body"=> $json);
		$message = json_encode($map);
		self::sendMessage($topic,$message);
	}

	public static function sendMessage($topic,$message) {
		$client = new \Stomp\Client(self::$broker);
		$client->setLogin(self::$user,self::$password);
		$client->connect();
		$client->send("/topic/" . $topic, $message);
		$client->disconnect();
	}

	public static function createTronWallet() {
		try {
			$tron = new \IEXBase\TronAPI\Tron();
			$generateAddress = $tron->generateAddress();  

			$isValid = $tron->isAddress($generateAddress->getAddress());
			if($isValid) {
				echo 'Is Validate: '. $isValid;
			}
			
			return $generateAddress;
		} catch (\IEXBase\TronAPI\Exception\TronException $e) {
			echo $e->getMessage();
		}
		return null;
	}
}
?>