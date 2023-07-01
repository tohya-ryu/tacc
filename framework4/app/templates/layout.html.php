<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" 
      href="<?php $this->base_uri('assets/style.css');?>"/>
    <script type="text/javascript" src="
      <?php $this->base_uri('assets/framework.js');?>" defer></script>
    <script type="text/javascript" src="
      <?php $this->base_uri('assets/jlearn.js');?>" defer></script>

    <title><?php echo $this->view->title;?></title>
  </head>
    
  <body>
    <div id="app-window">
      <div id="app-title"><?php echo $this->view->title ?></div>
      <?php $this->view->render_content($this->data); ?>
    </div>
  </body>

</html>
