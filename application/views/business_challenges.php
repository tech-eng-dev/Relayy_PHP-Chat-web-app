<div class="profile-container container-widget">

	<?php if($u_type != 1) {  ?>
		<center>This page is visible only to admins.</center>
	<?php } else { ?>
		<?php foreach ($challengers as $challenger) { ?>
			
			<div class="border3">
				<div class="row padding_xs">
					<a href="<?php echo site_url().'profile/user/'.$challenger['id'] ?>" class="pull-left"><img width="70" height="70" class="img-circle padding_xs" src="<?php echo strlen($challenger['photo']) == 0?asset_base_url().'/images/emp.jpg':$challenger['photo'] ?>"></a>
					<h3 class="pull-left gray-text"><?php echo $challenger['fname']." ".$challenger['lname'] ?></h3>
				</div>

				<div class="row" style="padding-left:30px;">
					<h6><?php echo $challenger['challenge'] ?></h6>
				</div>
			</div>

		<?php  } ?>

		<?php if($count == 0) {  ?>
			<center>There is not an entrepreneur you are looking for. </center>
		<?php } ?>
	<?php } ?>

	

</div>