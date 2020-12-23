<script>

	var gname = "<?= $u_group?>";
	var utype = "<?= $u_type ?>";

	function AllUsers(){
		$("#filter_by").text("All");
		if(utype == 1){
			$(".mng_user_cell").show();
		}
		else{
			$(".mng_user_cell").hide();
			$(".mng_user_cell").each(function(){			
	    		if($(this).attr("data-group") === gname) $(this).show();
	    	});
		}
	}

	function PendingUsers(){
		$("#filter_by").text("Pending");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){			
    		if($(this).attr("data-status") == "0") $(this).show();
    	});
	}

	function ActiveUsers(){
		$("#filter_by").text("Activated");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-status") == "1") $(this).show();
    	});
	}

	function InvitedUsers(){
		$("#filter_by").text("Invited");
		$(".mng_user_cell").hide();
		if(utype == 1){
			$(".mng_user_cell").each(function(){
	    		if($(this).attr("data-status") == "2") $(this).show();
	    	});
		}
		else{
			$(".mng_user_cell").each(function(){
	    		if($(this).attr("data-status") == "2" && $(this).attr("data-group") === gname) $(this).show();
	    	});
		}		
	}

	function SearchUsers(){
		$("#filter_by").text("Search Relayy Users");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-status") == "1") $(this).show();
    	});
	}

	function Entrepreneurs(){
		$("#filter_by").text("Entrepreneurs");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-type") == "3" && $(this).attr("data-group") === gname) $(this).show();
    	});
	}

	function Advisors(){
		$("#filter_by").text("Advisors");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-type") == "2" && $(this).attr("data-group") === gname) $(this).show();
    	});
	}

	function Moderators(){
		$("#filter_by").text("Moderators");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-type") == "4" && $(this).attr("data-group") === gname) $(this).show();
    	});
	}

	var manual_dialog;

	function add_user_manually(){
		$("#AddUserDialog").modal("show");
	}

	function register_user_manually(fname, lname, email, role, uid, group){
		$.ajax({
	             url: site_url + 'users/add_user_manually',
	             data: {
	                fname: fname,
	                lname: lname,
	                email: email,
	                role: role,
	                uid: uid,
	                group: group

	             },
	             success: function(data) {
	             	if(data == ""){

	             	}	     
	                location.reload();
	             },
	             type: 'POST'
	    });
	}

	function onRegisterUser(){
		var fname = $("#m_user_fname").val();
		var lname = $("#m_user_lname").val();
		var email = $("#m_user_email").val();
		var role = $("#m_user_role").val();
		var group = $("#m_user_group").val();

		if(fname === "" || lname === "" || email === ""){
			alert("All fields are required!");
			return;
		}

		  var params = { 'login': email, 'password': QBApp.authKey, 'full_name': fname+" "+lname, 'email': email };
		  var filters = {filter: { field: 'email', param: 'eq', value: email }};

		  QB.users.listUsers(filters, function(err, result){
		    if (result && result.items.length> 0) {      //=======Old User in QuickBlox
		      var user = result.items[0];

		      $.ajax({
		       url: site_url + 'home/link',// check email is live or not
		       data: {
		          email: email
		       },
		       success: function(data) {
		          if (data == 11 || data == 4) {//unlive
		              register_user_manually(fname, lname, email, role, user.user.id, group);						          
		          }
		          else {
		              alertstate("You can add only non-user and deleted user.");
		              return;
		          }
		          
		       },
		       type: 'POST'
		      });
		    } else if (result && result.items.length == 0) {
		      QB.users.create(params, function(err, user){//=========== New User in QuiclBlox
		        if (user) {
		             register_user_manually(fname, lname, email, role, user.id, group);
		        } else  {
		          alert("***********************" + JSON.stringify(err));
		        }
		      }); 
		    } else {

		      console.log(result);
		    }
		  });	         
	}

	function onCancelAUD(){
		$("#AddUserDialog").modal("hide");
	}

