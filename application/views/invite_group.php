<div class="white_back">
<div class="profile-container">
        <center><h3 class="gray-text"><?= $u_fname ?>, you have been invited to a group on Relayy:</h3></center>
        <div class="container-widget invite_groupInfo_border radius-item padding_sm">
          <div class="row">Group:</div>
          <div class="row padding_xs">
            <img id="invite_group_image" width="30" height="30" src="<?= strlen($group_image) > 0?uploads_base_url().$group_image:asset_base_url().'/images/ava-group.svg' ?>" class="pull-left img-circle">
            <span id="invite_group_name" class="pull-left padding_xs"><?= $group_name ?></span>
          </div>
          <div class="row">Invited by:</div>
          <div class="row padding_xs">
            <div class="col-xs-2">
              <img id="inviter_photo" width="30" height="30" src="<?= strlen($inviter_photo) > 0?$inviter_photo:asset_base_url().'/images/ava-single.svg' ?>" class="img-circle">
            </div>
            <div class="col-xs-3">
              <div class="gray-text">Name:</div>
              <div class="gray-text">Bio:</div>
            </div>
            <div class="col-xs-7">
              <div id="inviter_name" style="font-weight:Bold;"><?= $inviter_name ?></div>
              <div id="inviter_bio"><?= $inviter_bio ?></div>
            </div>
          </div>
        </div>

        <div class="container-widget padding_sm">
          <div class="row"><center>Do you want to accept this invitation and join this group? </center></div>
          <div class="row">
            <div class="col-xs-6 padding_sm">
              <button type="button" class="rb pull-right wtext" onclick="onDeclineGroupInvite()">DECLINE</button>
            </div>
            <div class="col-xs-6 padding_sm">
              <button type="button" class="db pull-left wtext" onclick="onAcceptGroupInvite()">ACCEPT</button>
            </div>
          </div>
          <div class="row">
            <p>How do Groups work?</p>
            <p>Relayy Groups allow users to ask and answer questions inside of a private group-instead of using the entire Relayy network. Group members are still able to participate in the open Relayy network if they so desire.</p>
          </div>
        </div>
</div>
</div>
<script>
	$(".content").addClass("white_back");
</script>
