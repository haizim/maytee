<?php
$judul = "Button Set";

include 'part/header.php';
$q = "select * from button where no!='1' order by no desc";
$get = mysqli_query($konek,$q);
?>
    <a href="c_action.php"></a>
    <form method="post" action="c_button.php">
        <button class="btn btn-block tmb" name="create" type="submit" value="create">Create New Button Set</button>
    <?php while($hasil=mysqli_fetch_array($get)){
        //print_r($hasil);
        $capt = json_decode($hasil['caption'],TRUE);
        $cont = json_decode($hasil['content'],TRUE);
        $type = json_decode($hasil['type'],TRUE);
        
        /*print_r($capt); 
        echo "<hr/>";
        print_r($cont); 
        echo "<hr/>";
        print_r($type); 
        echo "<hr/>";*/
        ?>
        
    <div class="row" style="border-bottom : 1px solid #777; padding:5px 0px; line-height:2em;">
    <div class="col-sm-9">
        <h5 style="font-weight:700;"><?php echo $hasil['name']; ?></h5>
        <?php
            for($w=1;$w<=count($capt);$w++){
                /*echo "capt".$w." >-> ";
                print_r($capt[$w]);
                echo "<br/>";*/
                for($m=0;$m<=count($capt[$w])-1;$m++){
                    echo "<span class='summary'>".$capt[$w][$m]."</span>";
                }
                echo "<br/>";
            }
        ?>
    </div>
    <div class="col-sm-3">
        <button type="submit" name="edit" class="btn tmb" value="<?php echo $hasil['no']; ?>">Edit</button> 
        
        <button type="submit" name="delete" class="btn tmb-" value="<?php echo $hasil['no']; ?>">Delete</button>
    </div>
    </div>
    <?php } ?>
</form>