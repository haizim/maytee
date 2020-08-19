<?php
date_default_timezone_set('Asia/Jakarta');

include "part/konek.php";
include "process/message.php"; //Berisi fungsi untuk memproses pesan
include "process/callback.php"; //Berisi fungsi untuk memproses callback dari button


$qc = "select * from info where name='token'";
$runc = mysqli_query($konek, $qc);
$resc = mysqli_fetch_array($runc);

//logging(__LINE__,"token database = ".$resc['val']);

define('BOT_TOKEN', $resc['val']);
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

logging(__LINE__,"-------------------------------------------------------------------------------------------------------------");

    


//logging(__LINE__,"cmd = $cmd");
//logging(__LINE__,"one = $one");
//logging(__LINE__,"two = $two");

if(isset($_POST['cmd'])||isset($_POST['one'])||isset($_POST['two'])){

$cmd = $_POST['cmd'];
$one = $_POST['one'];
$two = $_POST['two'];

if($cmd=='show'){
    $qs = "select * from log where (source='MS' or source='BC') and user='$one'";
    logging(__LINE__,"qs = $qs");
    $gets = mysqli_query($konek, $qs);
    $show = "";
    while ($shw = mysqli_fetch_array($gets)){
        $msgs=explode("][", $shw['value']);
        if(($msgs[0]=="msin")){
            $show = $show."<div class=".$msgs[0]."><p>".$msgs[1]."<small><br/>".$shw['waktu']."</small></p></div>";
        }elseif($msgs[0]=="msout"){
            $show = $show."<div align=right class=".$msgs[0]."><p>".$msgs[1]."<small><br/>".$shw['waktu']."</small></p></div>";        
        }elseif($msgs[0]=="msbc"){
            $show = $show."<div align=right class='msout'><p>".$msgs[1]."<small><br/>".$shw['waktu']."</small></p></div>";        
        }
    }
    $jml = mysqli_num_rows($gets);
    $show = $show."<input type='hidden' id='jml' value=$jml>";
    logging(__LINE__,"show = $show");
    echo $show;
}elseif($cmd=='send'){
    
    sendChat($one, $two);
}elseif($cmd=="showbc"){
    $qbc = "select * from log where source='BC'";
    logging(__LINE__,"qbc = $qbc");
    $getbc = mysqli_query($konek, $qbc);
    $showbc = "";
    while ($shw = mysqli_fetch_array($getbc)){
        $msgs = explode("][", $shw['value']);
        $times = explode(" ",$shw['waktu']);
        $time = $times[1]." ".$times[0];
        $showbc = $showbc."<div class=".$msgs[0]."><p>".$msgs[1]."<br/><i><small>".$time."</small></i></p></div>";
    }
    logging(__LINE__,"showbc = $showbc");
    echo $showbc;
}elseif($cmd=='sendbc'){
    
    sendBC($one);
}elseif($cmd=="checkmsg"){
    $qc = "select * from log where source='MS' and user='$one'";
    //logging(__LINE__,"qs = $qs");
    $getc = mysqli_query($konek, $qc);
    $numc = mysqli_num_rows($getc);
    //logging(__LINE__,"numc = $numc");
    echo $numc;
}elseif($cmd=='cektoken'){
    $infot = cektoken($one);
    if (isset($infot['id'])){
    $info = "Id : <span class='summary'>".$infot['id']."</span><br/>Username : <span class='summary'>".$infot['username']."</span><br/>Display Name : <span class='summary'>".$infot['first_name']."</span><br/><input type='hidden' name='token' value='$one'><input type='hidden' name='id' value='".$infot['id']."'><input type='hidden' name='username' value='".$infot['username']."'><input type='hidden' name='dispname' value='".$infot['first_name']."'><button type='submit' class='btn tmb' value='deploy' name='submit'>Submit</button>";
    }else{
        $info = "<b>$infot</b>";
    }
    echo $info;
}elseif($cmd=="deploy"){
        $link .= "https://"; 
        $link .= $_SERVER['HTTP_HOST']; 
        $link .= $_SERVER['REQUEST_URI'];
        logging(__LINE__,"on deploy >> one = $one // link= $link");
    $dep = deploybot($one,$link);
    echo $dep;
}

}

