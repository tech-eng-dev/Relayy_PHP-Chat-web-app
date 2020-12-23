<script>
	var challenge;
    
	function save_challenge(){
		challenge = $("#input_challenge").val();
		if(challenge == "") return;
		$(".Loading").show();
		$.ajax({
            url: site_url + "home/save_Challenge",
            data: {
              	challenge: challenge
            },
            success: function(data) {
                window.location.href = site_url; 
            },
            type: 'POST'
        });     
	}
	function skip_page(){
		window.location.href = site_url; 
	}


</script>
<div class="onboarding-container container-widget">
	<div class="row">
		<center><h5 class="gray-text">What is your number one business challenge right now? This will stay private, and the more you explain, the easier we can connect you with the right advisors.</h5></center>
	</div>
	<div class="row">
        <textarea class="border-style-xs radius-input" id="input_challenge" maxlength="1024" placeholder="Explain your biggest challenge..." style="max-width:100%;width:100%;height:150px;"></textarea>
	</div>
	
	<div class="row">
		
		<div class="col-sm-5 col-xs-12">
			<center><h5><button class="trans" onclick="skip_page()">Skip for now >></button></h5></center>
		</div>
		<div class="col-sm-7 col-xs-12">
			<center><h1><button class="btn" onclick="save_challenge()">SAVE & CONTINUE</button></h1></center>
		</div>
		
	</div>

	<div class="row">
		<h5><center class="Loading gray-text" style="display:none;">Loading questions...</center></h5>
	</div>

</div>


