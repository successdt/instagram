<?php
/**
 * @author duythanh
 * @copyright 2012
 */
?>

<!-- autolink script -->
<script type="text/javascript">
    $(document).ready(function() {
      $('#content').html($('#content').html().replace(/#([a-zA-Z1-9]{1,})/gi,'<a href="<?php echo
$this->webroot ?>instagrams/index/$1" class="tag_replace">#$1</a>'));
    });
</script>

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
       }) 
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
       $('#content').masonry('reload'); 
    });
</script>
<?php
foreach ($response['data'] as $data): ?>
    <div class="display-block" id="<?php echo $data['id'] ?>">
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