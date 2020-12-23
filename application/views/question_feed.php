<script src="<?php echo asset_base_url()?>/js/questions.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/js/dist/hello.all.js"></script>


<script type="text/javascript">
    
    var email = "<?php echo $u_email ?>";
    var q_id = "<?= $feed['q_id']?>";
    var c_id = "<?= $current_id ?>";
    var group_image_name = "<?php echo isset($group_image_name)?$group_image_name:"" ?>";
    var group_name = "<?php echo isset($group_name)?$group_name:"" ?>";
    val_feed_alert = "<?= isset($feed_alert)?$feed_alert:0 ?>";
    var dialog;

    var router_ids = [];
    var accept_ids = [];
    var string = <?php echo json_encode($feed['r_ids'])?>;
    router_ids = JSON.parse(string);
    string = <?php echo json_encode($feed['a_ids'])?>;
    if(string.length > 0)accept_ids = JSON.parse(string);

    function onLinkedInLoad() {
      IN.UI.Authorize().place();
      IN.Event.on(IN, "auth", shareContent);
    }

  // Handle the successful return from the API call
    function onSuccess(data) {
      console.log(data);
      location.href = data.updateUrl;
      //$("#passButton").click();
    }

    // Handle an error response from the API call
    function onError(error) {
      console.log(error);
    }

    // Use the API call wrapper to share content on LinkedIn
    function shareContent() {
      var payload;
      if( 1 ){
         var articleUrl = encodeURIComponent(site_url + "questions/preview/" + q_id);
         var articleTitle = encodeURIComponent($("#feed-title").text());
         var articleSummary = encodeURIComponent('Relayy is on-demand advice for business owners. Questions are matched with advisors in private and secure messaging chats. Business owners get answers and advisors get business leads and connections.');
         var articleSource = encodeURIComponent(site_url + "assets/images/favicon.png");
         var goto = 'http://www.linkedin.com/shareArticle?mini=true'+
             '&url='+articleUrl+
             '&title='+articleTitle+
             '&summary='+articleSummary+
             '&source='+articleSource;
         window.open(goto, "LinkedIn", "width=320,height=500,scrollbars=no;resizable=no");
        
      }
      else{

        //Build the JSON payload containing the content to be shared(not work on safari);
        alert('Sharing');
        payload = {
          "comment": "Check out " + site_url,
          "content": {
            "title": "I think you can benefit from this lead on Relayy",
            "description": $("#linkedIn-message").val(),
            "submitted-url": site_url + "questions/preview/" + q_id,  
            "submitted-image-url": site_url + "assets/images/favicon.png"
          },
          "visibility": {
            "code": "anyone"
          }  
        }

        IN.API.Raw("/people/~/shares?format=json")
          .method("POST")
          .body(JSON.stringify(payload))
          .result(onSuccess)
          .error(onError);

      }
      // passFeed(q_id, c_id, true); 
      // dialog.close();
      //location.href = "http://www.linkedin.com/shareArticle?mini=true&url=SendMessageForm&title=SendMessageToYourConnections";
    }

</script>


