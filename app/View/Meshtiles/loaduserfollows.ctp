<?php if(isset($data)){ ?>
<script type="text/javascript">
    $(document).ready(function(){
       $('#follows').masonry('reload'); 
    });
</script>
<?php foreach($data['user'] as $user): ?>
<div class="mini-block" style="cursor: auto">
    <div class="block-avatar inline" style="cursor: pointer;" onclick="window.location='<?php echo $this->webroot ?>meshtiles/viewprofile/<?php echo $user['user_id'] ?>'">
        <?php echo $this->Html->image($user['url_image'],array('width'=>'50','height'=>'50')) ?>
    </div> <!-- /block-avatar -->
    <div class="block-info inline">
        <div class="block-username">
            <?php echo $this->Html->link($user['user_name'],array(
                'controller'=>'meshtiles',
                'action'=>'viewprofile',
                $user['user_id'])) ?>
        </div><!-- block-username -->
        <div class="block-fullname"><?php echo urldecode($user['first_name'].$user['last_name']) ?></div><!-- block-fullname -->
    </div> <!-- /block-info -->
</div><!-- miniblock -->
<?php endforeach ?>
<?php }else{ ?>
<script>
    $(document).ready(function(){
       $('.loadmore').html('');
    });
</script>
<?php } ?>