</script>
<div class="container-widget">

	<div id="AddUserDialog" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background: #72B7F8;">
            <h4 class="modal-title" style="color:white;">User Registration</h4>
          </div>
          <div class="modal-body">
            <div class="container-widget">
            	<div class="row padding_xs">
                	<span class="col-xs-4">First Name:</span>
                	<input type="text" placeholder="First Name" class="padding_xs col-xs-8" id="m_user_fname">
                </div>
                <div class="row padding_xs">
                	<span class="col-xs-4">Last Name:</span>
                	<input type="text" placeholder="Last Name" class="padding_xs col-xs-8" id="m_user_lname">
                </div>
                <div class="row padding_xs">
                	<span class="col-xs-4">Email:</span>
                	<input type="text" placeholder="Email address" class="padding_xs col-xs-8" id="m_user_email">
                </div>
                <div class="row padding_xs">
                    <span class="col-xs-4">Role:</span>
                    <select class="edit_input col-xs-8" id="m_user_role">
				      <option value="1">Admin</option>
				      <option value="2">Advisor</option>
				      <option value="3" selected>Entrepreneur</option>
				      <option value="4">Moderator</option>
				    </select>
                </div>
                <div class="row padding_xs">
                	<span class="col-xs-4">Group:</span>
                	<select class="edit_input col-xs-8" id="m_user_group">
                		<option value="">None</option>
				      <?php foreach($groupcodes as $code){
				      	echo "<option value='".$code."'>".$groupnames->{$code}."</option>";
				      } ?>
				    </select>
                </div>
            </div>
          </div>
          <div class="modal-footer" style="text-align:center;">
              <button class="btn pull-right wtext" onclick="onCancelAUD()" style="background: #72B7F8">Cancel</button>
              <button class="btn pull-right wtext" onclick="onRegisterUser()" style="background: #72B7F8">Register</button>
          </div>
        </div>
      </div>
    </div>
	
	

	<div class="row white_back content-title-bar">
		<?php if($u_type == 1){ ?>
		<button class="btn pull-right" style="margin:15px;"  onclick="add_user_manually()"><span class="glyphicon glyphicon-plus"></span>Add user</button>
		<?php } ?>
		<h3 class="desktop-visible-item gray-text">USER MANAGEMENT</h3>
		<h4 class="mobile-visible-item gray-text">USER MANAGEMENT</h4>
	</div>

	<?php if($u_type == 1 || $u_type == 4){ ?>
	<div class="row" style="padding: 20px 0px;">
		<div class="col-sm-6 col-xs-12 padding_x">
			<span style="margin-left:20px;">Filter by </span>
			<div class="dropdown" id="user_filter_dropdown">
	        	<button type="button" data-toggle="dropdown" class="dropdown-toggle" id="user_filter_by"><span id="filter_by">All</span><span class="glyphicon glyphicon-menu-down pull-right"></span></button>
				<ul class="dropdown-menu">
					<li><a onclick="AllUsers()">All</a></li>
			        <?php if ($u_type == 1) {?>
			        <li><a onclick="PendingUsers()">Pending</a></li>
			        <li><a onclick="ActiveUsers()">Activated</a></li>
			        <?php } else {?>
			        <li><a onclick="Entrepreneurs()">Entrepreneurs</a></li>
			        <li><a onclick="Advisors()">Advisors</a></li>
			        <li><a onclick="Moderators()">Moderators</a></li>
			        <?php } ?>
			        <li><a onclick="InvitedUsers()">Invited</a></li>
			    </ul>				    	
			</div>
		</div>
		<div class="col-sm-6 col-xs-12">
			
			<div class="col-sm-8 col-xs-12">
				<span><input id="invite_txt" type="text" class="radius-item padding_xs margin_x" placeholder="Email to invite"></span>
				<span>
					<div class="dropdown" id="dropdown-invite">
			        	<button type="button" data-toggle="dropdown" class="dropdown-toggle btn"><span class="glyphicon glyphicon-send font-10 pull-left"></span>&nbsp;&nbsp;Invite as</button>
						<?php if($u_type == 1 || strlen($group_name) > 0){ ?>
							<ul class="dropdown-menu">
								<?php if($u_type == 1) { ?>
								<li style="margin-left:10px;"><a onclick="sendInvite(11)">Admin</a></li>
								<?php } ?>
						        <li style="margin-left:10px;"><a onclick="sendInvite(12)">Advisor</a></li>
						        <li style="margin-left:10px;"><a onclick="sendInvite(13)">Entrepreneur</a></li>
						        <li style="margin-left:10px;"><a onclick="sendInvite(14)">Moderator</a></li>
						    </ul>
					    <?php } else { ?>
					    	<ul class="dropdown-menu padding_xs" style="width:50vw;">
								<p style="color:red;"><b>Warning:</b> &nbsp&nbspYour group name must not be empty in order to send out invites to your group.  Please go to your profile and add your group name first.</p>
						    </ul>
					    <?php } ?>
					</div>
				</span>
			</div>
			<div class="col-sm-4 col-xs-12">
				<?php if ($u_type == 4) {?>
				<button class="btn" onclick="SearchUsers()">Search Relayy Users</button>
				<?php } ?>
			</div>
		</div>
		
	</div>
	<?php } ?>
	
	
	<div id="user-manage-noti" class="box-section user_div">	

	<?php 
		$index = 0;

		foreach ($users as $user) {
			//if($u_type == 4 && $u_group === "") break;//if moderator hasnot his own group
			if ($user['id'] == $u_id) continue;//don't show me
			//if($u_type == 4 && $user['group'] !== $u_group) continue;//show only same group users if moderator
			$index = $index + 1;

			if ($user['type'] % 10 == 1) $utype = "Admin";
			if ($user['type'] % 10 == 2) $utype = "Advisor";
			if ($user['type'] % 10 == 3) $utype = "Entrepreneur";
	        if ($user['type'] % 10 == 4) $utype = "Moderator";

	        $username = '';
	        if ($user['fname'].$user['lname']) $username = $user['fname']." ".$user['lname'];
	        else {
	            $str_arr = explode("@", $user['email']);
	            $username = $str_arr[0];
	        }
			?>
		  <div class="row border3 mng_user_cell" data-status = "<?= $user['status'] ?>" data-type = "<?= $user['type'] ?>" data-group = "<?= $user['group']?>" style="margin:0px;padding:20px 10px;background:#FFF;display:none;">
		  	<div class="col-md-1 col-xs-3 canvas" style="margin-top:10px;">
			  	<img class="round pull-right" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" width="38" height="38">
			  	<span class="state_<?= $user['id'] ?> offline"></span>
		  	</div>
		    <div class="col-md-2 col-xs-9" style="margin-top:20px;"><span><?= $username?></span></div>
		    <div class="col-md-3 col-md-offset-0 col-xs-9 col-xs-offset-3 wrapword" style="margin-top:20px;"><span><a href="<?= site_url('profile/user/'.$user['id'])?>"><?= $user['email']?></a></span></div>
		    <div class="col-md-1 col-md-offset-0 col-xs-4 col-xs-offset-3" style="margin-top:20px;overflow:hidden;"><span><?= $utype?></span></div>
		    <?php if(strpos($invitedusers, $user['id']) !== false) { ?>
		    	<div class="col-md-1 col-md-offset-0 col-xs-5" style="margin-top:20px;"><span>[<?= $invited_group->{$user['id']} ?>]</span></div>
			<?php } else { ?>
		    	<div class="col-md-1 col-md-offset-0 col-xs-5" style="margin-top:20px;"><span>[<?= $user['groupname']?>]</span></div>
			<?php } ?>
	<?php if ($user['status'] == 0) {?>
		    <div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><center><span class="text-warning">Pending</span></center></div>
		    <?php if ($u_type == 1) {?><div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="ob pull-right" href="<?= site_url('users')?>/action/<?= $user['id']."/".$page?>">Activate</a></div><?php }?>
	<?php } else if ($user['status'] == 1) {?>
			<div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><center><span class="text-success">Activated</span></center></div>
		    <?php if ($u_type == 1) {?><div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="ob pull-right" href="<?= site_url('users')?>/action/<?= $user['id']."/".$page?>">Deactivate</a></div><?php }?>
	<?php } else if ($user['status'] == 2) {?>
			<div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><center><span class="text-primary">Invited</span></center></div>
		    <?php if ($u_type == 1) {?><div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="bb pull-right" href="#">Invite</a></div><?php }?>
	<?php } else if ($user['status'] == 4) {?>
			<div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><center><span class="text-success">Deleted</span></center></div>
		    <?php if ($u_type == 1) {?><div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="bb pull-right" href="<?= site_url('users')?>/invite/<?= $user['type']."/".$user['email']."/".$page?>">Invite</a></div><?php }?>
	<?php }?>

			<?php if ($u_type == 1) {?>
			<div class="col-md-1 col-xs-12 pull-right" style="margin-top:10px;"><a class="rb pull-right" onclick="delAction(this, '<?= $user['email']?>')" data-act="<?= site_url('users')?>/delete/<?= $user['id']."/".$page?>">Delete</a></div>
		  	<?php } else if($user['group'] === $u_group) { ?>
			<div class="col-md-2 col-xs-12 pull-right" style="margin-top:10px;"><a class="rb pull-right" onclick="delAction(this, '<?= $user['email']?>')" data-act="<?= site_url('users')?>/delete/<?= $user['id']."/".$page?>">Remove From Group</a></div>
		  	<?php } else if(strlen($user['group']) > 0 || $user['type'] == 1){ ?>
		  	<!-- have no buttons about invitation -->
		  	<?php } else if(strpos($invitedusers, $user['id']) !== false) { ?>
		  		<?php if($invited_group->{$user['id']} === $group_name){ ?>
				<div class="col-md-2 col-xs-12 pull-right" style="margin-top:10px;"><button class="bb pull-right" disabled="disabled">Invited</button></div>
		  		<?php } else { ?>
				<div class="col-md-2 col-xs-12 pull-right" style="margin-top:10px;"><button class="rb pull-right" disabled="disabled">Invited</button></div>
		  		<?php } ?>
		  	<?php } else { ?>
			<div class="col-md-2 col-xs-12 pull-right" style="margin-top:10px;"><button class="gb pull-right" onclick="InviteToGroup(this, '<?= $user['email']?>')">Invite to Group</button></div>
		  	<?php } ?>
		  </div>
	<?php		
		}
		if($index == 0){ ?>
		<p style="text-align:center; width:100%; padding:20px;">There is no result.</p>
	<?php } ?>

	</div>
</div>

<script>
	AllUsers();

	function InviteToGroup(obj, email){
		$.ajax({
	             url: site_url + 'users/InviteToGroup',
	             data: {
	                email: email
	             },
	             success: function(data) {	  
	             	if(data === "group_empty"){
	             		alertstate("You can't invite people to your group until your group name is filled out on your profile. Add a group name on your profile page.");
	             		return;
	             	}
	             	else if(data === "failed"){
	             		alertstate("The user was invited to another group now.");
	             		return;
	             	}
	                $(obj).text("Invited");
	                $(obj).prop("class", "bb pull-right");
	                $(obj).attr("disabled", "disabled");
	             },
	             type: 'POST'
	    });
	}
</script>







