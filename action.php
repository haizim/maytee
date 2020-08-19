<?php
$judul = "Action Set";

include 'part/header.php';
$q = "select * from action order by no desc";
$get = mysqli_query($konek,$q);
?>
    <a href="c_action.php"></a>
    <form method="post" action="c_action.php">
        <button class="btn btn-block tmb" name="create" type="submit" value="create">Create New Action Set</button>
    <?php while($hasil=mysqli_fetch_array($get)){
        //print_r($hasil);
        $act = json_decode($hasil['action']);
        ?>
        
    <div class="row" style="border-bottom : 1px solid #777; padding:5px 0px">
    <div class="col-sm-9">
        <h5 style="font-weight:700;"><?php echo $hasil['name']; ?></h5>
        <?php
            for($w=0;$w<=count($act)-1;$w++){
                echo "<span class='summary'>".$act[$w][0]."</span> ";
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