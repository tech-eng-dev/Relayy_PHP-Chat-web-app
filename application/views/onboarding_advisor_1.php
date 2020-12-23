<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
      <title><?php if(isset($page_title)) echo $page_title; ?></title>
      <link rel="shortcut icon" href="<?= asset_base_url()?>/images/favicon.png">

      <!--reset styles-->
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/bootstrap.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/style.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/font-awesome.min.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/chat.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/demo.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/defaults.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/bootstrap-dialog.min.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive1.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style2.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/main.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/font-awesome.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/guiders.css" type="text/css">
      
      <script src="<?php echo asset_base_url()?>/libs/jquery.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/guiders.js" type="text/javascript"></script>
      <script>var site_url = "<?php echo site_url() ?>";</script>
    </head>
    <body class="onboarding-content <?php if(isset($body_class)) echo $body_class; ?>">
   	<div class="onboarding-content onboarding-body">
		<div class="onboarding-container container-widget">
			<div class="row">
				<center><h1 class="gray-text">Welcome to Relayy, <?= $u_fname ?></h1></center>
			</div>
			<div class="row">
				<center><h5 class="gray-text">In which business topics are you most skilled? List a few here. This will help us better match you to questions fitting your skillsets.</h5></center>
			</div>
			<div class="row">
				<center>
					<div class="col-sm-9">
		            <input type="text" class="border-style-xs radius-input" id="input_tags" placeholder="e.g. marketing, accounting, sales" onkeypress="detect_typing(event, this)" style="width:100%;">
		            </div>
		            <div class="col-sm-3">
		              <button type="button" class="btn radius-input" onclick="addTag()" style="width:100%;margin:0px;">+ ADD</button>
		            </div>
				</center>
			</div>
			<div class="row section-seeking" id="tag_container" style="min-height:100px;">
			</div>
			<div class="row">
				<center><h1><button class="btn" onclick="save_advisor_Skill()">SAVE & CONTINUE</button></h1></center>
			</div>
			<div class="row">
				<center><h5><button class="trans" onclick="skip_page()">Skip for now >></button></h5></center>
			</div>
		</div>
		<script>
			var array_tags = [];
			function detect_typing(e, object) {
		        var key=e.keyCode || e.which;
		        if (key==13){
		            addTag(); 
		      }
		    }

		    function addTag(){
			    var skl = $("#input_tags").val();
			    if(skl === "") return;
			    htmlTxt = '<div class="online_tags pull-left">'+ skl +'<a class="close more-close" onclick="removeTag(this)">&times;</a></div>';
			    $("#tag_container").append(htmlTxt);
			     array_tags.push(skl);
			     document.getElementById('input_tags').value="";
			}

			function removeTag(obj) {
			    var strTxt = $(obj).parent().text();
			    strTxt = strTxt.substring(0,strTxt.length - 1);
			    var index = array_tags.indexOf(strTxt);
			    array_tags.splice(index, 1);
			    $(obj).parent().remove();
			}
			
			function save_advisor_Skill(){
				if(array_tags.length == 0) return;
				$.ajax({
		            url: site_url + "home/save_advisor_Skill",
		            data: {
		              	skill: JSON.stringify(array_tags)
		            },
		            success: function(data) {
		                $(".onboarding-body").html(data);
		            },
		            type: 'POST'
		        });     
			}
			function skip_page(){
				$.ajax({
		            url: site_url + "home/switchTo_onboarding_advisor_2",
		            data: {
		              
		            },
		            success: function(data) {
		                $(".onboarding-body").html(data);
		            },
		            type: 'POST'
		        });      
			}


		</script>
	</div>
	</body>
</html>


