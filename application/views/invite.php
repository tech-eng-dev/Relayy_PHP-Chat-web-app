
<script src="<?php echo asset_base_url()?>/libs/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/bootstrap.min.js" type="text/javascript"></script>

<script src="<?php echo asset_base_url()?>/libs/quickblox.min.js"></script>
<script src="<?php echo asset_base_url()?>/js/bootstrap-dialog.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/js/config.js"></script>
<script src="<?= asset_base_url()?>/js/page_home.js"></script>

<script type="text/javascript">
    var userId = '<?php echo $current_id ?>';
    var userType = '<?php echo $current_type ?>';
    var platform;
    var LData = '<?php echo isset($LData)?$LData:"" ?>';
    var id = 0;//unused now
    var l_email, l_fname, l_lname, l_pictureUrl, l_headline, l_location, l_publicUrl, l_company;
    var log_state;// 1: sign in   0: sign out

    //history.pushState({}, null, site_url + 'invite/user');
    function onLinkedInClk() {
        $.ajax({            
           url: site_url + 'home/save_log_state',
           data: {
              log_state: 2
           },
           success: function(data) {
              location.href = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='+linkedIn_API+'&redirect_uri='+link_url+'home%2Flinkedin_callback&state=DCEeFWf456666fKef477&scope=r_basicprofile%20r_emailaddress';
           },
           type: 'POST'
        });
    }

    function InviteUserSignUp(){
        var type = 0;
        if($("#inviteuser_role").val() === "Admin") type = 1;
        else if($("#inviteuser_role").val() === "Advisor") type = 2;
        else if($("#inviteuser_role").val() === "Entreprenuer") type = 3;
        else type = 4;

        $.ajax({            
           url: site_url + 'invite/check_email',
           data: {
              email: l_email,
              id: userId
           },
           success: function(data) {
              if(data.indexOf("active_user") > -1){
                alertstate("You can't signup with this LinkedIn account because it already belongs to a Relayy user: " + data.split("/")[1] + ", " + data.split("/")[2] + ". Please log out of LinkedIn, click the sign up button again, and sign up with a different LinkedIn account.");
                $("#validating_text").text("Sign up failed!");
                return;
              }
              else if(data == "invalid"){
                alertstate("This url is invalid now. Perhaps your invited was deleted.");
                return;
              }
              else{
                registerInvitedUser(userId, l_email, l_fname, l_lname, l_pictureUrl,
                              l_headline, type, l_location, l_publicUrl, l_company);
              }
           },
           type: 'POST'
        });

        
    }

</script>




<div id="main" class="main blue_main">

    <center>

        <div class="padding_md">
          <center style="color:white;"><h4 id="validating_text"></h4></center>
        </div>

        <div class="container-widget invite_up_div">
            <div class="row border3" style="margin:0px;">
              <div class="col-xs-offset-2 col-xs-8 padding_sm">
                <center><img style="width:100%;" src="<?php echo asset_base_url()?>/images/logo.jpg"></center>
              </div>
            </div>

            <div class="row border3" style="margin:0px;">

                <div class="row" style="margin:0px;">
                    <center><h3 class="hi_tit">You have been invited to join Relayy.</h3></center>
                </div>
                
                <div class="row invite_up_div" style="min-width:250px;margin:0px;">
                    <div class="row">
                        <center>
                        <h5 class="col-sm-4 control-label" style="padding:0px;">User Role:</h5>
                        <div class="col-sm-6 selectContainer">
                            <select class="form-control" id="inviteuser_role" <?php echo $current_type > 10?"disabled='disabled'":"" ?>>
                                <?php if($current_type > 10) { ?>
                                    <option <?php echo $current_type % 10 == 1?"selected":"" ?>>Admin</option>
                                <?php } ?>
                                <option <?php echo $current_type % 10 == 2?"selected":"" ?>>Advisor</option>
                                <option <?php echo $current_type % 10 == 3?"selected":"" ?>>Entreprenuer</option>
                                <option <?php echo $current_type % 10 == 4?"selected":"" ?>>Moderator</option>
                            </select>
                        </div>
                        </center>
                    </div>
                        
                    <div class="row padding_sm">
                        <button type="button" class="btn btn-primary btn-lg btn-block facebook"  onclick="onLinkedInClk()">Sign up with LinkedIn</button>
                    </div>

                    <div class="row lp"><center class="uline" id="whysignup">Why must I sign up with LinkedIn?</center></div>
                    <div class="row padding_sm">
                        <center>By clicking "Sign Up" you indicate that you have read and agree to the <a href="http://relayy.io/termsofservice" target="_blank">Terms of Service</a> and <a href="http://relayy.io/privacypolicy" target="_blank">Privacy policy</a>.</center>
                    </div>

                </div>
            </div>

            <div class="row padding_sm" style="margin:0px;">
                <center><h2 style="color:#72b7f8;">WHAT IS RELAYY?</h2></center>
                <center>Relayy is on-demand advice for business owners. Questions are matched with advisors in private and secure messaging chats. Business owners get answers and advisors get business leads and connections.</center>
                <center>Learn more about Relayy here: <a href="http://relayy.io" target="_blank">http://relayy.io</a></center>
            </div>
        </div>



      </center>
</div>

<script>
  $("#whysignup").hover(function(){
    HoverOnWhy();
  }, function(){
    HoverOutWhy();
  });

  function HoverOnWhy(){
    guiders.createGuider({
      attachTo: "#whysignup",
      buttons: [],
      description:  '<div class="row padding_sm" style="font-family:\'proximanovar\';">'+
                    '<p>Why must I sign up with LinkedIn?<p>'+
                    '<p style="margin-left:30px;">Enhances trust between members. You know exactly who you are talking with.<br>Saves you time during the sign up process</p>'+
                    '<br>'+
                    '<p>**NOTHING will get shared on LinkedIn. You are the only one that can share anything about Relayy on LinkedIn.</p>'+
                    '</div>',
      id: "whysignupwithlinkedIn",
      position: 6,
      title: "",
      width: 300
    }).show();
  }

  function HoverOutWhy(){
    $("#whysignupwithlinkedIn").hide();
  }

  $(document).ready(function() {   

      if(LData.length > 0){
        l_email = '<?php echo isset($LData)?$email:"" ?>';
        l_fname = '<?php echo isset($fname)?$fname:"" ?>';
        l_lname = '<?php echo isset($lname)?$lname:"" ?>';
        l_pictureUrl = '<?php echo isset($pictureUrl)?$pictureUrl:"" ?>';
        l_headline = '<?php echo isset($headline)?$headline:"" ?>';
        l_location = '<?php echo isset($location)?$location:"" ?>';
        l_publicUrl = '<?php echo isset($publicUrl)?$publicUrl:"" ?>';
        l_company = '<?php echo isset($companyInfo)?$companyInfo:"" ?>';
        $("#validating_text").text("Checking your data for Relayy...");
        setTimeout(function(){
          InviteUserSignUp();
        }, 1000);
        
      }
      
  });


</script>

