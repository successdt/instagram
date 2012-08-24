<!-- autolink script -->
<script type="text/javascript">
    $(document).ready(function() {
      $('.p_text').html($('.p_text').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo $this->webroot ?>instagrams/index/$1" class="tag_replace">#$1</a>'));
      $('.p_comment').html($('.p_comment').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo $this->webroot ?>instagrams/index/$1" class="tag_replace">#$1</a>'));
    });
</script>



<div class="p_image">
    <?php echo $this->Html->image($media['data']['images']['standard_resolution']['url'],array('alt'=>'image','width'=>'612','height'=>'612')) ?>
</div><!-- /.p_image -->
<div class="p_text">
    <div class="p_text_avatar"><?php echo $this->Html->image($media['data']['caption']['from']['profile_picture'],array('alt'=>'profile picture','width'=>'50','height'=>'50')); ?></div>
    <div class="p_text_text">
    <?php 
        echo $this->Html->link($media['data']['caption']['from']['username'],array(
            'controller'=>'instagrams',
            'action'=>'viewprofile',
            $media['data']['caption']['from']['id']));
        echo $media['data']['caption']['text'];    
     ?>
     </div>
</div><!--/p_text  -->
<div class="p_like">
    <?php echo $this->Html->image('icons/likes.png',array('alt'=>'likes')) ?>
    Like by
    <?php foreach($media['data']['likes']['data'] as $like): ?>
        <?php 
            echo $this->Html->link($like['username'],array(
                'controller'=>'instagrams',
                'action'=>'viewprofile',
                $like['id'])); ?>
    <?php endforeach?>
</div><!-- /.p_like -->
<div class="p_comment">
    <?php
        foreach($media['data']['comments']['data'] as $comment):
            
    ?>
    <div class="comment-avatar">
        <?php 
            echo $this->Html->image($comment['from']['profile_picture'],array(
                'width'=>'30',
                'height'=>'30',
                'url'=>array(
                    'controller'=>'instagrams',
                    'action'=>'viewprofile',
                    $comment['from']['id']
                    )));
        ?>
    </div><!-- /comment-avatar -->
    <div class="comment-inner">
    <?php        
    echo $this->Html->link($comment['from']['username']." ",array('controller'=>'instagrams','action'=>'viewprofile',$comment['from']['id']));
    echo $comment['text']; 
    ?>
    </div> <!-- /.comment-inner -->
    <?php 
        endforeach;
     ?>
</div><!-- /.p_comment -->
