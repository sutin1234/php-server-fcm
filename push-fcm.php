<?php

    // Access Cross Domain
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT');

    // mysqli connected
    $conn = new mysqli("localhost", "thinnyde_ionfcm", "ionfcm", "thinnyde_ionfcm");
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } 

    // Reciever Data From client
    $post = file_get_contents("php://input");
    $arr_employee = json_decode($post);
    $device_token = $arr_employee->device_token;

    // Decode data To Object
    $employees = array(
      "firstName" => $arr_employee->employees->firstName,
      "lastName" => $arr_employee->employees->lastName,
      "pushTime" => date("Y-m-d H:i:s")
    );

    $sql =  "insert into ion2_push values(null,'".$device_token."','".$employees['firstName']."','".$employees['lastName']."','".$employees['pushTime']."',NOW())";
    if($device_token != null || $device_token != ""){
      if(AddToMySQL($conn, $sql) ==  true){
        onPushFCM($device_token, "AAAAYfzDQYY:APA91bFbGv_BR3aDbJJCe5_lrqK-ABM6R3O5B3TgP60YMepdZJ18fXK6cCM8GWEGgqzLHe8GDmVr3RWcmYKU8WN6lxXOoMedudCvmM9cT4ZAkDMaWq8F0dXtyIPq8ofz3VGr-VqVJMiz");
        echo json_encode(
          array(
            "status" => "OK",
            "result" => "Added OK"
        ));
      }else{
        echo json_encode(
          array(
            "status" => "Err",
            "result" => "Added Fail"
          )
        );
      }
    }
  

    // AddTo MySQL function
    function AddToMySQL($conn, $sql){
      $returnData = false;
      if ($conn->query($sql) === TRUE) {
        $returnData =  true;
      } else {
        $returnData = false;
      }
      $conn->close();
      return $returnData;
    }

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
?>
