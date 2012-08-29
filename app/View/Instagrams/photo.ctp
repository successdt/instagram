<!-- autolink script -->
<script type="text/javascript">
    $(document).ready(function() {
      $('.p_text').html($('.p_text').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
    $this->webroot ?>instagrams/index/$1" class="tag_replace">#$1</a>'));
      $('.p_comment').html($('.p_comment').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
    $this->webroot ?>instagrams/index/$1" class="tag_replace">#$1</a>'));
    });
</script>

<!-- close lightbox button -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.p_close').click(function(){
            $('#lightbox ,.preview_wrapper').hide('slow');
            $('.preview').html('');
       });  
    });
</script>



<!-- post comment -->
<script>
$(document).ready(function(){
   $('.send-button').click(function(){
        var txt=$('textarea#new-post').val();
        var id='<?php echo $media['data']['id']; ?>';
        $.post("<?php echo $this->webroot ?>instagrams/new_comment",{id:id,text:txt},function(result){
            alert(result);
        });
   }); 
});
</script>

<!-- click like -->
<script>
    $(document).ready(function(){
        <?php if($media['data']['user_has_liked']){ ?>
            $('.instagramlike').css('opacity','0.5');
            $('.instagramlike').toggle(function(){
                $(this).css('opacity','1');
                },function(){
                $(this).css('opacity','0.5');
            });
            $('.instagramlike').toggle(function(){
                var id=$(this).attr('data-media-id');
                //alert(id);
                $('#loading').show();
                $.ajax({
                    url:"<?php echo $this->webroot ?>instagrams/like/0/"+id,
                    success:function(html){
                        $('.p_like_inner').html(html);
                        $('#'+id).attr('data-media-liked','');
                        $('#loading').hide();
                    }
                });
            },function(){
                var id=$(this).attr('data-media-id');
                //alert(id);
                $('#loading').show();
                $.ajax({
                    url:"<?php echo $this->webroot ?>instagrams/like/1/"+id,
                    success:function(html){
                        $('.p_like_inner').html(html);
                        $('#'+id).attr('data-media-liked','1');
                        $('#loading').hide();
                    }
                });                
            });            
        <?php }else{ ?>
            $('.instagramlike').css('opacity','1');
            $('.instagramlike').toggle(function(){
                $(this).css('opacity','0.5');
                },function(){
                $(this).css('opacity','1');
            });
            $('.instagramlike').toggle(function(){
                var id=$(this).attr('data-media-id');
                $('#loading').show();
                //alert(id);
                $.ajax({
                    url:"<?php echo $this->webroot ?>instagrams/like/1/"+id,
                    success:function(html){
                        $('.p_like_inner').html(html);
                        $('#'+id).attr('data-media-liked','1');
                        $('#loading').hide();
                    }
                });
            },function(){
                $('#loading').show();
                var id=$(this).attr('data-media-id');
                //alert(id);
                $.ajax({
                    url:"<?php echo $this->webroot ?>instagrams/like/0/"+id,
                    success:function(html){
                        $('.p_like_inner').html(html);
                        $('#'+id).attr('data-media-liked','');
                        $('#loading').hide();
                    }
                });
            });     
        <?php } ?>
    });
</script>

<div class="p_image">
    <?php echo $this->Html->image($media['data']['images']['standard_resolution']['url'],
array(
    'alt' => 'image',
    'width' => '612',
    'height' => '612')) ?>
</div><!-- /.p_image -->
<div class="p_text">
    <div class="p_text_avatar"><?php echo $this->Html->image($media['data']['caption']['from']['profile_picture'],
    array(
    'alt' => 'profile picture',
    'width' => '50',
    'height' => '50')); ?></div>
    <div class="p_text_text">
    <?php
echo $this->Html->link($media['data']['caption']['from']['username'], array(
    'controller' => 'instagrams',
    'action' => 'viewprofile',
    $media['data']['caption']['from']['id']));
echo $media['data']['caption']['text'];
?>
     </div><!-- /p_text_text -->
</div><!--/p_text  -->
<div class="p_close"></div><!-- /p_close -->
<div class="p_like">
    <?php
echo $this->Html->image('icons/facebook.png', array(
    'alt' => 'fblike',
    'class' => 'facebook social',
    'data-media-id' => $media['data']['id']));
echo $this->Html->image('icons/twitter.png', array(
    'alt' => 'twfollow',
    'class' => 'twitter social',
    'data-media-id' => $media['data']['id']));
echo $this->Html->image('icons/googleplus.png', array(
    'alt' => 'google+',
    'class' => 'googleplus social',
    'data-media-id' => $media['data']['id']));
echo $this->Html->image('icons/heart.png', array(
    'alt' => 'instagram',
    'class' => 'instagramlike social',
    'data-media-id' => $media['data']['id']));
?>
    <div class="p_like_inner">
        <?php echo $this->Html->image('icons/likes.png', array('alt' => 'likes')) ?>
        Liked by
        <?php foreach ($media['data']['likes']['data'] as $like): ?>
       
            <?php
        echo $this->Html->link('@'.$like['username'], array(
            'controller' => 'instagrams',
            'action' => 'viewprofile',
            $like['id'])); ?>
        <?php endforeach ?>
    </div><!-- /p_like_inner -->
</div><!-- /.p_like -->
<div class="p_comment">
    <?php foreach ($media['data']['comments']['data'] as $comment):?>
    <div class="comment-avatar">
        <?php
    echo $this->Html->image($comment['from']['profile_picture'], array(
        'width' => '30',
        'height' => '30',
        'url' => array(
            'controller' => 'instagrams',
            'action' => 'viewprofile',
            $comment['from']['id'])));
        ?>
    </div><!-- /comment-avatar -->
    <div class="comment-inner">
    <?php
    echo $this->Html->link($comment['from']['username'] . " ", array(
        'controller' => 'instagrams',
        'action' => 'viewprofile',
        $comment['from']['id']), array('description' => 'hello'));
    echo $comment['text'];
    ?>
    <script type="text/javascript">
    </script>
    <div class="created-time">
        <abbr class="timeago" title="<?php echo date("Y-m-d H:i:s", $comment['created_time'])?>"><?php echo date("Y-m-d H:i:s", $comment['created_time'])?></abbr>
    </div><!-- /created-time -->
    </div> <!-- /.comment-inner -->
    <?php
endforeach;
?>
</div><!-- /.p_comment -->
<div class="p_post">
    <div class="inline">
        <?php echo $this->Form->input('', array(
        'id' => 'new-post',
        'type' => 'textarea',
        'cols'=>'25',
        'rows' => '3')) ?>    
    </div>
    <div class="inline">
        <button class="white send-button">Send</button>
    </div>   
</div><!-- /p_post -->