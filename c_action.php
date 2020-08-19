<?php
$judul = "Action Set";
if ($_POST['delete']){
    $judul = "Delete ".$judul;
}elseif ($_POST['create']){
    $judul = "Create ".$judul;
}elseif ($_POST['edit']){
    $judul = "Edit ".$judul;
}else{
    header("location:action.php");
}

include 'part/header.php';

$qb = "select * from button where no!=1 order by no asc";
$but = mysqli_query($konek,$qb);
$button = "";

while($btn = mysqli_fetch_array($but)){
    $vbut = $btn['no'];
    $cbut = $btn['name'];
    $button=$button."<option value='$vbut'>$cbut</option>\n";    
}

//echo $button;

if ($_POST['delete']){
    $judul = "Delete ".$judul;
    $del = $_POST['delete'];
    $q = "delete from action where no='$del'";
    $delete = mysqli_query($konek, $q);
    if($delete){
        echo "<h3 style='font-weight:650;'>Action Set Successfully Deleted</h3>";
    }else{
        echo "<h3 style='font-weight:650;'>Sorry, Action Set Unsuccessfully Deleted</h3>";
    }
    echo "<a href='action.php'><button class='btn btn-block tmb'>Back to Action Set List</button></a>";
}elseif ($_POST['create']){
    $judul = "Create ".$judul;
    $cnt = "2";
?>

<form method="post" action="p_action.php" style="line-height:1em">
    <div class="form-group">
    <b>Action Set Name :</b><br/>
    <input type="text" class="form-control" name="title"/>
    <hr/>
    
    
    <div id="input1">
    <h6 style="font-weight:700;" id="hdr1">Action 1 <i class='fas fa-trash-alt' onclick="hapus(1)"></i></h6>
    <p id="act1" >Action :</p>
    <select name="act[]" class="form-control" id="type1" onchange="cpt('1')">
        <option value="photo" selected>Photo</option>
        <option value="video">Video</option>
        <option value="audio">audio</option>
        <option value="document">Document</option>
        <option value="location">Location</option>
        <option value="contact">Contact</option>
        <option value="button">Buttons</option>
        <option value="message">Message</option>
    </select><br/>
    <p id="one1" >URL :</p>
    <input type="text" id="vone1" class="form-control" name="one[]"/>
    <select id="butn1" style="display: none;" class="form-control" onchange="btnval(1)">
        <?php echo $button; ?>
    </select><br/>
    <p id="two1" >Caption :</p>
    <textarea type="text" id="vtwo1" class="form-control" name="two[]"></textarea>
    <hr id="hr1"/>
    </div>
    
    <div id="input2"></div>
    </div>
    
    <span class="btn btn-md tmb-" onclick="tambah()"> +Add Action </span>  
    <button class="btn tmb" type="submit" name="submit" value="add">Create</button> 
    
</form>

<?php 

//////////////////////////////[EDIT MULAI]//////////////////////////////////////

}elseif ($_POST['edit']){
    $judul = "Edit ".$judul;
    $edit = $_POST['edit'];
    //$no = $_POST['no'];
    $q = "select * from action where no='$edit'";
    $sql = mysqli_query($konek,$q);
    $content = mysqli_fetch_array($sql);
    $act = json_decode($content['action']);
    $cnt = count($act)+1;
    //print_r($content);
    //echo "<hr/>";
    //print_r($act);
    //echo "<hr/>";
    //echo "<hr/>";
    ?>
    
   <form method="post" action="p_action.php" style="line-height:1em">
    <div class="form-group">
    <b>Action Set Name :</b><br/>
    <input type="text" class="form-control" name="title" value="<?php echo $content['name']; ?>"/>
    <hr/>
    <?php
    	for($m=0;$m<$cnt-1;$m++){
    ?>
    <div id="input<?php echo $m+1; ?>">
    <h6 style="font-weight:700;" id="hdr<?php echo $m+1; ?>">Action <?php echo $m+1; ?> <i class='fas fa-trash-alt' onclick="hapus(<?php echo $m+1; ?>)"></i></h6>
    <p id="act<?php echo $m+1; ?>">Action :</p>
    <select name="act[]" class="form-control" id="type<?php echo $m+1; ?>" onchange="cpt('<?php echo $m+1; ?>')">
        <option value="photo" selected>Photo</option>
        <option value="video">Video</option>
        <option value="audio">audio</option>
        <option value="document">Document</option>
        <option value="location">Location</option>
        <option value="contact">Contact</option>
        <option value="button">Buttons</option>
        <option value="message">Message</option>
    </select><br/>
    <p id="one<?php echo $m+1; ?>" >URL :</p>
    <input type="text" id="vone<?php echo $m+1; ?>" class="form-control" name="one[]"/ value="<?php echo $act[$m][1]; ?>">
    <select id="butn<?php echo $m+1; ?>" style="display: none;" class="form-control" onchange="btnval(<?php echo $m+1; ?>)">
        <?php echo $button; ?>
    </select>
    <br/>
    <p id="two<?php echo $m+1; ?>" >Caption :</p>
    <textarea type="text" id="vtwo<?php echo $m+1; ?>" class="form-control" name="two[]" ><?php echo $act[$m][2]; ?></textarea>
    <hr id="hr<?php echo $m+1; ?>"/>
    </div>
    
	<?php } ?>
	<div id="input<?php echo $cnt; ?>"></div>
    </div>
    <input type="hidden" name="no" value="<?php echo $edit; ?>"/>
    <span class="btn btn-md tmb-" onclick="tambah()"> +Add Action </span>  
    <button class="btn tmb" type="submit" name="submit" value="edit">Update</button> 
    
</form>

<script type="text/javascript">
	window.onload = function(){
	<?php
	for($m=0;$m<$cnt-1;$m++){
		$count = $m+1;
		$val = $act[$m][0];
		echo "document.getElementById('type"."$count').value = '$val';\n";
		echo "cpt('$count');\n";
	}
	?>
	};
</script>
    
<?php    
}
?>

