<script type="text/javascript">
    $(document).ready(function(){
       $('.top-photo').click(function(){;        
            var id=$(this).attr('data-media-id');                
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>meshtiles/photo/'+id);
                                  
       });
       $('#lightbox').click(function(){
            $('#lightbox ,.preview_wrapper').hide('slow');
            $('.preview').html('');
       }) 
    });
</script>
<div class="user-stat stat2">
    <span><i class="icon-user icon-white"></i><?php echo $user['user_name'] ?></span>
    <div class="stat-desc"><?php echo urldecode($user['first_name'].$user['last_name']) ?>Phạm Văn Hiếu</div>
</div><!-- /user-stat --> 
<?php echo $this->html->image($user['url_image'],array('class'=>'stat2')) ?>
<div class="user-stat stat1"  data-user-id="<?php echo $user['user_id'] ?>">
    <span><?php echo $user['number_follower'] ?></span>
    <div class="stat-desc">Followers</div>
</div><!-- /user-stat-followers -->
<div class="user-stat stat1"  data-user-id="<?php echo $user['user_id'] ?>">
    <span><?php echo $user['number_following'] ?></span>
    <div class="stat-desc">Following</div>
</div><!-- /user-stat-following -->
<div class="user-stat stat1">
    <span><?php echo $user['number_photos'] ?></span>
    <div class="stat-desc">Photos</div>    
</div><!-- /user-stat --> 
<div class="user-stat stat1">
    <span><?php echo $user['number_rating'] ?></span>
    <div class="stat-desc">Rating</div>    
</div><!-- /user-stat --> 
<div class="user-stat stat1">
    <span><?php echo $user['number_comment'] ?></span>
    <div class="stat-desc">Comments</div>    
</div><!-- /user-stat -->
<div class="user-stat stat1">
    <span><?php echo $user['number_rating'] ?></span>
    <div class="stat-desc">Rating</div>    
</div><!-- /user-stat --> 
<div class="user-stat stat2">
    <span><?php echo number_format($user['number_rating_per_day'],2,'.','' )?></span>
    <div class="stat-desc">Rating per day</div>    
</div><!-- /user-stat -->   
<div class="user-stat stat2">
    <span><?php echo $user['number_comment_per_day'] ?></span>
    <div class="stat-desc">Comments per day</div>    
</div><!-- /user-stat -->
<div class="stat3 user-stat">
    <span><i class="icon-picture icon-white"></i>Top Photos</span>
    <div class="stat-desc" >
        <?php foreach($user['top_photos'] as $photo):?>
            <div class="top-photo" data-media-id="<?php echo $photo['photo_id'] ?>">
                <?php echo $this->html->image($photo['url_thumb'],array('width'=>'60','height'=>'60')) ?>
            </div>
        <?php endforeach ?>
    </div>
</div><!-- /user-stat -->

