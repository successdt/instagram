<?php 
if (!$session) {
    $instagram = new Instagram($config->cfg);
    $instagram->openAuthorizationUrl();
}
/**
 * Nếu tồn tại rồi thì bắt đầu lấy dữ liệu
 */
?>
<!-- autolink script -->
<script type="text/javascript">
    $(document).ready(function() {
      $('#content').html($('#content').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
$this->webroot ?>instagrams/index/$1" class="tag_replace">#$1</a>'));
    });
</script>

<!-- ligthbox script -->
<script type="text/javascript">
    $(document).ready(function(){
       $('.thumbnail').click(function(){        
            var id=$(this).attr('id');                
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>instagrams/photo/'+id);                      
       });
       $('#lightbox').click(function(){
            $('#lightbox ,.preview_wrapper').hide('slow');
            $('.preview').html('');
       });
        $('#statfollowers').click(function(){
            var userid=$(this).attr('data-user-id');
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>instagrams/userfollowedby/'+userid);                      
       });
       $('#statfollowing').click(function(){
            var userid=$(this).attr('data-user-id');
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>instagrams/userfollows/'+userid);                      
       });          
    });
</script>


<!-- lazyload script -->       
<script type="text/javascript">
$(window).scroll(function()
{            
    if($(window).scrollTop() == $(document).height() - $(window).height())
    {
        lazyload();
    }
});
</script>
<div class="lazyload">
    <script type="text/javascript">
        function lazyload(){
            $('#loading').show();
            $.ajax({
            url: "<?php echo $this->webroot; ?>instagrams/loaduserrecent/<?php echo $user['data']['id']  ?>",
            success: function(html)
            {
                if(html)
                {
                    $("#content").append(html);
                    $('#loading').hide();
                }else
                {
                    //$('#content').html('<center>No more posts to show.</center>');
                    alert('Error while loading next page');
                }
            }
            });
        }
        $(document).ready(function(){
            lazyload();
        });
    </script>
</div>

<div class="display-block" id="userinfo-block">
    <div class="block-avatar inline">
        <?php echo $this->Html->image($user['data']['profile_picture'],array('width'=>'50','height'=>'50')) ?>
    </div> <!-- /block-avatar -->
    <div class="block-info inline">
        <div class="block-username"><?php echo $user['data']['username'] ?></div><!-- block-username -->
        <div class="block-fullname"><?php echo $user['data']['full_name'] ?></div><!-- block-fullname -->
    </div> <!-- /block-info -->
    <div class="block-bio">
        <?php if($user['data']['website']) echo $this->Html->link($user['data']['website'],$user['data']['website']) ?>
        <?php echo $user['data']['bio'] ?>
    </div><!--/block-bio  -->
    <div class="stat-wrapper">
        <div class="stat stat-photo" data-user-id="<?php echo $user['data']['id'] ?>">
            <span><?php echo $user['data']['counts']['media'] ?></span>
            <div class="stat-desc">Photos</div><!-- /stat-desc -->
        </div><!-- /stat-photo -->
        <div class="stat stat-followers" id="statfollowers" data-user-id="<?php echo $user['data']['id'] ?>">
            <span><?php echo $user['data']['counts']['followed_by'] ?></span>
            <div class="stat-desc">Followers</div>
        </div><!-- /stat-followers -->
        <div class="stat stat-following" id="statfollowing" data-user-id="<?php echo $user['data']['id'] ?>">
            <span><?php echo $user['data']['counts']['follows'] ?></span>
            <div class="stat-desc">Following</div>
        </div><!-- /stat-following -->
    </div><!-- /stat-wrapper -->
    <button class="follow white" data-user-id="<?php echo $user['data']['id'] ?>">Follow</button>
</div> <!-- /.display-block -->
