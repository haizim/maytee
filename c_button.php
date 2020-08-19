<?php
$judul = "Button Set";
if ($_POST['delete']){
    $judul = "Delete ".$judul;
}elseif ($_POST['create']){
    $judul = "Create ".$judul;
}elseif ($_POST['edit']){
    $judul = "Edit ".$judul;
}else{
    header("location:button.php");
}

include 'part/header.php';

if ($_POST['delete']){
    $judul = "Delete ".$judul;
    $del = $_POST['delete'];
    $q = "delete from button where no='$del'";
    $delete = mysqli_query($konek, $q);
    if($delete){
        echo "<h3 style='font-weight:650;'>Button Set Successfully Deleted</h3>";
    }else{
        echo "<h3 style='font-weight:650;'>Sorry, Button Set Unsuccessfully Deleted</h3>";
    }
    echo "<a href='button.php'><button class='btn btn-block tmb'>Back to Button Set List</button></a>";

    
}elseif ($_POST['create']){
    $row=2;
    $cel="[1]";
?>
<form method="post" action="p_button.php">
<div class="form-group">
    <p>Button Set Name :</p>
    <input type="text" name="title" class="form-control">
    <hr/>
<div class="row" id="row1">
    <div id="cel1-1" class="col kol">
        <div class="cel">
            <p id="capt1-1">Caption :</p>
            <input type="text" id="vcapt1-1" name="capt[1][]" class="form-control">
            <p id="capt1-1">Content :</p>
            <input type="text" id="vcont1-1" name="cont[1][]" class="form-control">
            <p id="capt1-1">Type :</p>
            <select name="type[1][]" class="form-control" id="type1-1">
                <option value="callback_data" selected>callback_data</option>
                <option value="url">url</option>
            </select>
            <center>
                <i class='fas fa-trash-alt' onclick="delcel('cel1-1')"></i>
            </center>
        </div>
    </div>
    <div class="col-sm-1">
        <br/>
            <p onclick="addcel(1);"><i style="font-size: large;" class="fas fa-plus circle"></i></p>
            <p onclick="delrow(1);"><i style="font-size: large;" class="fas fa-trash-alt circle"></i></p>
        
    </div>
</div>
<div class="row" id="row2"></div>
<span class="btn btn-block tmb-" onclick="addrow();"> +Add Row </span><br/> 
<button type="submit" class="btn tmb" name="submit" value="add">Create</button>
</div>
</form>
<?php
//////////////////////////////[EDIT MULAI]//////////////////////////////////////

}elseif ($_POST['edit']){
    $edit = $_POST['edit'];
    //$no = $_POST['no'];
    $q = "select * from button where no='$edit'";
    $sql = mysqli_query($konek,$q);
    $content = mysqli_fetch_array($sql);
    $capt = json_decode($content['caption'],true);
    $cont = json_decode($content['content'],true);
    $type = json_decode($content['type'],true);
    $row = count($capt)+1;
    $cel = "[";
    /*
    echo "<hr/>";
    print_r($capt);
    echo "<hr/>";
    print_r($cont);
    echo "<hr/>";
    print_r($type);
    echo "<hr/>";*/
?>    
    <form method="post" action="p_button.php">
<div class="form-group">
    <p>Button Set Name :</p>
    <input type="text" name="title" class="form-control" value="<?php echo $content['name'] ;?>">
    <hr/>
<?php 
for($m=1;$m<=count($capt);$m++){
$cel=$cel.count($capt[$m]).",";
?>

<div class="row" id="row<?php echo $m;?>">
	<?php for($w=0;$w<=count($capt[$m])-1;$w++){ ?>
    <div id="cel<?php echo $m."-".($w+1); ?>" class="col kol">
        <div class="cel">
            <p id="capt<?php echo $m."-".($w+1); ?>">Caption :</p>
            <input type="text" id="vcapt<?php echo $m."-".($w+1); ?>" name="capt[<?php echo $m;?>][]" class="form-control" value="<?php echo $capt[$m][$w]; ?>">
            <p id="capt<?php echo $m."-".($w+1); ?>">Content :</p>
            <input type="text" id="vcont<?php echo $m."-".($w+1); ?>" name="cont[<?php echo $m;?>][]" class="form-control" value="<?php echo $cont[$m][$w]; ?>">
            <p id="capt<?php echo $m."-".($w+1); ?>">Type :</p>
            <select name="type[<?php echo $m;?>][]" class="form-control" id="type<?php echo $m."-".($w+1); ?>">
                <option value="callback_data">callback_data</option>
                <option value="url">url</option>
            </select>
            <center>
            <i class='fas fa-trash-alt' onclick="delcel('cel<?php echo $m."-".($w+1); ?>')"></i>
            </center>
        </div>
    </div>
    <?php
	}
    ?>
    <div class="col-sm-1">
        <br/>
            <p onclick="addcel(<?php echo $m;?>);"><i style="font-size: large;" class="fas fa-plus circle"></i></p>
            <p onclick="delrow(<?php echo $m;?>);"><i style="font-size: large;" class="fas fa-trash-alt circle"></i></p>
        
    </div>
</div>
<?php
}
$cel = $cel."]";
?>
<div class="row" id="row<?php echo $row;?>"></div>
<span class="btn btn-block tmb-" onclick="addrow();"> +Add Row </span><br/> 
<input type="hidden" name="no" value="<?php echo $edit; ?>"/>
<button type="submit" class="btn tmb" name="submit" value="edit">Update</button>
</div>
</form>

<script>
window.onload = function(){
	<?php
		for($m=1;$m<=count($capt);$m++){
			for($w=0;$w<=count($capt[$m])-1;$w++){
				$nom = $m."-".(($w+1));
				$tipe = $type[$m][$w];
				echo "document.getElementById('type".$nom."').value='$tipe'\n";
			}
		}
	?>
}
</script>
<?php
}
?>

