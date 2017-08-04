<?php
$access_token = 'OHKWU97ZacrRd5cMpbFSIEBZnYzpLrM+RS31gG/YXdTwxPu7RIt0lVTYOkyTygUsuhg3fDIwK2JIzQWmzLoHPQMeNIDKFFNhNRr69U/AmmRRktl4qxHp2i+9clOfZJRLEBct3d9zPxjQ0/pB1CEo/QdB04t89/1O/w1cDnyilFU=';
//$access_token = '8yzQi0CLRcLihI4wxXjSAciltIbIBtLBiU4LhE8ZdNJ';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			$retext = getmsg();
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $retext
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		}
	}
}
function getmsg(){
	
$contents = file_get_contents('https://api.nicehash.com/api?method=stats.provider.ex&addr=185unAAo6hBigg2rSeUtE7DomPSkqMd3Rf'); //185unAAo6hBigg2rSeUtE7DomPSkqMd3Rf
$enc = json_decode($contents,true);
//print_r($enc);
//echo "<br><br>";
$basecontents = file_get_contents('https://api.nicehash.com/api?method=stats.global.current');
$baseenc = json_decode($basecontents,true);
$pricecontents = file_get_contents('https://bx.in.th/api/');
$priceenc = json_decode($pricecontents,true);
$price = ($priceenc['1']['last_price']);
for ($i=0;$i<=20;$i++){
$bbb = ($enc['result']['current'][$i]['data'][0]['a']);
	if( $bbb != "" ){
		$algo = ($enc['result']['current'][$i]['algo']);
		for ($j=0;$j<=30;$j++){
			if( ($baseenc['result']['stats'][$j]['algo']) == $algo){
			$base = ($baseenc['result']['stats'][$j]['price']);
			}
		}
		$a = ($enc['result']['current'][$i]['name']);
		$c = ($enc['result']['current'][$i]['suffix']).'/s';
		$abc = "Rig status:\r\n".$a.": ".$bbb." ".$c;
		$d = "\r\nProfit: ". round($bbb*$base/1000,6) ." mBTC/Day";
		$e = "\r\nProfit: ". round($price*$bbb*$base/1000000,2) ." THB/Day";
		$f = "\r\n\r\nCurrent BTC: ".($enc['result']['current'][$i]['data'][1]); 
		$g = "\r\nCurrent THB: ". round($price*($enc['result']['current'][$i]['data'][1]),2); 
		$h = "\r\n\r\nTHB/BTC: ".$price; 
	}
}
return $abc.$d.$e.$f.$g.$h;	
}
echo "OK";
