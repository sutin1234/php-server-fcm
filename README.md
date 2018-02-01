# php-server-fcm
php-server-fcm send push fcm to android ios


# function pushnotification 
function onPushFCM($device_token, $server_key){
        $msg = array(
            'message' => 'ทดสอบการแจ้งข่าวผ่าน Push Notification',
            'title' => 'ทดสอบ',
            'subtitle' => 'หัวข้อรอง',
            'tickerText' => 'ข้อความนี้จะปรากฎบน status bar',
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon'
        );
	      $fields = array(
				    'to'		=> $device_token,
				    'data'	=> $msg
			  );
	      $headers = array(
          'Authorization: key=' . $server_key,
          'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        //echo $result;
    }