///////////////////////////////////////////////////////////////////////////////
function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    logging(__LINE__,"apiRequestWebhook : Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    logging(__LINE__,"apiRequestWebhook : Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

//////////////////////////////////////////////////////////////////////

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    logging(__LINE__,"Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    logging(__LINE__,"500 : do not wat to DDOS server if something goes wrong");
    $responses = "500 : do not wat to DDOS server if something goes wrong";
    $response = $responses;
    sleep(10);
    //return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    logging(__LINE__,"Request has failed with error {$response['error_code']}: {$response['description']}");
    
    $responses = "Request has failed with error ".$response['error_code'].": ".$response['description'];
    
    /*if ($http_code == 401) {
      //throw new Exception('Invalid access token provided');
      $responses = 'Invalid access token provided';
    }*/
    $response = $responses;
    //return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      logging(__LINE__,"Request was successful: {$response['description']}");
    }
    $response = $response['result'];
  }
    logging(__LINE__,"response = $response");
    
  return $response;
}

////////////////////////////////////////////////////////////////////////////

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    logging(__LINE__,"apiRequest : Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    logging(__LINE__,"apiRequest : Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

    logging(__LINE__,"url apiRequest >> ".$url);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

//////////////////////////////////////////////////////////////////////////////

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    logging(__LINE__,"apiRequestJson : Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    logging(__LINE__,"apiRequestJson : Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

//////////////////////////////////////////////////////////////////////////////

//////////////LOGGING MULAI////////////////////////////////

function logging($line,$isi){
    $t=time();
    $myfile = fopen("data-log.txt", "a") or die("Unable to open file!");
    $data = fread($myfile,filesize("data-log.txt"));
    $txt = date("Y-m-d H:i:s",$t);
    $txt .= " >> ".$isi." on line ".$line."\n";
    //$txt .= $data."\n";
    fwrite($myfile, $txt);
    fclose($myfile);
}

/////////////////LOGGING SELESAI///////////////////////////

//////////////////////REPLY RESPONSE MULAI/////////////////////////////////////

function replyresponse($no, $chatid){
    include "part/konek.php";
    

    $q = "select * from action where no=$no";
    $jalan = mysqli_query($konek,$q);
    $res = mysqli_fetch_array($jalan);
    $act = json_decode($res['action']);
    $acts = print_r($act,true);
    
    for($m=0;$m<=count($act)-1;$m++){
        $action = $act[$m];
        
        switch($action[0]){
            case "photo" :
                sendPhoto($chatid, $action[1],$action[2]);
                logging(__LINE__,"sendPhoto >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "video" :
                sendVideo($chatid, $action[1],$action[2]);
                logging(__LINE__,"sendVideo >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "audio" :
                sendAudio($chatid, $action[1],$action[2]);
                logging(__LINE__,"sendAudio >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "document" :
                sendDocument($chatid, $action[1],$action[2]);
                logging(__LINE__,"sendDocument >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "location" :
                sendLocation($chatid, $action[1],$action[2]);
                logging(__LINE__,"sendLocation >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "contact" :
                $pn = $action[1];
                sendContact($chatid, "$pn" ,$action[2]);
                logging(__LINE__,"pn data type : ".gettype($pn));
                logging(__LINE__,"sendContact >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "message":
                //apiRequest("sendMessage", array('chat_id' => $chatid, "text" => $action[1]));
                sendReply($chatid, $action[2]);
                logging(__LINE__,"sendMessage >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "button":
                $bn = $action[1];
                $qb = "select * from button where no=$bn";
                $getb = mysqli_query($konek, $qb);
                $cb = mysqli_fetch_array($getb);
                $bcpt = json_decode($cb['caption'],true);
                $bctn = json_decode($cb['content'],true);
                $btype = json_decode($cb['type'],true);
                
                logging(__LINE__,"bcpt >> ".print_r($bcpt,true));
                logging(__LINE__,"bctn >> ".print_r($bctn,true));
                logging(__LINE__,"btype >> ".print_r($btype,true));
                button($chatid, $action[2], $bcpt, $bctn,$btype);
                logging(__LINE__,"button >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
        }
        
        //apiRequest("sendMessage", array('chat_id' => $chatid, "text" => $action[0]." ".(count($act)-1)));
    }
    
    
}

//////////////////////REPLY RESPONSE SELESAI////////////////////////////////////


//////////////////////////////sendReply Mulai///////////////////////////////////
function sendReply($chat_id, $text){
	include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $query = "insert into log(user, source, value) value ($chat_id,'MA','msans][$text')";
	logging(__LINE__,"query balas pesan : $query");
	$result = mysqli_query($konek, $query);
	
	
	
		apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
}
//////////////////////////////sendReply Selesai/////////////////////////////////

//////////////////////////////sendChat Mulai///////////////////////////////////

function sendChat($chat_id, $text){
	include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $action = explode("|mw|",$text);
    $admin = "\n-mimin";
    
    switch($action[0]){
            case "photo" :
                sendPhoto($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendPhoto >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "video" :
                sendVideo($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendVideo >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "audio" :
                sendAudio($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendAudio >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "document" :
                sendDocument($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendDocument >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "location" :
                sendLocation($chat_id, $action[1],$action[2]);
                logging(__LINE__,"sendLocation >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                $one = "Latitude";
                $two = "Longitude";
                break;
            case "contact" :
                $pn = $action[1];
                sendContact($chat_id, "$pn" ,$action[2]);
                //logging(__LINE__,"pn data type : ".gettype($pn));
                logging(__LINE__,"sendContact >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                $one = "Phone Number";
                $two = "Name";
                break;
            case "message":
                //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $action[1]));
                sendReply($chat_id, $action[2].$admin);
                logging(__LINE__,"sendMessage >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
        }
    
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
    
    $text = str_replace("\n","<br/>",$text);
    
    if($action[0]=="message"){
        $save = $action[2];
    }elseif($action[0]=="photo"||$action[0]=="video"||$action[0]=="audio"||$action[0]=="document"){
        $save = $action[0]." : <br/>Attachment : <a href=".$action[1].">".$action[1]."</a><br/>Caption : ".$action[2];
    }else{
        $save = $action[0]." : <br/>$one : ".$action[1]."<br/>$two : ".$action[2];
    }
    
    $query = "insert into log(user, source, value) value ($chat_id,'MS','msout][$save')";
	logging(__LINE__,"query balas direct message : $query");
	$result = mysqli_query($konek, $query);
	
	$t=time();
    $tm = date("Y-m-d H:i:s",$t);
	$qupdate = "update user set last_dm='$tm' where id=$chat_id;";
	logging(__LINE__,"query qupdate : $qupdate");
	$resupdate = mysqli_query($konek, $qupdate);
}

//////////////////////////////////////////////////////////////////////////
function sendBC($text){
	include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $qr = "select * from user";
    $getr = mysqli_query($konek, $qr);
    
    $action = explode("|mw|",$text);
    $admin = "\n-BC";
    
    while($hasilr = mysqli_fetch_array($getr)){
        
        $chat_id = $hasilr['id'];
        
        switch($action[0]){
            case "photo" :
                sendPhoto($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendPhoto >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "video" :
                sendVideo($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendVideo >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "audio" :
                sendAudio($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendAudio >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "document" :
                sendDocument($chat_id, $action[1],$action[2].$admin);
                logging(__LINE__,"sendDocument >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
            case "location" :
                sendLocation($chat_id, $action[1],$action[2]);
                logging(__LINE__,"sendLocation >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                $one = "Latitude";
                $two = "Longitude";
                break;
            case "contact" :
                $pn = $action[1];
                sendContact($chat_id, "$pn" ,$action[2]);
                //logging(__LINE__,"pn data type : ".gettype($pn));
                logging(__LINE__,"sendContact >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                $one = "Phone Number";
                $two = "Name";
                break;
            case "message":
                //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $action[1]));
                sendReply($chat_id, $action[2].$admin);
                logging(__LINE__,"sendMessage >> act 1 = ".$action[1]." // act 2 = ".$action[2]);
                break;
        }
        
        //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
        logging(__LINE__,"send broadcast to $chat_id");
    }
    $text = str_replace("\n","<br/>",$text);
    
    
    if($action[0]=="message"){
        $save = $action[2];
    }elseif($action[0]=="photo"||$action[0]=="video"||$action[0]=="audio"||$action[0]=="document"){
        $save = $action[0]." : <br/>Attachment : <a href=".$action[1].">".$action[1]."</a><br/>Caption : ".$action[2];
    }else{
        $save = $action[0]." : <br/>$one : ".$action[1]."<br/>$two : ".$action[2];
    }
    $query = "insert into log(user, source, value) value ($chat_id,'BC','msbc][$save')";
	logging(__LINE__,"query balas direct message : $query");
	$result = mysqli_query($konek, $query);
}

//////////////////////////////////////////////////////////////////////////

function button($chat_id, $reply, $caption, $content,$type){
	$keyboard = ['inline_keyboard'=>[]];
	$isi = [];
	
	for($w=0; $w<=count($caption);$w++){
	    for($m=0; $m<count($caption[$w]); $m++){
		    $tipe = $type[$w][$m];
		    $isi['text'] = $caption[$w][$m];
		    $isi[$tipe] = $content[$w][$m];
		    $jkl = print_r($isi, TRUE);
		    logging(__LINE__,"isi baris $w ke-$m = ".$jkl);
		    $keyboard['inline_keyboard'][($w-1)][$m] = $isi;
		    $isi = [];
	    }
	}
    $prnt = print_r($keyboard, TRUE);
    logging(__LINE__,"inline keyboard buttons : $prnt");
    logging(__LINE__,"tipe data keyboard button : ".gettype($keyboard));
    
    
    $postfields = array('chat_id' => $chat_id,'parse_mode' => 'html',"text" => $reply, 'reply_markup' => json_encode($keyboard));
	$prntt = print_r($postfields, TRUE);
    logging(__LINE__,"postfields json : $prntt");
	apiRequestJson("sendMessage", $postfields);
    
    
}

///////////////////////////////////////////////////////////////////////////

function tombol($chat_id){
	$reply = "<b>Siapa Presiden Pilihan kamu?</b>";
	
	$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => 'Faith', 'callback_data' => 'pres 1-a'],
            ['text' => 'Ipin', 'callback_data' => 'pres 1-b']
        ],
        [
            ['text' => 'Cyva', 'callback_data' => 'pres 2-a'],
            ['text' => 'Darril', 'callback_data' => 'pres 2-b']
        ],
        [
            ['text' => 'Golput', 'callback_data' => 'pres 0']
        ]
    ]
];

    $prnt = print_r($keyboard, TRUE);
    logging(__LINE__,"inline keyboard button tombol : $prnt");
    logging(__LINE__,"tipe data keyboard tombol : ".gettype($keyboard));
    
    $postfields = array('chat_id'=> $chat_id,'parse_mode' => 'html', "text" => $reply, 'reply_markup' => json_encode($keyboard));
    $prntt = print_r($postfields, TRUE);
    logging(__LINE__,"postfields json : $prntt");
	apiRequestJson("sendMessage", $postfields);
    
    
}

//////////////////////////////////////////////////////////////////////////

function sendPhoto($chat_id, $source,$caption){
	include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $query = "insert into log(user, source, value) value ($chat_id,'PT','$source][$caption')";
	logging(__LINE__,"query balas photo : $query");
	$result = mysqli_query($konek, $query);
	
		apiRequest("sendPhoto", array('chat_id' => $chat_id, 'photo' => $source, 'caption' => $caption));
}

///////////////////////////////////////////////////////////////////////////

function sendVideo($chat_id, $source,$caption){
    include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $query = "insert into log(user, source, value) value ($chat_id,'VD','$source][$caption')";
	logging(__LINE__,"query balas video : $query");
	$result = mysqli_query($konek, $query);
	
		apiRequest("sendVideo", array('chat_id' => $chat_id, 'video' => $source, 'caption' => $caption));
}

///////////////////////////////////////////////////////////////////////////

function sendAudio($chat_id, $source,$caption){
    include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $query = "insert into log(user, source, value) value ($chat_id,'AU','$source][$caption')";
	logging(__LINE__,"query balas audio : $query");
	$result = mysqli_query($konek, $query);
	
		apiRequest("sendAudio", array('chat_id' => $chat_id, 'audio' => $source, 'caption' => $caption));
}

///////////////////////////////////////////////////////////////////////////

function sendDocument($chat_id, $source, $caption){
    include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $query = "insert into log(user, source, value) value ($chat_id,'DC','$source][$caption')";
	logging(__LINE__,"query balas document : $query");
	$result = mysqli_query($konek, $query);
	
		apiRequest("sendDocument", array('chat_id' => $chat_id, 'document' => $source, 'caption' => $caption));
}

///////////////////////////////////////////////////////////////////////////

function sendLocation($chat_id, $latitude, $longitude){
    include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $query = "insert into log(user, source, value) value ($chat_id,'LC','$latitude][$longitude')";
	logging(__LINE__,"query balas location : $query");
	$result = mysqli_query($konek, $query);
	
		apiRequest("sendLocation", array('chat_id' => $chat_id, 'latitude' => $latitude, 'longitude' => $longitude));
}

///////////////////////////////////////////////////////////////////////////

function sendPoll($chat_id, $question, $options){
    
		apiRequest("sendPoll", array('chat_id' => $chat_id, 'question' => $question, 'options' => $options));
}

///////////////////////////////////////////////////////////////////////////

function sendContact($chat_id, $phone_number, $first_name){
    include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());
    
    $query = "insert into log(user, source, value) value ($chat_id,'CT','$phone_number][$first_name')";
	logging(__LINE__,"query balas kontak : $query");
	$result = mysqli_query($konek, $query);
	
		apiRequest("sendContact", array('chat_id' => $chat_id, 'phone_number' => $phone_number, 'first_name' => $first_name));
}

///////////////////////////////////////////////////////////////////////////

function getMe(){
		$me = apiRequest("getMe",array());
		$hasil = print_r($me,true);
		logging ("hasil getme : $hasil");
}

///////////////////////////////////////////////////////////////////////////

function wordcount($kalimat){
	include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());

    $text = strtolower($kalimat);
    $textur = preg_replace('/\s\s+/', ' ', $text);
    $kata = explode(' ',$textur);

    foreach ($kata as $word) {
    
    	$sql = "SELECT * from word where kata='$word'";
		$result = mysqli_query($konek, $sql);
        logging(__LINE__,"kata = $word");
		if(mysqli_num_rows($result)>0){
		    
			$query = "UPDATE word SET jumlah=jumlah+1 WHERE kata='$word'";
			logging(__LINE__,"query kata ada : $query");
		}else {
			$query = "insert into word(kata) value ('$word')";
			logging(__LINE__,"query kata tidak ada : $query");
		}
		$result = mysqli_query($konek, $query);
	}
}

//////////////////////////////////////////////////////////////////////////

function adduser($id,$name){
	include "part/konek.php";
    mysqli_select_db($konek, "tagarakc_tcms_galih");
    logging(__LINE__,"konek error : ".mysqli_connect_error());

    $sql = "SELECT * from user where id='$id'";
	$result = mysqli_query($konek, $sql);

	if(mysqli_num_rows($result)>0){
		$query = "UPDATE user SET nama='$name' WHERE id=$id";
		logging(__LINE__,"query ID ada : $query");
	}else {
		$query = "insert into user(id,nama) value ($id,'$name')";
		logging(__LINE__,"query ID tidak ada : $query");
	}
	$result = mysqli_query($konek, $query);
}

//////////////////////////////////////////////////////////////////////////

function cektoken($token) {

  $API_URL = 'https://api.telegram.org/bot'.$token.'/'; 
  $method = "getMe";
  $parameters = array();

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = $API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  $hasil = exec_curl_request($handle);
  
  if($hasil['id']){
    $lihat = print_r($hasil,true);
  }else{
      $lihat = $hasil;
  }
  logging(__LINE__,"hasil cek token : $lihat");
  return $hasil;
}

/////////////////////////////////////////////////////////////////////////
function deploybot($token,$loc) {

  $API_URL = 'https://api.telegram.org/bot'.$token.'/'; 
  $method = "setWebhook";
  $parameters = array('url'=>$loc);

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = $API_URL.$method.'?url='.$loc;
  
  logging(__LINE__,"url fungsi deploybot = $url");

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  $hasil = exec_curl_request($handle);
  
  if($hasil=TRUE){
    $lihat = "Bot deployed on $loc";
  }else{
      $lihat = "Sorry, Failed to deploy bot";
  }
  logging(__LINE__,"hasil deploy bot : $lihat");
  return $lihat;
}

/////////////////////////////////////////////////////////////////////////

define('WEBHOOK_URL', 'https://haizim.one/iseng/bms/bot.php');

/*
if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}
*/

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

$upd = print_r($update,true);
logging(__LINE__,"isi update : $upd");

if (isset($update["message"])) {
  processMessage($update["message"]);
}
elseif(isset($update["callback_query"])){
    processCallback($update["callback_query"]["message"],$update["callback_query"]["data"]);
}


