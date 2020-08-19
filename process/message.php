<?
//ini untuk memproses pesan masuk
function processMessage($message) {
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  $first =  $message["from"]["first_name"];
  $last =  $message["from"]["last_name"];
  
  include "part/konek.php";
    
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
  $count = 0;
  
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    $kal = strtolower($text);
    
    
    wordcount($kal);
    
    logging(__LINE__,"kal masuk : $kal");
    $query = "insert into log(`user`, `source`, `value`) value ('$chat_id','M','$text')";
    logging(__LINE__,"query : $query");
    $input = mysqli_query($konek,$query);
    logging(__LINE__,"Error message: ". mysqli_error($konek));
    
    
    if ($input){
        if($kal=="/start"){ //jika chat berisi "/start"
            logging(__LINE__,"/start dipanggil");
            $qstr = "select * from command where name='start'";
            $rstr = mysqli_query($konek, $qstr);
            $gstr = mysqli_fetch_array($rstr);
            $jstr = json_decode($gstr['action']);
            $arstr = print_r($jstr,true);
            logging(__LINE__,"jstr = $arstr");
            if($jstr[0]=="a"){
                logging(__LINE__,"/start isinya action >> ".$jstr[1]);
                replyresponse($jstr[1], $chat_id);
            }elseif($jstr[0]=="t"){
                logging(__LINE__,"/start isinya text >> ".$jstr[1]);
                sendReply($chat_id, $jstr[1]);
            }
            $nama = "$first $last";
            logging(__LINE__,"nama : $nama");
            adduser($chat_id,$nama);
            $count=58;
        
        }else{ //mulai pencocokan
            $word = explode(" ",strtolower($text)); //pisahkan tiap kata
            for ($m=0;$m<=count($word)-1;$m++){
                $wrd = $word[$m]; //ambil satu kata
        	    $q = "select action from command where type='message' and keyword like '% $wrd %'"; //lakukan string matching terhadap kata
        	    $get_act = mysqli_query($konek, $q);
        	    $action = mysqli_fetch_array($get_act);
        	    $act = json_decode($action['action']); // array berisi action set yang menjadi respon
        	    $acts = print_r($act,true);
        	    
        	    if(count($act)>0){ //jika kata sesuai dengan keyword yang ada di database
        	        $qclog = "insert into `command-log`(user,command) value ('$chat_id','$wrd')";
        	        logging(__LINE__,"insert command-log message query : $qclog");
        	        $count++;
        	        $iclog = mysqli_query($konek,$qclog);
        	        
        	        for($w=0;$w<=count($act)-1;$w++){
        	            replyresponse($act[$w], $chat_id); // eksekusi tiap action set
        	        }
        	        
        	    }
        	    
        }
        }
        if ($count<1){ //jika tidak ditemukan kata yang sesuai di dalam pesan
            $query = "insert into log(user, source, value) value ($chat_id,'MS','msin][$text')";
            $result = mysqli_query($konek, $query);
              $qdef = "select * from command where name='default'";
            $rdef = mysqli_query($konek, $qdef);
            $gdef = mysqli_fetch_array($rdef);
            $jdef = json_decode($gdef['action']);
            $prntd = print_r($def,true);
            logging(__LINE__,"isi def = $def");
            if($jdef[0]=="a"){
                replyresponse($jdef[1], $chat_id);
            }elseif($jdef[0]=="t"){
                sendReply($chat_id, $jdef[1]."\n-bot");
            }
                $t=time();
                $tm = date("Y-m-d H:i:s",$t);
	            $qupdate = "update user set last_dm='$tm' where id=$chat_id;";
	            logging(__LINE__,"query qupdate : $qupdate");
	            $resupdate = mysqli_query($konek, $qupdate);
              
        }
    }    
    
    
  } else {
    
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}