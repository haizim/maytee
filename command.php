<?php
$judul = "Command of Reply Set";

include 'part/header.php';
$q = "select * from command where no!=1 and no!=2 order by no desc";
$get = mysqli_query($konek,$q);

function act_name($no){
    include "part/konek.php";
    
    
    $q_act = "select * from action where no='$no'";
    $get_act = mysqli_query($konek,$q_act);
    
    $hsl=mysqli_fetch_array($get_act);
    $action_name = $hsl['name'];
    
    
    //print_r($hsl);
    
    return $action_name;
}


?>

<form method="post" action="c_command.php" style="line-height : 2em;">
        <button class="btn btn-block tmb" name="create" type="submit" value="create"> Create New Command of Reply Set </button>
    <?php while($hasil=mysqli_fetch_array($get)){
        //print_r($hasil);
        $key = explode(" ",$hasil['keyword']);
        //print_r($key);
        $act = json_decode($hasil['action']);
        ?>
        
        <div class="row" style="border-bottom : 1px solid #777; padding:5px 0px">
    <div class="col-sm-9">
        <h5 style="font-weight:700;"><?php echo $hasil['name']; ?></h5>
        <?php
            for($w=0;$w<=count($key)-1;$w++){
                echo "<span class='summary'>".$key[$w]/*act_name($act[$w])*/."</span> ";
            }
            echo "<br/>";
            for($w=0;$w<=count($act)-1;$w++){
                echo "<span class='summary-'>".act_name($act[$w])."</span> ";
            }
        ?>
    </div>
    <div class="col-sm-3">
        <button type="submit" name="edit" class="btn tmb" value="<?php echo $hasil['no']; ?>">Edit</button>  
        <button type="submit" name="delete" class="btn tmb-" value="<?php echo $hasil['no']; ?>">Delete</button>
    </div>
    </div>
        
        <?php
            }
        ?>