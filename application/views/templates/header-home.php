<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
      <title><?php if(isset($page_title)) echo $page_title; ?></title>
      <link rel="shortcut icon" href="<?= asset_base_url()?>/images/favicon.png">

      <!--reset styles-->
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/main.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/bootstrap.min.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style2.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/guiders.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/font-awesome.css" type="text/css">
      
      <script src="<?php echo asset_base_url()?>/libs/jquery.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/guiders.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/heap.js"></script>
   </head>
   <body class="<?php if(isset($body_class)) echo $body_class; ?>">
   <script>
         var site_url = '<?= site_url()?>';
         var linkedIn_API = '<?= gf_linkedIn_api()?>';
         var linkedIn_secret_key = '<?= gf_linkedIn_secret_key()?>';
         var link_url = '<?= urlencode(site_url())?>';

         function alertstate(text){
          BootstrapDialog.show({
              type: BootstrapDialog.TYPE_INFO,
              title: "<h4 class='modal-title' style='color:white;'>Relayy</h4>",
              message: text + "\n",
              buttons: [{
                  label: 'Close',
                  cssClass: 'btn-primary',                           
                  action: function(dialogRef) {  
                     dialogRef.close();
                  }
              }]
          });
        }
   </script>

<!-- header end -->