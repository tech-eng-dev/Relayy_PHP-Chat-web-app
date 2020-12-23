<script>

	function AllChats(){
		$("#filter_by").text("All");
		$(".mng_chat_cell").show();
	}

	function PendingChats(){
		$("#filter_by").text("Pending");
		$(".mng_chat_cell").hide();
		$(".mng_chat_cell").each(function(){
    		if($(this).attr("data-status") == "0") $(this).show();
    	});
	}

	function ActiveChats(){
		$("#filter_by").text("Activated");
		$(".mng_chat_cell").hide();
		$(".mng_chat_cell").each(function(){
    		if($(this).attr("data-status") == "1") $(this).show();
    	});
	}

</script>
<div class="container-widget">
	
	

	<div class="row white_back content-title-bar">
		<h3 class="pull-left gray-text">CHAT MANAGEMENT</h3>
	</div>


	<div class="row padding_sm">

		<div class="dropdown pull-left" id="user_filter_dropdown">
        	<button type="button" data-toggle="dropdown" class="dropdown-toggle" id="user_filter_by"><span id="filter_by">All</span><span class="glyphicon glyphicon-menu-down pull-right"></span></button>
			<ul class="dropdown-menu">
				<li><a onclick="AllChats()">All</a></li>
				<li><a onclick="PendingChats()">Pending</a></li>
				<li><a onclick="ActiveChats()">Activated</a></li>
		    </ul>				    	
		</div>
	</div>	


		
	<div id="chat-manage-noti" class="box-section user_div">	

	<?php 
		$index = 0;
		foreach ($chats as $chat) {

		if ($chat['type'] == CHAT_TYPE_WELCOME) continue;
		if ($chat['status'] != 0 && $page == 1) continue;
		if ($chat['status'] != 1 && $page == 2) continue;
		if($u_type == 4 && $chat['group'] != $u_group) continue;

		$index = $index + 1;
		$emails = json_encode($chat['emails']);
			?>
		  <div class="row border3 mng_chat_cell" data-status = "<?= $chat['status'] ?>" style="margin:0;padding:10px;background:#FFF;">
		  	<div class="col-md-1 col-xs-3" style="margin-top:10px;"><img class="round" src="<?= $chat['type']==CHAT_TYPE_PRIVATE?asset_base_url().'/images/ava-single.svg':asset_base_url().'/images/ava-group.svg'?>" width="38" height="38"></div>
		    <div class="col-md-2 col-xs-9" style="margin-top:20px;"><span><?= $chat['name']?></span></div>
		    <div class="col-md-3 col-md-offset-0 col-xs-10 col-xs-offset-2" style="margin-top:20px;overflow:hidden;"><a href="<?= site_url('chat/channel/'.$chat['did'])?>" title="Go to chat!"><p><?php $str = str_replace(array("[","]","\""), "", $emails); echo str_replace(",", " ", $str);?></p></a></div>
		    <div class="col-md-1 col-md-offset-0 col-xs-5 col-xs-offset-2" style="margin-top:20px;"><span><?= $chat['type']==CHAT_TYPE_PRIVATE?'1:1 Chat':'Group'?></span></div>
		    <div class="col-md-1 col-md-offset-0 col-xs-5" style="margin-top:20px;"><span>[<?= $chat['group'] ?>]</span></div>

	<?php if ($chat['status'] == 0) {?>
		    <div class="col-md-1 col-md-offset-0 col-xs-4 col-xs-offset-2" style="margin-top:20px;"><span class="text-warning">Pending</span></div>
		    <div class="col-md-2 col-xs-6" style="margin-top:10px;"><a class="ob pull-right" href="<?= site_url('allow')?>/action/<?= $chat['did']."/".$page?>">Activate</a></div>
	<?php } else if ($chat['status'] == 1) {?>
			<div class="col-md-1 col-md-offset-0 col-xs-4 col-xs-offset-2" style="margin-top:20px;"><span class="text-success">Activated</span></div>
		    <div class="col-md-2 col-xs-6" style="margin-top:10px;"><a class="ob pull-right" href="<?= site_url('allow')?>/action/<?= $chat['did']."/".$page?>">Deactivate</a></div>
	<?php }?>
			<div class="col-md-1 col-xs-12" style="margin-top:10px;"><a class="rb pull-right" onclick="delAction(this, '<?= $chat['did']?>', '<?= $chat['name']?>')" data-act="<?= site_url('allow')?>/delete/<?= $chat['did']."/".$page?>">Delete</a></div>
		  </div>
	<?php		
		}
		if($index == 0) { ?>
		<p style="text-align:center; width:100%; padding:20px;">There is no result.</p>
	<?php } ?>
	</div>
		  
</div>
