<!-- autolink script -->
<script type="text/javascript">
    $(document).ready(function() {
      $('#content').html($('#content').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
$this->webroot ?>instagrams/index/$1" class="tag_replace">#$1</a>'));
    });
</script>
<!-- lightbox -->
<script type="text/javascript">
    $(document).ready(function(){
       $('.thumbnail, .metadata').click(function(){;        
            var id=$(this).parent().attr('id');                
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


<!-- masonry -->
<script type="text/javascript">
    $(document).ready(function(){
       $('#content').masonry('reload'); 
    });
</script>

<!-- social -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.display-block').mouseover(function(){
            var id=$(this).attr('id');
            var liked=$(this).attr('data-media-liked');
            $('#like'+id).show();
            if(liked){
                $('.instagram'+id).css('opacity','0.5');
                $('.instagram'+id).toggle(function(){
                    $('#loading').show();
                    $.ajax({
                        url:"<?php echo $this->webroot ?>instagrams/like/0/"+id,
                        success:function(html){
                            $('.instagram'+id).css('opacity','1');
                            $('#'+id).attr('data-media-liked','');
                            $('#loading').hide();
                        }
                    });
                },function(){
                    $('#loading').show();
                    $.ajax({
                        url:"<?php echo $this->webroot ?>instagrams/like/1/"+id,
                        success:function(html){
                            $('.instagram'+id).css('opacity','0.5');
                            $('#'+id).attr('data-media-liked','1');
                            $('#loading').hide();
                        }
                    });                     
                });
            }else{
                $('.instagram'+id).css('opacity','1');
                $('.instagram'+id).toggle(function(){
                    $('#loading').show();
                    $.ajax({
                        url:"<?php echo $this->webroot ?>instagrams/like/1/"+id,
                        success:function(html){
                            $('.instagram'+id).css('opacity','0.5');
                            $('#'+id).attr('data-media-liked','1');
                            $('#loading').hide();
                        }
                    });                      

                },function(){
                    $('#loading').show();
                    $.ajax({
                        url:"<?php echo $this->webroot ?>instagrams/like/0/"+id,
                        success:function(html){
                            $('.instagram'+id).css('opacity','1');
                            $('#'+id).attr('data-media-liked','');
                            $('#loading').hide();
                        }
                    });                   
                });                
            }
        }); //mouseover
        $('.display-block').mouseout(function(){
            var id=$(this).attr('id');
            $('#like'+id).hide();
        });
    });
</script>
<?php
foreach ($media['data'] as $data): ?>
    <div class="display-block" id="<?php echo $data['id'] ?>" data-media-liked="<?php echo $data['user_has_liked'] ?>">
        <div class="block-like" id="like<?php echo $data['id'] ?>">
        
            <?php
            echo $this->Html->image('icons/facebook.png', array(
                'alt' => 'fblike',
                'class' => 'social main-facebook',
                'data-media-id' => $data['id']));
            echo $this->Html->image('icons/twitter.png', array(
                'alt' => 'twfollow',
                'class' => 'social main-twitter',
                'data-media-id' => $data['id']));
            echo $this->Html->image('icons/googleplus.png', array(
                'alt' => 'google+',
                'class' => 'social main-googleplus',
                'data-media-id' => $data['id']));
            echo $this->Html->image('icons/heart.png', array(
                'alt' => 'instagram',
                'class' => 'social main-instagramlike instagram'.$data['id'],
                'data-media-id' => $data['id']));
            ?>
        </div>
        <div class="thumbnail" ><?php echo $this->Html->image($data['images']['low_resolution']['url'],
array('width' => '204', 'height' => '204')); ?></div><!-- /.thumbnail -->
        <div class="tags">
            <?php echo $data['caption']['text'] ?>
        </div><!-- /tags -->
        <div class="metadata">
            <?php echo $this->Html->image('icons/likes.png', array('alt' =>
'likes')) ?>
            <?php echo $data['likes']['count']; ?>
            <?php echo $this->Html->image('icons/comments.png', array('alt' =>
'comments')) ?>
            <?php echo $data['comments']['count']; ?>
        </div><!-- /.metadata -->
        <div class="comment">
            <?php
    $i = 0;
    foreach ($data['comments']['data'] as $comment): ?>
            
                <?php if ($i == 3)
            break; ?>
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
            $comment['from']['id']));
        echo $comment['text'];
        $i++;
?>
                </div> <!-- /.comment-inner -->
            <?php endforeach ?>
        </div><!-- /comment -->
    </div><!-- /.display-block -->
<?php endforeach ?>