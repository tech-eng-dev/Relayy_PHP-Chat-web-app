<div class="profile-container">
<div class="container-widget" style="height:100vh;padding:0px 30px 0px 30px;">

<?php 
  if ($u_status == 2) {?>
  <div class="alert alert-info">
    <strong>Congratulations!</strong><br> You've been registered and activated automatically. Please fill your profile details.
  </div>
<?php } else if ($u_status != 1) {?>
  <div class="alert alert-warning">
    <strong>Sorry!</strong> Your account is not approved by admin. Please wait for admin's action.
  </div>
<?php }
?>

  
<form method="post" id="edit_profile_form" action="<?php echo site_url('profile/save/'.$user['id'])?>">
  <div class="row">

    <div class="col-xs-12 upload-image user-type">

      <div class="image-wrap text-center" style="margin:0 auto;">
        <img id="user_pic" class="img-responsive round" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp.jpg'?>" style="width:100px; height:100px; margin:0 auto;">

        <input id="user_pic_info" type="hidden" name="picture" value="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp.jpg'?>">

        <input id="img-file" type="file" name="files[]" multiple style="margin:0 auto;">

      </div>

    </div>

  </div>
  <div class="row">
  
  <div class="account-info container-widget">
    <div class="row" style="margin-top:30px;">
      <div class="col-sm-2 col-xs-4" style="text-align:center;margin-top:5px;">First Name:</div>
      <div class="col-xs-8">
        <input type="text" name="fname" id="ep_fname" class="form-control" placeholder="First Name" required="true" value="<?= $user['fname']?>" autocomplete="off"/>
      </div>

    </div>

    <div class="row" style="margin-top:10px;">
      <div class="col-sm-2 col-xs-4" style="text-align:center;margin-top:5px;">Last Name:</div>
      <div class="col-xs-8">
        <input type="text" name="lname" id="ep_lname" class="form-control" placeholder="Last Name" required="true" value="<?= $user['lname']?>" autocomplete="off"/>
      </div>
    </div>

    <div class="row" style="margin-top:10px;">

      <div class="col-sm-2 col-xs-4" style="text-align:center;margin-top:5px;">Email:</div>

      <div class="col-xs-8">
        <input type="email" name="email" class="form-control" placeholder="Email" required="true" value="<?= $user['email']?>"/>
      </div>

    </div>
    
    
    <div class="row" style="margin-top:10px;">

      <div class="col-sm-2 col-xs-4" style="text-align:center;margin-top:5px;">Bio:</div>

      <div class="col-xs-8">
        <textarea name="bio" class="form-control" required="true"><?= $user['bio']?></textarea>
      </div>

    </div>

    <div class="row" style="margin-top:10px;">

      <div class="col-sm-2 col-xs-4" style="text-align:center;margin-top:5px;">Location:</div>

      <div class="col-xs-8">
        <textarea name="location" class="form-control" required="true"><?= $user['location']?></textarea>
      </div>

    </div>

    <div class="row" style="margin-top:10px;">

      <div class="col-sm-2 col-xs-4" style="text-align:center;margin-top:5px;">LinkedIn:</div>

      <div class="col-xs-8">
        <textarea name="linkedIn" id="edit_linkedIn" class="form-control" required="true"><?= $user['public_url']?></textarea>
      </div>

    </div>

    <div class="row" style="margin-top:10px;">     

        <div class="col-xs-12" style="padding-right:20px;">
          <input onclick="checkChangedData()" class="btn btn-primary pull-right" value="Save" style="width:100px;">
        </div>

    </div>
    
  </div>

  </form>
  </div>
</div>
</div>

<script>
function checkChangedData(){
  var public_url = $("#edit_linkedIn").val();
  //alert(public_url);
  if($("#ep_fname").val().length == 0){
    alert("First Name is empty.");
    return;
  }
  else if($("#ep_lname").val().length == 0){
    alert("Last Name is empty.");
    return;
  }
  else if(public_url.indexOf("http://") < 0 && public_url.indexOf("https://") < 0 && public_url.length > 0){
    alert("Invalid Url! You must Copy/Paste your full link address.");
    return;
  }else{
    $("#edit_profile_form").submit();
  }
}
</script>


