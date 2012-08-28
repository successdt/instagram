<?php if(isset($data)){ ?>
<script type="text/javascript">
    $(document).ready(function(){
       $('#follows').masonry('reload'); 
    });
</script>
<?php foreach($data['data'] as $user): ?>
<div class="mini-block">
    <div class="block-avatar inline" onclick="window.location='<?php echo $this->webroot ?>instagrams/viewprofile/<?php echo $user['id'] ?>'">
        <?php echo $this->Html->image($user['profile_picture'],array('width'=>'50','height'=>'50')) ?>
    </div> <!-- /block-avatar -->
    <div class="block-info inline">
        <div class="block-username"><?php echo $user['username'] ?></div><!-- block-username -->
        <div class="block-fullname"><?php echo $user['full_name'] ?></div><!-- block-fullname -->
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