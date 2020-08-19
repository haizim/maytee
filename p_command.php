<?php
$judul = "Process Command";
$title = $_POST['title'];
$act = $_POST['act'];
$key = $_POST['key'];
$type = $_POST['type'];
$submit = $_POST['submit'];
$existed = "";

include "part/header.php";

/*print_r($act);
echo "<br/>$title = title<br/>";
echo "$key = key<br/>";
echo "$type = type<br/>";
echo "$submit = submit<br/>";*/

function checkkw($type, $key){
    include "part/konek.php";
    
    $q = "select * from command where keyword like '% $key%' or keyword like '%$key %'";
    $run = mysqli_query($konek, $q);
    $hasil = mysqli_fetch_array($run);
    
    //echo $key." hasil >> ".$hasil['no']." // query = ".$q."<br/>";
    
    if(strlen($hasil['no'])>0){
        $rtrn = ", $key";
    }else{
        $rtrn = "";
    }
    
    return $rtrn;
}

$word = explode(" ",$key);

$jarr = json_encode($act);

//echo $type."<br/>";

if ($submit=="add"){
    for($m=0;$m<=count($word)-1;$m++){
        $wrd = $word[$m];
        //echo $wrd.", ";
        $existed = $existed.checkkw($type,$wrd);
    }
    
    //echo strlen($existed)."<br/>";
    
    if($existed==""){
        $q = "insert into command(name,type,keyword,action) values ('$title','$type','$key','$jarr')";
        $ok = "Created";
    }else{
        echo "<h3 style='font-weight:700'>Sorry, keyword(s) '$existed' for $type has been existed</h3>";   
    }
}elseif ($submit=="edit"){
    $no = $_POST['no'];
    echo "$no = no<br/>";
    $q = "update command set name='$title', type='$type', keyword='$key', action='$jarr' where no='$no'";  
    $ok = "Edited";
}

$ins = mysqli_query($konek,$q);

if ($ins){
    echo "<h3 style='font-weight:700'>Action Set $title Successfully $ok </h3>";
}else{
    echo "<h3 style='font-weight:700'>Action Set $title Failed $ok </h3>";
}
?>
<hr/>
<a href="command.php"><button class="btn btn-block tmb">Back to Action Set List</button></a> 

<?php
include "part/footer.php";
?>