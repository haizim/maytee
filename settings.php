<?php
$judul ="Bot Setting";
include 'part/header.php';

if (isset($_POST['token'])){
    $token = $_POST['token'];
}else{
    $token = "";
}

if (isset($_POST['id'])){
    $id = $_POST['id'];
}else{
    $id = "";
}

if (isset($_POST['username'])){
    $username = $_POST['username'];
}else{
    $username = "";
}

if (isset($_POST['dispname'])){
    $dispname = $_POST['dispname'];
}else{
    $dispname = "";
}


$tkn = "";
$cht = "";

if(isset($_POST['submit'])){

$submit = $_POST['submit']; 
//echo "submit = $submit<hr/>";
if($submit=="deploy"){
    $tkn = $token;
    $cht = "deploybot('$tkn'); \n checktoken();";
    
    echo "<div class='infot'>Data has been submited</div>";
    
    $qt = "update info set val = '$token' where name = 'token';";
    $runt = mysqli_query($konek, $qt);
    //echo $qt."<br/>";
    //echo mysqli_errno($konek)."<hr/>";
    $qi = "update info set val = '$id' where name = 'id';";
    $runi = mysqli_query($konek, $qi);
    //echo $qi."<br/>";
    //echo mysqli_errno($konek)."<hr/>";
    $qu = "update info set val = '$username' where name = 'username';";
    $runu = mysqli_query($konek, $qu);
    //echo $qu."<br/>";
    //echo mysqli_errno($konek)."<hr/>";
    $qd = "update info set val = '$dispname' where name = 'display_name';";
    $rund = mysqli_query($konek, $qd);
    //echo $qd."<br/>";
    //echo mysqli_errno($konek)."<hr/>";
}elseif($submit=="update"){
    //echo "update<br/>";
    $startv = $_POST['startv'];
    $defv = $_POST['defv'];
    $rs = explode("|+|",$startv);
    $rd = explode("|+|",$defv);
    $js = json_encode($rs);
    $jd = json_encode($rd);
    //echo"startv = $startv // defv = $defv<br/>";
    $qs = "update command set action='$js' where name='start'";
    //print_r($rs);
    //print_r($rd);
    $upd = "<div class='infot'>Default Response & Welcome Message submited successfully</div>";
    $runs = mysqli_query($konek,$qs);
    $qd = "update command set action='$jd' where name='default'";
    $rund = mysqli_query($konek,$qd);
    
    $qc = "select * from info where name='token'";
$runc = mysqli_query($konek, $qc);
$resc = mysqli_fetch_array($runc);

if($resc['val']!=''){
    $tkn = $resc['val'];
    $cht = "checktoken();";
    //echo "ini load aja";
}
}

}else{
$qc = "select * from info where name='token'";
$runc = mysqli_query($konek, $qc);
$resc = mysqli_fetch_array($runc);

if($resc['val']!=''){
    $tkn = $resc['val'];
    $cht = "checktoken();";
    //echo "ini load aja";
}
}

///////////////////value start
$csa = "";
$cst = "";
$vsa = "";
$vst = "";
$defv = "";
$qs = "select * from command where name='start'";
$runs = mysqli_query($konek, $qs);
$ress = mysqli_fetch_array($runs);
$jrs = json_decode($ress['action']);
//print_r($jrs);
if($jrs['0']=="a"){
    //echo "start isinya action";
    $csa = "checked";
    $vsa = "$('#vacts').val(".$jrs[1].");";
    $startv = "a|+|".$jrs[1];
}elseif($jrs['0']=="t"){
    //echo "start isinya text";
    $cst = "checked";
    $vst = "$jrs[1]";
    $startv = "t|+|".$jrs[1];
}

///////////////////value default
$cda = "";
$cdt = "";
$vda = "";
$vdt = "";
$qd = "select * from command where name='default'";
$rund = mysqli_query($konek, $qd);
$resd = mysqli_fetch_array($rund);
$jrd = json_decode($resd['action']);
//print_r($jrs);
if($jrd['0']=="a"){
    //echo "start isinya action";
    $cda = "checked";
    $vda = "$('#vactd').val(".$jrd[1].");";
    $defv = "a|+|".$jrd[1];
}elseif($jrd['0']=="t"){
    //echo "start isinya text";
    $cdt = "checked";
    $vdt = "$jrd[1]";
    $defv = "t|+|".$jrd[1];
}


