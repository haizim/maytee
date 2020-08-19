<?php
date_default_timezone_set('Asia/Jakarta');
//include "../pengunjung.php";

include "part/konek.php";

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
  //echo("<br>Error description: " . mysqli_error($konek));


$qc = "select * from info where name='token'";
$runc = mysqli_query($konek, $qc);
$resc = mysqli_fetch_array($runc);

$path = $_SERVER['REQUEST_URI'];
$loc = explode("/",$path);
$last = count($loc);
$here = $loc[$last-1];
//print_r($loc);
//echo "<hr/>".$last;
//echo "<hr/>".$here;
if($here!="settings.php"){
    if($resc['val']==''){
  header("location: settings.php");
    }
}

$h = rand(0,255);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Iseng-isengan Haizim">

  <title><?php echo $judul; ?> | Telegram Chatbot Management System</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
   <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
   
   <!-- chart.js-->
   <!--script src="chartjs/dist/Chart.js"></script-->
<script>
    //var myChart = new Chart(ctx, {...});
</script>

  
<style>
*{
    transition: all 0.5s ease-in-out;
}
**{
    transition: all 0.5s ease-in-out;
}
***{
    transition: all 0.5s ease-in-out;
}
****{
    transition: all 0.5s ease-in-out;
}
*****{
    transition: all 0.5s ease-in-out;
}
******{
    transition: all 0.5s ease-in-out;
}
*******{
    transition: all 0.5s ease-in-out;
}
********{
    transition: all 0.5s ease-in-out;
}
*********{
    transition: all 0.5s ease-in-out;
}
**********{
    transition: all 0.5s ease-in-out;
}  
html, body{
    background-color:#fffae4;
    width: 100%;
  height: 100%;
  margin: 0px;
  padding: 0px;
  scroll-behavior: smooth;
}
h1, h2, h3, h4, h5, h6, h7, h8, h9{
    font-weight:bold;
}
a{
    color : #ffd42a;
}
a:hover, a:focus{
    color : #0055d4;
}
a:active{
    color : #afc6e9;
}
.samping{
    /*background-image : linear-gradient(hsl(<?php echo $h;?>,34%,60%),hsl(<?php echo $h;?>,34%,22%));*/
    background: rgb(0,34,85);
    background: linear-gradient(#002255, #00317c);
    color: #fff;
    font-weight: 600;
    padding: 2em 1.5em;
    min-height: 100vh;
    border-radius: 15px;
    position: sticky;
    box-shadow: 0px 0px 15px rgba(0,34,85,.5);
    word-wrap: break-word;
    z-index: 1900;
    height: 100%;
    margin: 15px 10px;
}
.samping hr{
    border-top: 1px solid #ffd42a;
}
.mn{
    padding: 5px 10px;
    color: #ffd42a;
    width: 100%;
    margin: 5px 0px;
}
.mn:focus, .mn:hover, .mn:active{
    background-color: #ffd42a;
    color: #025;
    box-shadow: inset 5px 5px 8px rgba(170, 136, 1, 0.85);
    width: 118%;
    border-radius: 5px 0 0 5px;
}
.isi{
    padding: 20px 10%;
    color : #002255;
}
.kontener{
    background-color: #fff;
    color: #002255;
    border-radius: 15px;
    box-shadow: 8px 8px 10px #f5de86;
    width: 100%;
    padding: 5% 10%;
}
.atas{
    background-color:#fff;
    color : hsl(<?php echo $h;?>,34%,40%);
    font-weight : 600;
    box-shadow : 0px 0px 5px #1119;
    visibility:collapse;
    padding: 10px 10%;
}
.navbar-light .navbar-nav .nav-link {
    color : hsl(<?php echo $h;?>,34%,60%);
}
.tm {
    color : hsl(<?php echo $h;?>,34%,40%);
}
.tmb{
    background-color: #ffd42a;
    color: #002255;
    font-weight: 600;
    margin: 3px;
}
.tmbx{
    color: #ffd42a;
    background-color: #002255;
    font-weight: 600;
    margin: 3px;
}
.tmb-{
    color : #ffd42a;
    background-color:#fff;
    border : 1px solid #ffd42a;
    font-weight:600;
    margin : 3px;
}
.fa:hover, .fa:focus, .fa:active{
    opacity: 0.4;
}
.fas:hover, .fas:focus, .fas:active{
    opacity: 0.4;
}
.kol{
    padding:5px;
    
}
.cel{
    border-radius: 2.5px;
    border: 1px solid #002255;
    padding: .5em;
    word-wrap: break-word;
}
.summary{
    background-color: #ffeeaa;
    padding: 2px 7px;
    margin: 2px;
    color: #0055d4;
    border-radius: 2.5px;
}
.summary-{
    color: #ffeeaa;
    padding: 2px 7px;
    margin: 2px;
    background-color: #0055d4;
    border-radius: 2.5px;
}
.circle{
    background-color: #002255;
    padding: 5px;
    border-radius: 50%;
    color: #fff;
}
.btn-lbl{
    padding: 10px;
    color: #ffd42a;
    background-color: #fff;
    border: 1px solid #ffd42a;
    font-weight: 600;
}
.btn-lbl:hover, .btn-lbl input[type="radio"]:checked + .btn-lbl,.btn-lbl:focus,.btn-lbl.active, .btn-lbl:active{
    background-color: #ffd42a;
    color: #002255;
    font-weight: 600;
}
.fix-bawah{
    border-top : 3px solid hsl(<?php echo $h;?>,34%,40%);
    background-color : hsl(<?php echo $h;?>,34%,90%);
    padding : 15px;
    width:100vw;
    position:fixed;
    bottom:0;
    right:0;
    left:0;
    padding: 15px 5vw 15px 20vw;
}
.msin p{
    background-color: hsl(<?php echo $h;?>,34%,95%);;
    color : hsl(<?php echo $h;?>,34%,40%);
    border-radius : 15px 15px 15px 0px;
    border : 1px solid hsl(<?php echo $h;?>,34%,40%);
    padding:5px 10px;
    max-width : 90%;
    width : max-content;
    line-height: 1em;
}

.msout p{
    color:#fff;
    background-color : hsl(<?php echo $h;?>,34%,40%);
    border-radius : 15px 15px 0px 15px;
    padding:5px 10px;
    max-width : 90%;
    width : max-content;
    line-height: 1em;
}
.msbc p{
    color:#fff;
    background-color : hsl(<?php echo $h;?>,34%,40%);
    border-radius : 0px 0px 25px 25px;
    padding:10px;
    text-align: center;
}
.msbc a{
    color : hsl(<?php echo (255-$h);?>,25%,58%);
}
.msbc a:hover{
    color : hsl(<?php echo $h;?>,25%,58%);
}
#bawah{
    height:7vw;
}
small{
    opacity: 0.6;
    font-size: 70%;
}
.infot{
    background-color: #afc6e9;
    padding : 10px;
    border-radius : 2.5px;
    margin:15px;
    line-height : 2em;
    font-weight: bold;
    text-align: center;
    width:90%;
}
.total{
    /*margin:10px;*/
    padding:20px 20px; 
    border-radius : 2.5px;
    height:90%;
    background: rgb(255,212,42);
    background: linear-gradient(135deg, rgb(255, 212, 42) 25%, rgb(251, 228, 141) 100%); 
    color : #fff;
    box-shadow : inset 8.5px 8.5px 8.5px #d9b626;
    font-size : 1.3em;
    line-height: 1em;
    word-wrap:break-word;
}
.total h1{
    font-weight : bolder;
    line-height: 1em;
    color : #002255;
}
.total p{
    opacity : 80%;
    margin-bottom: 0.5rem;
}
.top5{
    margin:10px 0px;
}
.top5 h2{
    font-weight:bold;
}
.top5 hr{
    margin-top : 0.5em;
    margin-bottom : 0.5em;
}


