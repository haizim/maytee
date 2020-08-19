<?
//in untuk memproses callback yang dikirim button
function processCallback($message, $data) {
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  
  $isi = ["hai","you","php","i","u"];
  $ada = []; 
  
  $count = 0;
  
  if (isset($data)) {
    // incoming text message
    $text = $data;
    $kal = " ".strtolower($text);
    
    wordcount($kal);
    
    logging(__LINE__,"kal masuk : $kal");
    
    include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    $query = "insert into log(`user`, `source`, `value`) value ('$chat_id','C','$text')";
    logging(__LINE__,"query : $query");
    $input = mysqli_query($konek,$query);
    logging(__LINE__,"Error message: ". mysqli_error($konek));
    
    //logging(__LINE__,"log masuk");
    
    if ($input){
        //for ($m=0;$m<=count($word)-1;$m++){
           // $wrd = $word[$m];
          $q = "select action from command where keyword like '%$text%'"; // matching isi callbak
          $get_act = mysqli_query($konek, $q);
          $action = mysqli_fetch_array($get_act);
          $act = json_decode($action['action']); // array berisi action set yang menjadi respon
          $acts = print_r($act,true);
          
          if(count($act)>0){//jika kata sesuai dengan keyword yang ada di database
              $qclog = "insert into `command-log`(user,command) value ('$chat_id','$text')";
              logging(__LINE__,"insert command-log callback query : $qclog");
              $count++;
              $iclog = mysqli_query($konek,$qclog);
          for($w=0;$w<=count($act)-1;$w++){
              replyresponse($act[$w], $chat_id); // eksekusi tiap action set
          }
          }
          
          //apiRequest("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => "$acts"));
          
        //}
        if ($count<1){ 
            apiRequest("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => "maaf, jawaban belum tersedia yaa kak"));
              apiRequest("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => "Silahkan tunggu jawaban mimin yaa kak"));
        }
    }
    
    
    
  } else {
    
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}