<script>
    counter = <?php echo $cnt; ?>;
    function hapus(no){
        $("#hdr"+no).remove();
        $("#act"+no).remove();
        $("#type"+no).remove();
        $("#one"+no).remove();
        $("#vone"+no).remove();
        $("#two"+no).remove();
        $("#vtwo"+no).remove();
        //$("#hr"+no).remove();
    }
    
    function btnval(no){
        $("#vone"+no).val($("#butn"+no).val())
    }
    
    function tambah(){
        document.getElementById("input"+counter).innerHTML = `<h6 style="font-weight:700;" id="hdr`+counter+`">Action `+counter+` <i class='fas fa-trash-alt' onclick="hapus(`+counter+`)"></i></h6><p id="act`+counter+`" >Action :</p><select name="act[]" class="form-control" id="type`+counter+`" onchange="cpt('`+counter+`')"><option value="photo" selected>Photo</option><option value="video">Video</option><option value="audio">audio</option><option value="document">Document</option><option value="location">Location</option><option value="contact">Contact</option><option value="button">Buttons</option><option value="message">Message</option></select><br/><p id="one`+counter+`" >URL :</p><input type="text" id="vone`+counter+`" class="form-control" name="one[]"/><select id="butn`+counter+`" style="display: none;" class="form-control" onchange="btnval(`+counter+`)"><?php echo $button; ?></select><br/><p id="two`+counter+`" >Caption :</p><textarea type="text" id="vtwo`+counter+`" class="form-control" name="two[]"></textarea><hr id="hr`+counter+`"/><div id="input`+(1+counter)+`"></div>`
        counter++;
    }
    function cpt(no){
        elemen = "type"+no;
        one = "one"+no;
        two = "two"+no;
        valu = document.getElementById(elemen).value;
        if (valu == "button"){
            $("#butn"+no).show();
            $("#v"+one).hide();
            capt1="Button Set";
            capt2="Caption";
        }else{
            $("#butn"+no).hide();
            $("#v"+one).show();
        if (valu == "message"){
                capt1="-";
                capt2="Message";
                document.getElementById(one).hidden = true;
                document.getElementById("v"+one).hidden = true;
                
        }else{
            document.getElementById(one).hidden = false;
            document.getElementById("v"+one).hidden = false;
            

            switch(valu){
                case "photo":
                    capt1="URL";
                    capt2="Caption";
                    break;
                case "video":
                    capt1="URL";
                    capt2="Caption";
                    break;
                case "Audio":
                    capt1="URL";
                    capt2="Caption";
                    break;
                case "Document":
                    capt1="URL";
                    capt2="Caption";
                    break;
                case "location":
                    capt1="Latitude";
                    capt2="Longitude";
                    break;
                case "contact":
                    capt1="Phone Number";
                    capt2="Name";
                    break;
            }
        }
    }
        document.getElementById(one).innerHTML = capt1+" :";
        document.getElementById(two).innerHTML = capt2+" :";
        return capt1+" - "+capt2;
    }
</script>
<?php
include 'part/footer.php';
?>