<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="duythanh" />

	<title><?php echo $title_for_layout ?></title>
<?php
echo $this->Html->charset('UTF-8');
echo $this->Html->script('jquery-1.4.2.min');
//echo $this->Html->script('jquery.scrollExtend');
echo $this->Html->script('masonry.min.js');
echo $this->Html->script('timeago');
echo $this->Html->css('instagram', 'stylesheet');
?>
</head>

<body>
<?php echo $this->element('script') ?>
<div id="wrapper">
    <div id="sidebar"> <?php echo $this->element('sidebar'); ?></div><!-- /sidebar -->
    <div id="top-nav">
    <?php echo $this->element('topnav') ?>
    </div><!-- /top-nav -->
    <div id="content"><?php echo $this->fetch('content') ?></div><!-- /content -->
</div><!-- /wrapper -->
<div id="loading"><div id="loading-inner"> <?php echo $this->Html->image('loading.gif',
            array(
            'alt' => 'loading',
            'width' => '100',
            'height' => '100')) ?></div>
</div>
<div id="lightbox"></div><!-- /lightbox -->
<div class="preview_wrapper">
    <div class="preview"></div><!-- /.preview -->
</div><!-- /.preview_wrapper -->
</body>
</html>