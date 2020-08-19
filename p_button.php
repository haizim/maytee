<?php
    $judul="proses tombol";
    $capt = $_POST['capt'];
    $cont = $_POST['cont'];
    $type = $_POST['type'];
    $title = $_POST['title'];
    $submit = $_POST['submit'];
    include 'part/header.php';
    /* print_r($capt);
     echo count($capt)."<hr/>";
     print_r($cont);
     echo count($cont)."<hr/>";
     print_r($type);
     echo count($type)."<hr/>";*/
    
    $jcapt = json_encode($capt);
    $jcont = json_encode($cont);
    $jtype = json_encode($type);

if ($submit=="add"){
$q = "insert into button(name,caption,content,type) values ('$title','$jcapt','$jcont','$jtype')";
$ok = "Created";
}elseif ($submit=="edit"){
    $no = $_POST['no'];
    //echo "judl = $title";
    $q = "update button set name='$title', caption='$jcapt', content='$jcont', type='$jtype' where no='$no'";  
    //echo "q = $q";
    $ok = "Edited";
}

$ins = mysqli_query($konek,$q);

if ($ins){
    echo "<h3 style='font-weight:700'>Button Set $title Successfully $ok </h3>";
}else{
    echo "<h3 style='font-weight:700'>Button Set $title Failed $ok </h3>";
}
?>

<?php
include 'part/footer.php';
?>