$qb = "select * from action";
$act = mysqli_query($konek,$qb);
$action = "";

while($actn = mysqli_fetch_array($act)){
    $vactn = $actn['no'];
    $cactn = $actn['name'];
    $action=$action."<option value='$vactn'>$cactn</option>\n";    
}
?>
<script>
    function checktoken(){
        token = $("#token").val();
        $.ajax({
                    type:"POST", url:"bot.php", data:"cmd=cektoken&one="+token, success:function(data){
                        $("#infot").html(data);
                    }
                });
                
        
    }
    function deploybot(token){
        $.ajax({
                    type:"POST", url:"bot.php", data:"cmd=deploy&one="+token, success:function(data){
                        $("#infod").addClass("infot");
                        $("#infod").html(data);
                    }
                });
                
        
    }
    function defsel(){
        if($("input[name='def']:checked").val()=="defa"){
            defs = $("#vactd").val();
            $("#defv").val("a|+|"+defs)
        }
    }
    function deftex(){
        if($("input[name='def']:checked").val()=="deft"){
            defs = $("#deftxt").val();
            $("#defv").val("t|+|"+defs)
        }
    }
    
    
    function startsel(){
        if($("input[name='start']:checked").val()=="starta"){
            defs = $("#vacts").val();
            $("#startv").val("a|+|"+defs)
        }
    }
    function starttex(){
        if($("input[name='start']:checked").val()=="startt"){
            defs = $("#starttxt").val();
            $("#startv").val("t|+|"+defs)
        }
    }
    $(document).ready(function(){
        <?php echo $cht; ?>
        <?php echo $vda;?>
        <?php echo $vsa;?>
        
    });
</script>
<div id="infod"></div>
<center>
    <h3>Token Setting</h3>
    <input type="text" id="token" class="form-control" value="<?php echo $tkn; ?>"><br/>
    <button class="btn tmb" onclick="checktoken();">Check Token</button><br/>
    <form method="post" id="tkn">
    <div id="infot" class='infot'></div>
    </form>
    </center>
    <hr/>
    
    <? echo $upd; ?>
    <form method="post" id="upd">
        <div class="form-group">
    <h3>Default Response</h3>
    <p>Response when the keyword did not set yet</p>
    <input type="radio" name="def" id="defa" value="defa" onclick="defsel();" <?echo $cda;?>>
    <label for="defa">
        From Action Set :
    </label>
        <select name="defact" class="form-control" id="vactd" onchange="defsel();">
            <?php echo $action; ?>
        </select>
    <br/>
    <input type="radio" name="def" id="deft" value="deft" onclick="deftex();" <?echo $cdt;?>>
    <label for="deft">
        From This Text :
    </label>
    <textarea type="text" id="deftxt" name="deftxt" class="form-control"onkeyup="deftex();"><?echo $vdt;?></textarea>
    <br/>
    <textarea style="display:none;" name="defv" class="form-control" id="defv"><?php echo $defv;?></textarea>
    <hr/>
    
    
    <h3>Welcome Message</h3>
    <p>Message when iser first time chat the bot</p>
    <input type="radio" name="start" id="starta" value="starta" onclick="startsel();" <?echo $csa;?>>
    <label for="starta">
        From Action Set :
    </label>
        <select name="startact" class="form-control" id="vacts" onchange="startsel();">
            <?php echo $action; ?>
        </select>
    
    <br/>
    <input type="radio" name="start" id="startt" value="startt" onclick="starttex();" <?echo $cst;?>>
    <label for="startt">
        From This Text :
    </label>
    <textarea type="text" id="starttxt" name="starttxt" class="form-control" onkeyup="starttex();"><?echo $vst;?></textarea>
    <br/>
    <textarea style="display:none;" name="startv" class="form-control" id="startv" ><?php echo $startv;?></textarea>
    
    <button type="submit" name="submit" value="update" class="btn tmb">Update</button>
    </div>
    </form>


<?php
include 'part/footer.php';
?>