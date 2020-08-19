<?php
$judul = "proses action";
include "part/header.php";
$title = $_POST['title'];
$act = $_POST['act'];
$one = $_POST['one'];
$two = $_POST['two'];
$submit = $_POST['submit'];
/* echo "<br/>+++++++++++++++++++++++++++++<br/>";
 print_r($act);
 echo "<br/>";
 print_r($one);
 echo "<br/>";
 print_r($two);
 echo "<br/>";
 echo "------------------------------------";*/
 $jml = count($act)-1;
$arr = [];

for($m=0;$m<=$jml;$m++){
    ?>
<!--div class="row">
    <div class="col-4"><?php echo "act$m >> ".$act[$m];?></div>
    <div class="col-4"><?php echo "one$m >> ".$one[$m];?></div>
    <div class="col-4"><?php echo "two$m >> ".$two[$m];?></div>
</div-->

<?php
$actm = mysqli_escape_string($konek,$act[$m]);
$onem = mysqli_escape_string($konek,$one[$m]);
$twom = mysqli_escape_string($konek,$two[$m]);
$arr[$m]=[$actm,$onem,$twom];

}
//print_r($arr);
$jarr = json_encode($arr);

if ($submit=="add"){
$q = "insert into action(name,action) values ('$title','".$jarr."')";
$ok = "Created";
}elseif ($submit=="edit"){
    $no = $_POST['no'];
    $q = "update action set name='$title', action='".$jarr."' where no='$no'";  
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
<a href="action.php"><button class="btn btn-block tmb">Back to Action Set List</button></a> 