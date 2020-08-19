<?php
$judul ="Dashboard";
include 'part/header.php';

function getnumber($table){
    include "part/konek.php";
    
    
    $q = "select * from $table";
    $get = mysqli_query($konek,$q);
    $number = mysqli_num_rows($get);
    
    return $number;
}

//$datex=date_create();
//date_date_set($datex,2020,04,11);
//echo date_format($date,"Y/m/d");



$lbl = "";
$dt="";
$stat = [];
    for($m=29;$m>=0;$m--){
        $t=time();
    $tm = date("Y-m-d",$t);
    
    //$date=date_create("2020-04-10");
    //$tm = $date;
    
    //echo $tm."<br/>";
    //echo date_format($tm,"Y/m/d")."<br/>";
    
    $date = date_create($tm);
        date_sub($date, date_interval_create_from_date_string($m.' days'));
        $now = date_format($date, 'Y-m-d');
        $qs = "select * from log where waktu like '%$now%'";
        $runs = mysqli_query($konek, $qs);
        $nums = mysqli_num_rows($runs);
        $stat[$now]=$nums;
        $lbl .= "'$now',";
        $dt .= "$nums,";
        //echo "m=$m >> $now // $nums<br/>"; 
    }
    
    //echo "<hr/>";
    //print_r($stat);
?>
<script src="chartjs/dist/Chart.js"></script>
<script>
$(document).ready(function(){
    
var ctx = $('#myLineChart');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo $lbl; ?>],
        datasets: [{
            label: 'Interaction',
            data: [<?php echo $dt; ?>],
            borderColor: [
                'hsl(<?php echo $h;?>,34%,40%)',
            ],
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

    
});
</script>

<div class="row dash">
    <div class="col-7 col-lg-4">
        <div class="total">
            <p>Total Users</p>
            <h1><?php echo getnumber("user"); ?></h1>
        </div>
    </div>
    <div class="col-7 col-lg-4">
        <div class="total">
            <p>Total Words</p>
            <h1><?php echo getnumber("word"); ?></h1>
        </div>
    </div>
    <div class="col-7 col-lg-4">
        <div class="total">
            <p>Total Chats</p>
            
            <h1><?php echo getnumber("log"); ?></h1>
        </div>
    </div>
</div>
<div class="row dash">
    <div class="col-7 col-lg-4">
        <div class="total">
            <p>Total Button Sets</p>
            <h1><?php echo (getnumber("button")-1); ?></h1>
        </div>
    </div>
    <div class="col-7 col-lg-4">
        <div class="total">
            <p>Total Action Sets</p>
            <h1><?php echo getnumber("action"); ?></h1>
        </div>
    </div>
    <div class="col-8 col-lg-4">
        <div class="total">
            <p>Total Command Sets</p>
            <h1><?php echo (getnumber("command")-2); ?></h1>
        </div>
    </div>
</div>
<hr/>
<h3>Last 30 Days Statistics Usage</h3>
<canvas id="myLineChart" width="100%" height="58px"></canvas>
<hr/>
<div class="row">
    <div class="col-sm-6 top5">
        <h3>Top 5 Word</h3>
    <?php
        $qw= "select * from word where kata!='' order by jumlah desc limit 5 ";
        $runw = mysqli_query($konek, $qw);
        $cntw = 1;
        while($getw = mysqli_fetch_array($runw)){
            echo $cntw.". <b>";
            echo $getw['kata']."</b> (".$getw['jumlah'].")";?>
        <hr/>
    <?php
        $cntw++;
    }

    ?>
    </div>
    
    <div class="col-sm-6 top5">
        <h3>Top 5 Command</h3>
    <?php
        $qc= "SELECT DISTINCT(command) FROM `command-log` where command!=''";
        $runc = mysqli_query($konek, $qc);
        $numc = [];
        $cntc = 1;
        while($getc = mysqli_fetch_array($runc)){
            //print_r($getc); 
            $cmdc = $getc['command'];
            $qcn = "select * from `command-log` where command='$cmdc'";
            $runcn = mysqli_query($konek, $qcn);
            $cntcn = mysqli_num_rows($runcn);
            //echo "<br/>cmdc = $cmdc // cntcn = $cntcn <br/>";
            $numc["$cmdc"] = "$cntcn";
           // echo "<hr/>";
    }
    //echo "/////////////////<br/>";
    //print_r($numc);
    arsort($numc);
    //echo "=================<br/>";
    //print_r($numc);
    
    foreach($numc as $r => $rv){
        if($cntc<=5){
            echo $cntc.". <b>";
            echo "$r</b> ($rv)";?>
        <hr/>
    <?php
        $cntc++;
        }
    }

    ?>
    </div>
</div>
<?php
    
?>

<?php
include 'part/footer.php';
?>