<script>
      

      function fullSize(obj){
        $('.attached-Img').prop("class", "preview-Img attached-Img");
        $(obj).prop("class", "fullsize-Img attached-Img");    
      }

      function referToColleague(){
        dialog = BootstrapDialog.show({
            type: BootstrapDialog.TYPE_PRIMARY,
            title: "Refer to Colleague",
            message: '<div class="row padding_30">'+
                      '<h4 class="gray-text">Do you know someone who is qualified to answer this question?<br> Provide value to your colleagues by sharing this opportunity with them.Many times this results in a lead, or an opportunity to increase their network.</h4>'+
                    '</div>'+

                    '<div class="row">'+
                     '<center class="padding_xs"><button type="button" class="reviewlistingbtn padding_xs" onclick="ShareWithLinkedIn()" style="font-size:20px;">Sharing via LinkedIn</button></center>'+
                    '</div>'+

                    '<div class="row">'+
                      '<center class="padding_xs"><button type="button" class="reviewlistingbtn padding_xs" onclick="ShareWithEmail()" style="font-size:20px;">Sharing via Email</button></center>'+
                    '</div>'
            
        
       });
      }

      function ShareWithLinkedIn(){
        // BootstrapDialog.show({
        //     type: BootstrapDialog.TYPE_PRIMARY,
        //     title: "Refer to Colleague",
        //     message: '<div id="sendMessageForm">'+ 
        //                   '<textarea class="full-width padding_xs border1234 textview" type="text" id="linkedIn-message" placeholder="Type your message here...">'+
        //                   '</textarea>'+
        //               '</div>'+
        //               '<div id="sendMessageResult"><button type="button" class="reviewlistingbtn" onclick="shareContent()">Share</button></div>'
            
        
        // });
        shareContent();
      }


     
      function ShareWithEmail(){
        BootstrapDialog.show({
            type: BootstrapDialog.TYPE_PRIMARY,
            title: "Refer to Colleague",
            message: '<div class="row padding_30">'+
                      '<h4>Write a short message to your colleague, and we will include a unique link so that they can look at the question details.</h4>'+
                    '</div>'+

                    '<div class="container-widget padding_30">'+
                        '<div class="row"><p>Your Name: '+currentUser_name + '</p></div>'+
                        '<div class="row"><p>Your Email: '+email + '</p></div>'+

                        '<div style="margin-top:20px">'+
                          '<p>To Name:</p>'+
                          '<input   class="full-width padding_xs" type="text" id="refer-name">'+
                        '</div>'+

                        '<div style="margin-top:20px">'+
                          '<p>To Email:</p>'+
                          '<input   class="full-width padding_xs" type="text" id="refer-email">'+
                        '</div>'+

                        '<div style="margin-top:20px">'+
                          '<p>Personal Message:</p>'+
                          '<textarea class="full-width padding_xs border1234 textview" type="text" id="refer-message"></textarea>'+
                        '</div>'+
                    '</div>',
              buttons: [{
                  label: 'SEND',
                  cssClass: 'ob pull-right',
                  icon: 'glyphicon glyphicon-chevron-right',
                  autospin: true,                
                  action: function(dialogRef) {  
                    dialog = dialogRef;
                    SendReferEmail();
                  }

              },
              {
                  label: 'Cancel',
                  cssClass: 'rb pull-left',
                  icon: 'glyphicon glyphicon-remove',
                  autospin: true,                
                  action: function(dialogRef) {  
                    dialogRef.close();
                  }

              }]
        
       });
      }

      function SendReferEmail(){
        var dname = $("#refer-name").val();
        var demail = $("#refer-email").val();
        var dmessage = $("#refer-message").val();
        if(dname === "" || demail === "" || dmessage === ""){
          alert('All fields are required.');
          return;
        } 
        $.ajax({
           url: site_url + 'questions/refer',
           data: {
              fname: currentUser_name,
              femail: email,
              tname: dname,
              temail: demail,
              tmessage: dmessage,
              qid: q_id
           },
           success: function(data) {
              alert("Email was sent successfully!");
              dialog.close();
           },
           type: 'POST'
        });  
      }

      
</script>
<div class="profile-container">
<div class="container-widget" id="feed_width">

