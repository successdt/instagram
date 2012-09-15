<?php 
if (!$session) {
    $instagram = new Instagram($config->cfg);
    $instagram->openAuthorizationUrl();
}
/**
 * Nếu tồn tại rồi thì bắt đầu lấy dữ liệu
 */
?>

<!-- ligthbox script -->
<script type="text/javascript">
    $(document).ready(function(){
       $('.thumbnail').click(function(){        
            var id=$(this).attr('id');                
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>meshtiles/photo/'+id);                      
       });
       $('#lightbox').click(function(){
            $('#lightbox ,.preview_wrapper').hide('slow');
            $('.preview').html('');
       });
        $('#statfollowers').click(function(){
            var userid=$(this).attr('data-user-id');
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>meshtiles/userfollowedby/'+userid);                      
       });
       $('#statfollowing').click(function(){
            var userid=$(this).attr('data-user-id');
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>meshtiles/userfollows/'+userid);                      
       });          
    });
</script>


<!-- lazyload script -->       
<script type="text/javascript">
var load=true;
$(window).scroll(function()
{            
    if(($(window).scrollTop()+$(window).height()>($(document).height() -400 ))&&load)
    {
        load=false;
        userlazyload();
    }
});
</script>
<div class="lazyload">
    <script type="text/javascript">
        function userlazyload(){
            $('#loading').show();
            $.ajax({
            url: "<?php echo $this->webroot; ?>meshtiles/loaduserrecent/<?php echo $user['user']['user_id']  ?>",
            success: function(html)
            {
                if(html)
                {
                    $("#content").append(html);
                    $('#loading').hide();
                    load=true;
                    facebook();
                    twitter();
                }else
                {
                    //$('#content').html('<center>No more posts to show.</center>');
                    alert('Error while loading next page');
                }
            }
            });
        }
        $(document).ready(function(){
            userlazyload();
        });
    </script>
</div>

<div class="display-block" id="userinfo-block">
    <div class="block-avatar inline">
        <?php echo $this->Html->image($user['user']['url_image'],array('width'=>'50','height'=>'50')) ?>
    </div> <!-- /block-avatar -->
    <div class="block-info inline">
        <div class="block-username"><?php echo $user['user']['user_name'] ?></div><!-- block-username -->
        <div class="block-fullname">
            <?php echo urldecode($user['user']['first_name']) ?>
            <?php echo urldecode($user['user']['last_name']) ?> 
        </div><!-- block-fullname -->
    </div> <!-- /block-info -->
    <div class="block-bio">
        <?php //if($user['user']['website']) echo $this->Html->link($user['user']['website'],$user['user']['website']) ?>
        <?php //echo $user['user']['bio'] ?>
        <?php echo urldecode($user['user']['current_city']).'-'.urldecode($user['user']['current_country'] )?>
    </div><!--/block-bio  -->
    <div class="stat-wrapper">
        <div class="stat stat-photo" data-user-id="<?php echo $user['user']['user_id'] ?>">
            <span><?php echo $user['user']['number_post'] ?></span>
            <div class="stat-desc">Posts</div><!-- /stat-desc -->
        </div><!-- /stat-photo -->
        <div class="stat stat-followers" id="statfollowers" data-user-id="<?php echo $user['user']['user_id'] ?>">
            <span><?php echo $user['user']['number_follower'] ?></span>
            <div class="stat-desc">Followers</div>
        </div><!-- /stat-followers -->
        <div class="stat stat-following" id="statfollowing" data-user-id="<?php echo $user['user']['user_id'] ?>">
            <span><?php echo $user['user']['number_following'] ?></span>
            <div class="stat-desc">Following</div>
        </div><!-- /stat-following -->
        <div class="stat " id="statfollowing" data-user-id="<?php echo $user['user']['user_id'] ?>">
            <span><?php echo $user['user']['number_Master'] ?></span>
            <div class="stat-desc">Master</div>
        </div><!-- /stat -->
        <div class="stat " id="statfollowing" data-user-id="<?php echo $user['user']['user_id'] ?>">
            <span><?php echo $user['user']['number_Pennant'] ?></span>
            <div class="stat-desc">Pennan</div>
        </div><!-- /stat -->
                <div class="stat " id="statfollowing" data-user-id="<?php echo $user['user']['user_id'] ?>">
            <span><?php echo $user['user']['number_Vangard'] ?></span>
            <div class="stat-desc">Vangard</div>
        </div><!-- /stat -->
    </div><!-- /stat-wrapper -->
    <button class="follow white" data-user-id="<?php echo $user['user']['user_id'] ?>">Follow</button>
</div> <!-- /.display-block -->
