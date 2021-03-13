<?php
require('config.php');
require('whatsapp.php');

$install = new Whatsapp();
// --------------------------------------------------------------//
// --------------------------------------------------------------//
//  author , ilman sunannudin
// have any project? you can contact me at https://m-pedia.my.id.
// facebook https://facebook.com/ilman.sn
// PLEASE DO NOT DELETE THIS COPYRIGHT IF YOU ARE A HUMAN.
// ------------------------------------------------------------------//
// ------------------------------------------------------------------//

function curl($post, $link = 'http://localhost/v2/send-message')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}


header('content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('whatsapp.txt', '[' . date('Y-m-d H:i:s') . "]\n" . json_encode($data) . "\n\n", FILE_APPEND);
$message = $data['data']['body']; // ini menangkap pesan masuk
$type = $data['type'];
$from = $data['data']['from']; // ini menangkap nomor pengirim pesan
$nomor = preg_replace('/@c.us/', '', $from); // nomor yang sudah di bersihkan, hanya angka

if ($type == 'chat') {
    if (substr($message, 0, 10) == '!infocovid') {
		$api = file_get_contents('https://api.kawalcorona.com/indonesia');
    	$api = json_decode($api, true);
    	$api = $api[0];
    	$pesan = "INDONESIA\n\n";
    	$pesan .= "Total positif: " . $api['positif'] . "\n\n";
    	$pesan .= "Total sembuh: " . $api['sembuh'] . "\n\n";
    	$pesan .= "Total meninggal: " . $api['meninggal'];

    	$result = [
    		'type' => 'message',
    		'data' => [
    			'mode' => 'chat',
    			'pesan' => $pesan
    		]
    	];
    } elseif ($message == 'send') {
    	$pesan = 'https://v16m-default.akamaized.net/181315b87c69ff0298f2d2e842bd345c/60450357/video/tos/alisg/tos-alisg-pve-0037/643fe4aca34743a9b09c72c643484761/?a=0&br=1458&bt=729&cd=0%7C0%7C0&ch=0&cr=0&cs=0&cv=1&dr=0&ds=6&er=&l=2021030710454401023408714112653C73&lr=all&mime_type=video_mp4&pl=0&qs=0&rc=am80azZleG5mMzMzOzgzM0ApZDczZDc4NDxoN2Y2aGY1OWc0ZzYyLW01bTVgLS1iLzRzc141MzY0MGFhNjVfNV5fLS06Yw%3D%3D&vl=&vr=';
    	$result = [
    		'type' => 'message',
    		'data' => [
    			'mode' => 'chat',
    			'pesan' => $pesan
    		]
    	];
    } elseif (substr($message, 0, 12) == '!downloadyt4') {
    	$message = explode(' ', $message);
    	$api = file_get_contents('https://api.xteam.xyz/dl/ytmp4?url='. $message[1] .'&APIKEY=canuts123');
    	$api = json_decode($api, true);
    	$result = [
    		'type' => 'message',
    		'data' => [
    			'mode' => 'chat',
    			'pesan' => $api['url']
    		]
    	];
    } elseif ($message == 'test') {
    	$result = [
    		'type' => 'document',
    		'data' => [
    			'mode' => 'chat',
    			'pesan' => 'sukses'
    		]
    	];
    }
}
print json_encode($result);


// kami akan memberitahu jika update. :)