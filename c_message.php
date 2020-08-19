<?php
$id = $_POST['chat'];

include "part/konek.php";


$qn = "select * from user where id=$id";
$getn = mysqli_query($konek, $qn);
$hasiln = mysqli_fetch_array($getn);


$judul = "Chat to ".$hasiln['nama'];

include 'part/header.php';
?>
<script>
    function showchat(){
                id = <?php echo $id; ?>;
                $.ajax({
                    type:"POST", url:"bot.php", data:"cmd=show&one="+id, success:function(data){
                        $("#chat").html(data);
                        
                    }
                });
            }
    
    function send(){
        msg = $("#snd").val();
        id = <?php echo $id; ?>;
        $.ajax({
                    type:"POST", url:"bot.php", data:"cmd=send&one="+id+"&two="+msg, success:function(data){
                        $("#chat").html(data);
                        showchat(<?php echo $id; ?>);
                        
                        $("#msg").val("")
                    }
                });
                
        
    }    
    
    function checkmsg(){
        jml=$("#jml").val();
        id = <?php echo $id; ?>;
        
        $.ajax({
                    type:"POST", url:"bot.php", data:"cmd=checkmsg&one="+id, success:function(data){
                        if(data!=jml){
                            console.log("data = "+data);
                            console.log(typeof data);
                            console.log("-------------------")
                            console.log("jml = "+jml);
                            console.log(typeof jml);
                            console.log("===================")
                            showchat(<?php echo $id; ?>);
                        }
                    }
                });
    }
    
    function cpt(){
        
        valu = document.getElementById("type").value;
        
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
            
        document.getElementById("one").innerHTML = capt1+" :";
        document.getElementById("two").innerHTML = capt2+" :";
        return capt1+" - "+capt2;
    }
    
    function atch(){
        type = $("#type").val();
        one = $("#vone").val();
        two = $("#vtwo").val();
        
        snd = type+"|mw|"+one+"|mw|"+two;

        $("#snd").val(snd);
        send();
        $("#conf").html("This "+type+" has been sent");
    }
    
    function txt(){
        type = "message";
        one = "-";
        two = $("#msg").val();
        
        snd = type+"|mw|"+one+"|mw|"+two;
        
        $("#snd").val(snd);
        
    }
    $(document).ready(function(){
        
        var intervalID = window.setInterval(checkmsg, 500);
        
    
        showchat();
        document.getElementById("bawah").scrollIntoView();
    });
</script>
<div id="chat"></div>
<textarea type="text" style="display:none;" class="form-control" name="snd" id="snd"></textarea>
<div id="bawah"></div>

<div class="fix-bawah">
  <div class="row" style="width:100%">
      <div class="col-10">
          
          <textarea type="text" class="form-control" name="msg" id="msg" onkeyup="txt();"></textarea>
      
      </div>
      <div class="col-1">
          <button class="btn tmb" type="submit" onclick="send();"><i class="fas fa-paper-plane"></i></button>
          <br/>
          <button class="btn tmbx" type="submit" data-toggle="modal" data-target="#attachment" onclick="$('#conf').html('');"><i class="fas fa-paperclip"></i></button>
      </div>
  </div>
</div>

<div class="modal fade" id="attachment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Send Attachment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <i id="conf"></i>
        <p id="act1" >Action :</p>
             <select name="act" class="form-control" id="type" onchange="cpt()">
                <option value="photo" selected>Photo</option>
                <option value="video">Video</option>
                <option value="audio">audio</option>
                <option value="document">Document</option>
                <option value="location">Location</option>
                <option value="contact">Contact</option>
            </select><br/>
            <p id="one" >URL :</p>
            <input type="text" id="vone" class="form-control" name="one"/>
            <p id="two" >Caption :</p>
            <input type="text" id="vtwo" class="form-control" name="two"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="atch();">Send</button>
      </div>
    </div>
  </div>
</div>
<?php
include 'part/footer.php';
?>