<?php if($state === 'NoFeed') {?>
              <div class="padding_sm gray-80">
                <center>
                  <img src="<?= asset_base_url().'/images/EmptyStateQ.png' ?>" width="150">
                </center>
                <div class="row padding_sm">
                <center>At this time, you don't have any questions. To get more questions, be sure to fill out your profile completely, especially your "Skills" and "Interested in" tags. Click on your name in the upper right to go to your profile.</center>
                <p></p>
                <center>Check out our Advisor Quick Guide to see how questions appear here, and how to ensure they turn into opportunities.</center>
                <center><a href="http://relayy.io/advisorquickguide"target="_blank" >http://relayy.io/advisorquickguide</a></center>
                </div>
              </div>      

<?php } else if($u_type != 2){?>

    <div class="row" style="margin-top:200px;">
      <p style="text-align:center;"> You can't preview this question, because you are not an advisor.</p>
    </div>
<?php } else {?>

  <div class="alert alert-success common_question_alert" role="alert" style="display:none">
    <div class="row experience">You've been added to the advisor list for this question. You'll get an email when the TeamUp chat is created. In the meantime, continue reviewing quesitons.</div>
    <div class="row">
      <button type="button" class="pull-left margin_xs gb min100" onclick="onFeedAlertOk()">Next Question</button>
      <button type="button" class="pull-left margin_xs grb" onclick="onFeedAlertNever()">Don't show this message again</button>
    </div>
  </div>  

  <div class="feed_section">

  <?php if($feed['q_id'] == 1){ ?>
  <div class="alert alert-danger" role="alert">
    <div class="row experience">
      This is a sample question, and is how questions will be displayed to you.<br>
      If this sample question is showing, you have viewed all of your questions in your queue, and you will be notified when there are more sent your way.
    </div>
  </div>
  <?php } ?>

  <div class="row desktop-visible-item" style="background:#DDD;margin-bottom:40px;">
        <div class="col-xs-4 feed-btn" style="padding:10px;">
          <button type="button" class="has-spinner rb stretch-Img" id="passButton" onclick="passFeed(this, '<?= $feed['q_id']?>', '<?= $current_id?>', 0, '<?= $body_class ?>')">
            <img class="pull-left" width="15" src="<?= asset_base_url().'/images/PassWhite.png' ?>"> PASS
            <div class="right-spin" id="pass-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div>
          </button>
        </div>
        <div class="col-xs-4 feed-btn"  style="padding:10px;">
          <button type="button" class="btn stretch-Img" id="referButton" onclick="referToColleague()" style="margin:0px;">
            <img class="pull-left" width="15" src="<?= asset_base_url().'/images/ReferWhite.png' ?>"> REFER TO COLLEAGUE
          </button>
        </div>
        <div class="col-xs-4 feed-btn"  style="padding:10px;">
          <button type="button" class="ob stretch-Img" id="joinButton" onclick="acceptFeed(this, '<?= $feed['email']?>', '<?= $feed['askerid']?>', '<?= $current_id?>', '<?= $feed['q_id']?>');">
            <img class="pull-left" width="15" src="<?= asset_base_url().'/images/ThumbsUpWhite.png' ?>"> JOIN TEAMUP
            <div class="right-spin" id="join-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div>
          </button>
        </div>        
  </div>

  <div class="row mobile-visible-item" style="background:#DDD;margin-bottom:40px;">
        <div class="col-xs-4 feed-btn" style="padding:10px;">
          <button type="button" class="has-spinner rb stretch-Img" id="passButton" onclick="passFeed(this, '<?= $feed['q_id']?>', '<?= $current_id?>', 0, '<?= $body_class ?>')">
            <img class="pull-left" width="15" src="<?= asset_base_url().'/images/PassWhite.png' ?>"> PASS
            <div class="right-spin" id="pass-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div>
          </button>
        </div>
        <div class="col-xs-4 feed-btn"  style="padding:10px;">
          <button type="button" class="btn stretch-Img" id="referButton" onclick="referToColleague()" style="margin:0px;font-size:17px;">
            <img class="pull-left" width="15" src="<?= asset_base_url().'/images/ReferWhite.png' ?>"> REFER
          </button>
        </div>
        <div class="col-xs-4 feed-btn"  style="padding:10px;">
          <button type="button" class="ob stretch-Img" id="joinButton" onclick="acceptFeed(this, '<?= $feed['email']?>', '<?= $feed['askerid']?>', '<?= $current_id?>', '<?= $feed['q_id']?>');">
            <img class="pull-left" width="15" src="<?= asset_base_url().'/images/ThumbsUpWhite.png' ?>"> JOIN
            <div class="right-spin" id="join-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div>
          </button>
        </div>             
  </div>


  
  <div class="container-widget white_back border1234">

      <div class="row padding_sm white_back question-body" style="margin:0px;">
          <div class="row">
            <div class="col-md-9 col-xs-12">
              <h4 class="context_title" id="feed-title">
                <?php if($feed['q_id'] == 1){
                        echo str_replace("Sample Question:", "<span class='pull-left' style='color:#F34E4E'>Sample Question:</span>", $feed['title']);
                      }
                      else{
                        echo $feed['title'];
                      }
                ?>
              </h4>
            </div>
            <?php if(strlen($group_name) > 0){ ?>
            <div class="col-md-3 col-xs-12">
              <h6 class="pull-right">From    <a title="group image"><img class="group_Img" src="<?= strlen($group_image_name)>0?uploads_base_url().$group_image_name:asset_base_url().'/images/emp.jpg' ?>"></a> <?= $group_name ?></h6>
            </div>
            <?php } ?>
          </div>
          
          <h6 class="Qinput gray-80" id="feed-context"><?= $feed['context']?></h6>



          <div class='row feed_toggle' style="display:none;">
            <h5 class="gray-80">Links attached</h5>
            <div class="row">
              <?php foreach(json_decode($feed['links']) as $link) { ?>
                <a href="<?= $link?>" target="_blank"><?= $link?></a>
              <?php } ?>
            </div>
            <h5 class="gray-80">Files Attached</h5>
            <?php foreach(json_decode($feed['filename']) as $file) {
                            $spl=explode('.',$file);
                            $file_ext = $spl[count($spl) - 1];
                            $expensions= array("jpeg", "jpg", "png", "PNG", "JPG", "JPEG");
                           ?>
                              <div class="pull-left preview-Img canvas image-Item">
                              <?php if(in_array($file_ext, $expensions)=== true){ ?>
                                <img class="preview-Img attached-Img" src="<?= uploads_base_url().$file?>" alt="<?= $file ?>"/>
                                <button type="button" class="expand-Img trans" style="display:none;"><span class="glyphicon glyphicon-zoom-in"></span></button>
                                <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                  <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                                </a>
                              <?php } else if($file_ext == "pdf") { ?>
                                <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/pdf.png' ?>" alt="<?= $file ?>"/>
                                <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                  <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                                </a>
                              <?php } else if($file_ext == "gif") { ?>
                                <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/gif.png' ?>" alt="<?= $file ?>"/>
                                <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                  <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                                </a>
                              <?php } else { ?>
                                <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/file.png' ?>" alt="<?= $file ?>"/>
                                <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                                  <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                                </a>
                              <?php } ?>
                                
                              </div>
              <?php } ?>
          </div>



          <div class="row" style="margin:0px 10px 0px 10px;">
            <div class="col-xs-6">
              <input type="button" class="pull-left btn" id="feed_detail" value="VIEW DETAILS" onclick="viewFeedDetail()"/>
            </div>
            <div class="col-xs-6">
              <p class="desktop-visible-item col-md-6 col-sm-6 col-xs-12 pull-right gray-80" style="margin-top:5px; text-align:right;" id="feed-time"><?= date("D M d, Y h:i A", $feed['time']) ?></p>
              <p class="mobile-visible-item col-md-6 col-sm-6 col-xs-12 pull-right gray-80" style="margin-top:5px; text-align:center;" id="feed-time"><?= date("D M d, Y h:i A", $feed['time']) ?></p>
            </div>
          </div>


          <div class="row" style="margin:0px;">
            <h5 class="gray-80">TAGS</h5>
          </div>
          <div class="row" style="padding:5px;">
            <?php foreach(json_decode($feed['tags']) as $tag){?>
              <div class="online_tags Qinput pull-left"><?= $tag ?></div>
            <?php } ?>
          </div>

        </div>

        <div class="row border1 padding_sm" style="margin:0px;">
          <div class="col-md-2 col-sm-2 col-xs-4 gray-80">ADVISORS ANSWERING:</div>
          <div class="col-md-9 col-sm-8 col-xs-12">
            <?php if($feed['a_ids']) {?>
              <?php foreach(json_decode($feed['a_ids']) as $joiner){?>
                <?php foreach($advisors as $advisor){?>
                  <?php if($joiner === $advisor['id']){?>     
                    <a class="" href="<?= site_url("profile/user/".$advisor['id'])?>" title="<?= $advisor['fname'] ?> <?= $advisor['lname'] ?>">                   
                    <img  style="vertical-align:middle;" class="round" width="40" height="40" src="<?= strlen($advisor['photo'])>0?$advisor['photo']:asset_base_url().'/images/emp.jpg' ?>" /></a>                        
                  <?php }?>
                <?php }?>                     
              <?php }?>  
            <?php }?>
          </div>       
        </div>
     

      <div class="row border1">

            <div class="col-xs-6" style="padding:0px;"><button type="button" class="full-width" id="presonal_button" onclick="viewPersonalProfile()" style="height:60px;">PERSONAL PROFILE</button></div>
            <div class="col-xs-6" style="padding:0px;"><button type="button" class="full-width" id="business_button" onclick="viewBusinessProfile()" style="height:60px;">BUSINESS PROFILE</button></div>

      </div>

      <div class="white_back row padding_sm" id="Profile-Page"  style="margin:0px;">

        
          <div class="row">
            <div class="col-sm-2 col-xs-4">
              <a class="" href="<?= site_url("profile/user/".$feed['askerid'])?>"> 
              <img src="<?= strlen($feed['photo'])>0?$feed['photo']:asset_base_url().'/images/emp.jpg'?>" width="70" height="70" class="round"/></a>
            </div>
            <div style="padding:10px;" class="col-sm-10 col-xs-8">
              <p class="font-20"><?= $feed['fname']?>  <?= $feed['lname']?>, <?= isset($location)?$location:"-" ?></p>
              <p class="gray-text"><?php echo $feed['bio']?$feed['bio']:"None" ?></p>
              <p class="gray-text wrapword">LinkedIn:  <?php echo strlen($feed['public_url'])>0?'<a href="'. $feed['public_url'].'" target="_blank">'.$feed['public_url'].'</a>':'-' ?> </p>
            </div>
            
          </div>

      </div>
      
      
        <div class="row border1 white_back" id="personal-section" style="font-size:16px;">

              <div class="col-md-4 col-xs-12 border2" id="position-field" style="padding:20px;">
                  <div class="col-xs-12">
                    <p class="pull-left gray-80">EXPERIENCE:</p>
                  </div>
                  <div>
                  <?php if(isset($position) && $position !== "" && $position !== "[]") { ?>
                  <?php foreach(json_decode($position) as $pos) { ?>
                    <div class="col-xs-12"><h6><img src="<?= asset_base_url().'/images/list-disc.png'?>" class="pull-left m5"><?= $pos ?></h6></div>
                  <?php } ?>
                  <?php } else { echo "<h6 class='empty_skill'> There is no data</h6>"; }?>
                  </div>
              </div>
              <div class="col-md-4 col-xs-12 border2" id="education-field" style="padding:20px;">
                  <div class="col-xs-12">
                    <p class="pull-left gray-80">EDUCATION:</p>
                  </div>
                  <div>
                  <?php if(isset($education) && $education !== "" && $education !== "[]") { ?>
                  <?php foreach(json_decode($education) as $edu) { ?>
                    <div class="col-xs-12"><h6><img src="<?= asset_base_url().'/images/list-disc.png'?>" class="pull-left m5"><?= $edu ?></h6></div>
                  <?php } ?>
                  <?php } else { echo "<h6 class='empty_skill'> There is no data</h6>"; }?>
                  </div>
              </div>

              <div class="col-md-4 col-xs-12" id="skill-field" style="padding:20px;">
                <div class="col-xs-12">
                  <p class="pull-left gray-80"> CURRENTLY SEEKING:</p>
                </div>
                <div>
                    <?php if(isset($interesting) && $interesting !== "" && $interesting !== "[]") { ?>
                    <?php foreach(json_decode($interesting) as $node) { ?>
                      <div class="online_tags pull-left" id="li_skill"><?= $node ?><a class="close more-close" style="display:none;" onclick="skill_Remove(this)">&times;</a></div>
                    <?php } ?>
                    <?php } else { echo "<h6 class='empty_skill'> There is no data</h6>"; }?>
                </div>
              </div>
        </div>


        <div class="row border1 last_div" id="business-section">
          <?php if($asker_type == 3){ ?>

          <div class="col-md-6 col-xs-12 border2" id="business-section-left" style="padding:20px;">

              <div class="row Qinput">
                <div class="col-md-3 col-xs-12">
                  <p class="gray-80">NAME: </p>
                </div>
                <div class="col-md-9 col-xs-12">
                  <p><?= $venture_name ?></p>
                </div>
              </div>

              <div class="row Qinput">
                <div class="col-md-3 col-xs-12">
                  <p class="gray-80">SUMMARY: </p> 
                </div>
                <div class="col-md-9 col-xs-12">
                  <p><?= $summary ?></p>
                </div>
              </div>

              <div class="row Qinput">
                <div class="col-md-3 col-xs-12">
                  <p class="gray-80">BUSINESS STAGE: </p> 
                </div>
                <div class="col-md-9 col-xs-12">
                  <p><?= $stage ?></p>
                </div>
              </div>

              <div class="row Qinput">
                <div class="col-md-3 col-xs-12">
                  <p class="gray-80">FUNDING STAGE: </p>
                </div>
                <div class="col-md-9 col-xs-12">
                  <p><?= $funding ?></p>
                </div>
              </div>

          </div>

          <div class="col-md-6 col-xs-12" id="business-section-right" style="padding:20px;">

              <div class="row Qinput">
                <div class="col-md-3 col-xs-12">
                  <p class="gray-80">INDUSTRY: </p> 
                </div>
                <div class="col-md-9 col-xs-12">
                  <p><?= $industry ?></p>
                </div>
              </div>

              <div class="row Qinput">
                
                <div class="col-md-3 col-xs-12">
                  <p class="gray-80">EMPLOYEES: </p>
                </div>
                <div class="col-md-9 class="ot" col-xs-12">
                  <p><?= $employee_num ?></p>
                </div>
              </div>

              <div class="row Qinput">
                <div class="col-md-3 col-xs-12">
                  <p class="gray-80">LINKS:</p> 
                </div>
                <div class="col-md-9 col-xs-12 wrapword">
                  <?php foreach($array_link as $link){
                    echo '<p><a href="'.$link['link'].'">'.$link['link'].'</a></p>';
                  }
                  ?>
                </div>
              </div>

          </div>

        
        <?php } else { ?>
        <div class="row" style="padding:20px;">
          <div class="col-md-3 col-sm-4 col-xs-5 sm-button"><p class="gray-80">Company Name:</p></div>
          <div class="col-md-9 col-sm-8 col-xs-7 italic_value"><p><?= isset($company->company->name)?$company->company->name:"-" ?></p></div>
        </div>

        <div class="row" style="padding:20px;">
          <div class="col-md-3 col-sm-4 col-xs-5 sm-button"><p class="gray-80">Location:</p></div>
          <div class="col-md-9 col-sm-8 col-xs-7 italic_value"><p><?= isset($company->location->name)?$company->location->name:"-" ?></p></div>
        </div>

        <div class="row" style="padding:20px;">
          <div class="col-md-3 col-sm-4 col-xs-5 sm-button"><p class="gray-80">Summary:</p></div>
          <div class="col-md-9 col-sm-8 col-xs-7 italic_value"><p><?= isset($company->summary)?$company->summary:"-" ?></p></div>
        </div>
        <?php } ?>
      </div>
  </div>
  </div>
</div>

<?php } ?>

