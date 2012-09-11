<!-- autolink script -->
<script type="text/javascript">
    $(document).ready(function() {
    $('.p_text').html($('.p_text').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
        $this->webroot ?>meshtiles/index/$1" class="tag_replace">#$1</a>'));
    $('.p_comment').html($('.p_comment').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
        $this->webroot ?>meshtiles/index/$1" class="tag_replace">#$1</a>'));
    $('.p_like').html($('.p_like').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
        $this->webroot ?>meshtiles/index/$1" class="tag_replace">#$1</a>'));
    
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
        $('#loading').show();
        var txt=$('textarea#new-post').val();
        var id='<?php echo $media['photo']['photo_id']; ?>';
        $.post("<?php echo $this->webroot ?>meshtiles/new_comment",{id:id,text:txt},function(result){
            $('.p_comment').html(result);
            $('#loading').hide();
        });
   }); 
});
</script>
<!-- load comment -->
<script>
    $(document).ready(function(){
        $('.viewall').click(function(){
            var id=$(this).attr('data-media-id');
            $('#loading').show();
            //alert(id);
            $.ajax({
                url:"<?php echo $this->webroot ?>meshtiles/load_comment/"+id,
                success:function(html){
                    $('.p_comment').html(html);
                    $('#loading').hide();
                }
            });
        });
    });
</script>
<!-- click like -->
<script>
    $(document).ready(function(){
        <?php if($media['photo']['is_clicked']){ ?>
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
                    url:"<?php echo $this->webroot ?>meshtiles/like/0/"+id,
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
                    url:"<?php echo $this->webroot ?>meshtiles/like/1/"+id,
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
                    url:"<?php echo $this->webroot ?>meshtiles/like/1/"+id,
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
                    url:"<?php echo $this->webroot ?>meshtiles/like/0/"+id,
                    success:function(html){
                        $('.p_like_inner').html(html);
                        $('#'+id).attr('data-media-liked','');
                        $('#loading').hide();
                    }
                });
            });     
        <?php } ?>
        facebook();
        twitter();
    });
</script>

<div class="p_image">
    <?php echo $this->Html->image($media['photo']['url_photo'],
array(
    'alt' => 'image',
    'width' => '612',
    'height' => '612')) ?>
</div><!-- /.p_image -->
<div class="p_text">
    <div class="p_text_avatar">
        <?php 
        echo $this->Html->image($media['photo']['user']['url_image'],
            array(
            'alt' => 'profile picture',
            'width' => '50',
            'height' => '50')); 
        ?>
    </div>
    <div class="p_text_text">
        <?php
            echo $this->Html->link($media['photo']['user']['user_name'], array(
                'controller' => 'meshtiles',
                'action' => 'viewprofile',
                $media['photo']['user']['user_id']));
            echo urldecode($media['photo']['caption']);
        ?>

    <div class="created-time">
        <i class="icon-time icon-black"></i>
        <abbr class="timeago" title="<?php echo date('Y-m-d H:i:s', strtotime('- ' . $media['photo']['time_post'] . ' seconds' ))?>">
            <?php echo date('Y-m-d H:i:s', strtotime('- ' . $media['photo']['time_post'] . ' seconds' ))?>
        </abbr>
    </div><!-- /created-time -->
     </div><!-- /p_text_text -->
</div><!--/p_text  -->
<div class="p_close"></div><!-- /p_close -->
<div class="p_like">
    <?php
        echo $this->Html->image('icons/facebook.png', array(
            'alt' => 'fblike',
            'class' => 'facebook social',
            'data-media-id' => $media['photo']['photo_id']));
        
        echo $this->Html->image('icons/twitter.png', array(
            'alt' => 'twfollow',
            'class' => 'twitter social',
            'data-media-id' => $media['photo']['photo_id']));
        echo $this->Html->image('icons/googleplus.png', array(
            'alt' => 'google+',
            'class' => 'googleplus social',
            'data-media-id' => $media['photo']['photo_id'])); ?>
    
    <?php
        echo $this->Html->image('icons/heart.png', array(
            'alt' => 'instagram',
            'class' => 'instagramlike social',
            'data-media-id' => $media['photo']['photo_id']));
    ?>
    
    <div class="p_like_inner">
        <?php echo $media['photo']['number_click']?>
        <?php echo $this->Html->image('icons/likes.png', array('alt' => 'likes')) ?>
        <?php /* foreach ($media['data']['likes']['data'] as $like): ?>
       
            <?php
        echo $this->Html->link('@'.$like['username'], array(
            'controller' => 'meshtiles',
            'action' => 'viewprofile',
            $like['id'])); ?>
        <?php endforeach */ ?>
        <br />
        <!-- tag -->
        <i class="icon-tag icon-black"></i>
        <?php foreach($tags as $tag){
            if($tag)
                echo '#'.$tag.' ';
        } ?>
    </div><!-- /p_like_inner -->
</div><!-- /.p_like -->
<div class="p_comment">
    
    <?php foreach ($media['photo']['comment'] as $comment):?>
    <div class="comment-avatar">
        <?php
    echo $this->Html->image($comment['url_image'], array(
        'width' => '30',
        'height' => '30',
        'url' => array(
            'controller' => 'meshtiles',
            'action' => 'viewprofile',
            $comment['user_id'])));
        ?>
    </div><!-- /comment-avatar -->
    <div class="comment-inner">
        <?php
        echo $this->Html->link($comment['user_name'] . " ", array(
            'controller' => 'meshtiles',
            'action' => 'viewprofile',
            $comment['user_id']), array('description' => 'hello'));
        echo urldecode($comment['content']);
        ?>
        <div class="created-time">
            <i class="icon-time icon-black"></i>
            <abbr class="timeago" title="<?php echo date("Y-m-d H:i:s",strtotime('- ' .$comment['time_post']. ' seconds' ))?>">
                <?php echo date("Y-m-d H:i:s",strtotime('- ' .$comment['time_post']. ' seconds' ))?>
            </abbr>
        </div><!-- /created-time -->
    </div> <!-- /.comment-inner -->
    <?php endforeach; ?>
    <?php if($media['photo']['number_comment']>5): ?>
        <a class="btn viewall" data-media-id="<?php echo $media['photo']['photo_id'] ?>">
            View all <?php echo $media['photo']['number_comment']?> comments
        </a>
    <?php endif ?>
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