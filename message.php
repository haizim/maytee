<?php
$judul = "Direct Message";

include 'part/header.php';
$q = "select * from user order by last_dm desc";
$get = mysqli_query($konek,$q);
?>
<script>
    $(document).ready(function(){
        
        function tampildata(id){
                
                $.ajax({
                    type:"POST", url:"bot.php", data:"cmd=show&one="+id, success:function(data){
                        $("#tampilkan").html(data);
                    }
                });
            }
    });
</script>

<form method="post" action="c_message.php">
    <?php 
    while($hasil = mysqli_fetch_array($get)){
        $one = $hasil['id'];
        $qn = "select * from log where (source='MS' or source='BC') and user='$one'";
        $runr = mysqli_query($konek, $qn);
        $numr = mysqli_num_rows($runr);
      ?>
      <div class="row" style="border-bottom : 1px solid #777; padding:5px 0px">
    <div class="col-sm-9">
        <h5 style="font-weight:700;"><?php echo $hasil['nama']."(".$numr.")"; ?></h5>
        <small><?php echo $hasil['last_dm']; ?></small>
    </div>
    <div class="col-sm-3">
        <button type="submit" name="chat" class="btn btn-block tmb" value="<?php echo $hasil['id']; ?>">Chat</button>  
    </div>
    </div>
    <?php  
    }
    ?>
</form>