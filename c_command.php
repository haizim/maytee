<?php
$judul = "Command";
if ($_POST['delete']){
    $judul = "Delete ".$judul;
}elseif ($_POST['create']){
    $judul = "Create ".$judul;
}elseif ($_POST['edit']){
    $judul = "Edit ".$judul;
}else{
    header("location:command.php");
}
include 'part/header.php';

$qb = "select * from action";
$act = mysqli_query($konek,$qb);
$action = "";

while($actn = mysqli_fetch_array($act)){
    $vactn = $actn['no'];
    $cactn = $actn['name'];
    $action=$action."<option value='$vactn'>$cactn</option>\n";    
}

if ($_POST['delete']){
    $judul = "Delete ".$judul;
    $del = $_POST['delete'];
    $q = "delete from command where no='$del'";
    $delete = mysqli_query($konek, $q);
    if($delete){
        echo "<h3 style='font-weight:650;'>Command of Reply Set Successfully Deleted</h3>";
    }else{
        echo "<h3 style='font-weight:650;'>Sorry, Command of Reply Set Unsuccessfully Deleted</h3>";
    }
    echo "<a href='command.php'><button class='btn btn-block tmb'>Back to Command of Reply Set List</button></a>";
}elseif ($_POST['create']){
    $judul = "Create ".$judul;
    $cnt = "2";

?>

<form method="post" action="p_command.php" style="line-height:1em">
    <div class="form-group">
    <p><b>Command Name :</b></p>
    <input type="text" class="form-control" name="title"/>
    <p><b>Input Type :</b></p>
    <p>
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
  <label class="btn btn-lbl">
    <input type="radio" name="type" id="tmsg" value="message">Message
  </label>
  <label class="btn btn-lbl">
    <input type="radio" name="type" id="tcb" value="callback">Callback Button
  </label>
</div>
    </p>
    <p><b>Keywords : (separated by spaces)</b><br/></p>
    <input type="text" class="form-control" name="key"/>
    <hr/>
    
    
    <div id="input1">
    <p id="act1" >Action 1 :<i class='fas fa-trash-alt' onclick="hapus(1)"></i></p>
    <select name="act[]" class="form-control" id="vact1">
        <?php echo $action; ?>
    </select><hr/>
    </div>
    
    <div id="input2"></div>
    </div>
    
    <span class="btn btn-md tmb-" onclick="tambah()"> +Add Action </span>  
    <button class="btn tmb" type="submit" name="submit" value="add">Create</button> 
    
</form>
<?php }elseif ($_POST['edit']){

    $edit = $_POST['edit'];
    //echo $edit."<hr/>";
    $q = "select * from command where no='$edit'";
    //echo $q."<hr/>";
    $sql = mysqli_query($konek,$q);
    $content = mysqli_fetch_array($sql);
    $act = json_decode($content['action']);
    //print_r($act);
    $cnt = count($act)+1;
    
    if($content['type']=="message"){
        $clm = " active";
        $chm = " checked";
        $clc = "";
        $chc = "";
    }elseif($content['type']=="callback"){
        $clc = " active";
        $chc = " checked";
        $clm = "";
        $chm = "";
    }
?>    

<form method="post" action="p_command.php" style="line-height : 1em;">
    <div class="form-group">
    <p><b>Command Name :</b></p>
    <input type="text" class="form-control" name="title" value="<?php echo $content['name']; ?>"/>
    <p><b>Input Type :</b></p>
    <p>
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
  <label class="btn btn-lbl <?php echo $clm; ?>">
    <input type="radio" name="type" id="tmsg" autocomplete="on" value="message"<?php echo $chm; ?>>Message
  </label>
  <label class="btn btn-lbl <?php echo $clc; ?>">
    <input type="radio" name="type" id="tcb" autocomplete="on" value="callback" <?php echo $chc; ?>>Callback Button
  </label>
</div>
    </p>
    <p><b>Keywords : (separated by spaces)</b><br/></p>
    <input type="text" class="form-control" name="key" value="<?php echo $content['keyword']; ?>"/>
    <hr/>
    
    <?php
    	for($m=0;$m<$cnt-1;$m++){
    ?>
    <div id="input<?php echo $m+1; ?>">
    <p id="act1" >Action <?php echo $m+1; ?> :<i class='fas fa-trash-alt' onclick="hapus(<?php echo $m+1; ?>)"></i></p>
    <select name="act[]" class="form-control" id="vact<?php echo $m+1; ?>">
        <?php echo $action; ?>
    </select><hr/>
    </div>
    <?php } ?>
    
    <div id="input<?php echo $cnt; ?>"></div>
    </div>
    <input type="hidden" name="no" value="<?php echo $edit; ?>">
    <span class="btn btn-md tmb-" onclick="tambah()"> +Add Action </span>  
    <button class="btn tmb" type="submit" name="submit" value="edit">Update</button> 
    
</form>

<script type="text/javascript">
	window.onload = function(){
	<?php
	for($m=0;$m<$cnt-1;$m++){
		$count = $m+1;
		$val = $act[$m];
		echo "document.getElementById('vact"."$count').value = '$val';\n";
	}
	?>
	};
</script>
<?php
}?>

<script>
    counter = <?php echo $cnt; ?>;
    
    function hapus(no){
        $("#act"+no).remove();
        $("#vact"+no).remove();
    }
    
    function tambah(){
        document.getElementById("input"+counter).innerHTML =`<div id="input`+counter+`"><p id="act`+counter+`" >Action `+counter+` : <i class='fas fa-trash-alt' onclick="hapus(`+counter+`)"></i></p><select name="act[]" class="form-control" id="vact`+counter+`""><?php echo $action; ?></select><hr/></div><div id="input`+(counter+1)+`"></div>`;
        counter++;
    }
</script>

<?php
include 'part/footer.php';
?>