</div>
</div>
<script>

    function viewPersonalProfile(){
      $("#personal-section").show();
      $("#business-section").hide();
      $("#presonal_button").css("background","transparent");
      $("#presonal_button").css("border","0px");
      $("#business_button").css("background","#DDD");
      $("#business_button").css("border-left","1px solid #BBB");
      $("#business_button").css("border-bottom","1px solid #BBB");
      match_sections();
    }

    function viewBusinessProfile(){
      $("#personal-section").hide();
      $("#business-section").show();
      $("#business_button").css("background","transparent");
      $("#business_button").css("border","0px");
      $("#presonal_button").css("background","#DDD");
      $("#presonal_button").css("border-right","1px solid #BBB");
      $("#presonal_button").css("border-bottom","1px solid #BBB");
      match_sections();
    }

    function match_sections(){
          var value;
          var space = 5;
          if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            console.log('mobile');
          }
          else{
            var array = [$("#position-field").height(), $("#education-field").height(), $("#skill-field").height()];
            var mh = Math.max.apply(Math, array) + 40;
            document.getElementById("position-field").style.height=mh+'px';
            document.getElementById("education-field").style.height=mh+'px';
            document.getElementById("skill-field").style.height=mh+'px';


            if($("#business-section-left").height() > $("#business-section-right").height()){
              $("#business-section-right").height($("#business-section-left").height());
            }
            else{
              $("#business-section-left").height($("#business-section-right").height());
            }
          }

      }

 
    viewPersonalProfile();

    guiders.createGuider({
      attachTo: "#joinButton",
      buttons: [{name: "Got it!", onclick: guiders.hideAll}],
      description: "A short term chat in which advisors help answer a question.\nWhen you click 'Join TeamUp', you should normally be invited to a chat within 24hours.",
      id: "guider1",
      next: "guider2",
      position: 12,
      title: "TeamUp Chat:",
      width: $("#feed_width").width()/3
    });//.show();

    guiders.createGuider({
      attachTo: "#referButton",
      buttons: [{name: "next"}],
      description: "Description about refer button",
      id: "guider2",
      next: "guider3",
      position: 6,
      title: "Refer Button",
      width: 300
    });

    guiders.createGuider({
      attachTo: "#joinButton",
      buttons: [{name: "close", onclick: guiders.hideAll}],
      description: "Description about join button",
      id: "guider3",
      next: "guider2",
      position: 6,
      title: "Join Button",
      width: 300
    });

    function onFeedAlertOk(){
        $(".feed_section").show();
        $(".common_question_alert").hide();
    }
    function onFeedAlertNever(){
        val_feed_alert = 0;
        $.ajax({
           url: site_url + 'questions/NeverFeedAlert',
           data: {
              u_id: currentUser_id
           },
           success: function(data) {
              $(".feed_section").show();
              $(".common_question_alert").hide();      

           },
           type: 'POST'
        });         
    }
</script>





