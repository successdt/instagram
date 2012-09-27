<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="duythanh" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title_for_layout ?></title>
<?php
echo $this->Html->charset('UTF-8');
echo $this->Html->script('jquery-1.7.1.min');
echo $this->Html->script('http://www.google.com/jsapi');
echo $this->Html->script('masonry.min.js');
echo $this->Html->script('timeago');
echo $this->Html->script('bootstrap.min');
echo $this->Html->script('searchsuggestion');
echo $this->Html->css('bootstrap.min', 'stylesheet');
echo $this->Html->css('bootstrap-responsive.min', 'stylesheet');
echo $this->Html->css('mybootstrap', 'stylesheet',array('media'=>'screen'));
?>
</head>

<body>
<?php  echo $this->element('meshscript') ?>
<div class="navbar navbar-fixed-top">
    <?php echo $this->element('meshnav') ?>
</div><!-- /navbar -->
<div id="content">
    <?php echo $this->fetch('content') ?>
    <!-- masonry -->
    <script type="text/javascript">
        $(document).ready(function(){
           $('#content').masonry('reload'); 
        });
    </script>
</div><!--  /content-->
<div class="sidebar visible-desktop"> <!--  -->  
</div><!-- sidebar -->

<div id="loading">
    <div id="loading-inner"> 
        <?php echo $this->Html->image('loading.gif',array(
            'alt' => 'loading',
            'width' => '100',
            'height' => '100')) ?>
    </div>
</div>
<div id="lightbox"></div><!-- /lightbox -->
<div class="preview_wrapper">
    <div class="preview"> </div><!-- /.preview -->
</div><!-- /.preview_wrapper -->
</body>
</html>