@media screen and (max-width: 800px) {
    .samping{
  visibility:collapse;
  height:30px;
  width:0px;
  overflow:hidden;
    }
    .atas{
  visibility:visible;
    }
    .isi{
  padding:40% 10%;
    }
    .fix-bawah{
  padding:20px 15px;
    }
    #bawah{
    height:0;
    }
    .dash{
    overflow:scroll;
    flex-wrap: nowrap;
    }
}
  </style>
  </head>
  <body>

<nav class="navbar navbar-expand-md navbar-light fixed-top atas">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="index.php"><h5 style="font-weight:800" class="tm">Telegram<br/>Chatbot<br/>Management<br/>System</h5></a>
  
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav" style="height:100vh;">
  <li class="nav-item">
    <hr/>
    </li>
<li class="nav-item">
  <a href="index.php" class="nav-link tm"><i class='fas fa-couch'></i> Dashboard </a>
</li>
<li class="nav-item">
  <a href="button.php" class="nav-link tm"><i class='fas fa-grip-horizontal'></i> Button Set </a>
</li>
<li class="nav-item">
  <a class="nav-link tm" href="action.php"><i class='fas fa-child'></i> Action Set </a>
</li>
<li class="nav-item">
  <a class="nav-link tm" href="command.php"><i class='fas fa-robot'></i> Command </a>
</li> <li class="nav-item">
  <a class="nav-link tm" href="message.php"><i class='fas fa-comments'></i> Direct Message </a>
</li><li class="nav-item">
  <a class="nav-link tm" href="broadcast.php"><i class='fas fa-broadcast-tower'></i> Broadcast </a>
</li><!--li class="nav-item">
  <a class="nav-link tm" href="analytics.php"><i class='far fa-chart-bar'></i> Analytics </a>
</li--><li class="nav-item">
  <a class="nav-link tm" href="settings.php"><i class='fas fa-cog'></i> Setting </a>
</li><li class="nav-item">
    <hr/>
    <p style="font-size:small">© 2020 <a href="http://haizim.one/">haizim</a> All rights reserved.</p>
</li>
  
     
    </ul>
  </div>  
</nav>
<div class="row">
    <div class="col-sm-2">
  <div class="samping">
  <a href="index.php"><h5 style="font-weight:800">Telegram<br/>Chatbot<br/>Management<br/>System</h5></a>
  <hr/>
<a href="index.php"><div class="mn"><i class='fas fa-couch'></i> Dashboard </div></a>
<a href="button.php"><div class="mn"><i class='fas fa-grip-horizontal'></i> Button Set </div></a>
<a href="action.php"><div class="mn"><i class='fas fa-child'></i> Action Set </div></a>
<a href="command.php"><div class="mn"><i class='fas fa-robot'></i> Command </div></a>
<a href="message.php"><div class="mn"><i class='fas fa-comments'></i> Direct Message </div></a>
<a href="broadcast.php"><div class="mn"><i class='fas fa-broadcast-tower'></i> Broadcast </div></a>
<!--a href="analytics.php"><div class="mn"><i class='far fa-chart-bar'></i> Analytics </div></a-->
<a href="settings.php"><div class="mn"><i class='fas fa-cog'></i> Setting </div></a>
<hr/>
 <p style="font-size:small">© 2020 <a href="http://haizim.one/">Haizim</a> All rights reserved.</p>
    </div></div>
    <div class="col-sm isi">
  <h2 style="font-weight:700;padding: 10px 10%;"><?php echo $judul; ?></h2>
  <div class="kontener">

  
    
    