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
			
			function save_advisor_interesting(){
				if(array_tags.length == 0) return;
				$(".Loading").show();
				$.ajax({
		            url: site_url + "home/save_advisor_interesting",
		            data: {
		              	interesting_in: JSON.stringify(array_tags)
		            },
		            success: function(data) {
		                location.href = site_url;
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
				<center><h5 class="gray-text">What are you interested in? Who are you interested in helping? This will help us find the right questions and people for you.</h5></center>
				<center><h5 class="gray-text">E.g. restaurant accounting, tech marketplaces, Duke students, etc.</h5></center>
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
				<center><h1><button class="btn" onclick="save_advisor_interesting()">SAVE & CONTINUE</button></h1></center>
			</div>
			<div class="row">
				<center><h5><button class="trans" onclick="skip_page()">Skip for now >></button></h5></center>
			</div>

			<div class="row">
				<h5><center class="Loading gray-text" style="display:none;">Loading questions...</center></h5>
			</div>
		</div>


