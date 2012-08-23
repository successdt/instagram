<?php
/**
 * @author duythanh
 * @copyright 2012
 */
?>
<script type="text/javascript">
    $(document).ready(function(){
       $('.thumbnail').click(function(){;        
            var id=$(this).attr('id');                
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('http://localhost/instagram/instagrams/photo/'+id);                      
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
foreach($response['data'] as $data): ?>
    <div class="display-block ">
        <div class="thumbnail" id="<?php echo $data['id'] ?>"><?php echo $this->Html->image($data['images']['low_resolution']['url'],array('width'=>'204','height'=>'204')); ?></div><!-- /.thumbnail -->
        <div class="tags">
            <?php
            //Lọc lấy tags 
            $tags=split('#',$data['caption']['text']);
            $n=0;
            echo $tags[0];
            foreach($tags as $tag):
                $nospace=split(' ',$tag);
                if($n>0)
                    echo $this->Html->link('#'.$tag,array('controller'=>'instagrams','action'=>'index',$nospace[0]));
                $n++;
            endforeach?>
        </div><!-- /tags -->
        <div class="metadata">
            <?php echo $this->Html->image('icons/likes.png',array('alt'=>'likes')) ?>
            <?php echo $data['likes']['count']; ?>
            <?php echo $this->Html->image('icons/comments.png',array('alt'=>'comments')) ?>
            <?php echo $data['comments']['count']; ?>
        </div><!-- /.metadata -->
        <div class="comment">
            <?php
            $i=0; 
            foreach($data['comments']['data'] as $comment): ?>
            
                <?php if($i==3) break; ?>
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
                $i++;
                ?>
                </div> <!-- /.comment-inner -->
            <?php endforeach ?>
        </div><!-- /comment -->
    </div><!-- /.display-block -->
<?php endforeach?>