<script>
    row=<?php echo $row;?>;
    cel=<?php echo $cel;?>;
    
    function delcel(no){
        $("#"+no+" .cel").remove();
        $("#"+no).removeClass("col");
    }
    
    function delrow(no){
        $("#row"+no+" div").remove()
    }
    
    function addcel(rows){
        cnt=cel[(rows-1)];
        elemen = rows+"-"+cnt;
        elemenext = rows+"-"+(cnt+1);
        isicel=`<div id="cel`+elemenext+`" class="col kol"><div class="cel"><p id="capt`+elemenext+`">Caption :</p><input type="text" id="vcapt`+elemenext+`" name="capt[`+rows+`][]" class="form-control"><p id="capt`+elemenext+`">Content :</p><input type="text" id="vcont`+elemenext+`" name="cont[`+rows+`][]" class="form-control"><p id="capt`+elemenext+`">Type :</p><select name="type[`+rows+`][]" class="form-control" id="type`+elemenext+`"><option value="callback_data" selected>callback_data</option><option value="url">url</option></select><center><i class='fas fa-trash-alt' onclick="delcel('cel`+elemenext+`')"></i></center></div></div>`;
        console.log("elemen ="+elemen);
        console.log("isicel = "+isicel);
        $("#cel"+elemen).after(isicel);
        cel[(rows-1)] = cnt+1;
    }
    
    function addrow(){
        rownow=row-1;
        isirow=`<div class="row" id="row`+row+`"><div id="cel`+row+`-1" class="col kol"><div class="cel"><p id="capt`+row+`-1">Caption :</p><input type="text" id="vcapt`+row+`-1" name="capt[`+row+`][]" class="form-control"><p id="capt1-1">Content :</p><input type="text" id="vcont`+row+`-1" name="cont[`+row+`][]" class="form-control"><p id="capt`+row+`-1">Type :</p><select name="type[`+row+`][]" class="form-control" id="type`+row+`-1"><option value="callback_data" selected>callback_data</option><option value="url">url</option></select><center><i class='fas fa-trash-alt' onclick="delcel('cel`+row+`-1')"></i></center></div></div><div class="col-sm-1"><br/><p onclick="addcel(`+row+`);"><i style="font-size: large;" class="fas fa-plus circle"></i></p><p onclick="delrow(`+row+`);"><i style="font-size: large;" class="fas fa-trash-alt circle"></i></p></div></div>`;
        console.log("rownow ="+rownow);
        console.log("isirow = "+isirow);
        $("#row"+rownow).after(isirow);
        cel[rownow]=1;
        row++;
    }
</script>

<?php
include 'part/footer.php';
?>