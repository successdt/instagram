<?php
/**
 * @author duythanh
 * @copyright 2012
 */
 
/**
 * Nếu không tồn tại SESSION access token thì phải đăng nhập
 *
 */

if(!$session){
    $instagram = new Instagram($config->cfg);
    $instagram->openAuthorizationUrl();  
}
/**
 * Nếu tồn tại rồi thì bắt đầu lấy dữ liệu
 */
function tag_replace($string){
        $temp_strings=split('#',$string);
        $result='';
        //debug($temp_strings);
        $i=1;
        foreach($temp_strings as $temp_string){
            if($i!=1){
                $array=split(' ',$temp_string);
                //debug($array);
                $replace='<a class="tag_replace" href="http://localhost/instagram/instagrams/index/'.$array[0].'">#'.$array[0].'</a>';
                $temp_string=str_replace($array[0],$replace,$temp_string);
                //debug($temp_string);
                $result=$result.$temp_string;
            }
            $i++;

        }
        return $result;
}
?>


<script type="text/javascript">
    $(document).ready(function(){
       $('.thumbnail').click(function(){;        
            var id=$(this).attr('id');                
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
$(window).scroll(function()
{
    if($(window).scrollTop() == $(document).height() - $(window).height())
    {
        $('#loading').show();
        $.ajax({
        url: "<?php echo $this->webroot; ?>instagrams/lazyload/<?php echo $tags  ?>",
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
});
</script>
<?php

foreach($response['data'] as $data):?>
    <?php //debug($data); ?>
    <div class="display-block">
        <div class="thumbnail" id="<?php echo $data['id'] ?>"><?php echo $this->Html->image($data['images']['low_resolution']['url'],array('width'=>'204','height'=>'204')); ?></div><!-- /.thumbnail -->
        <div class="tags">
            <?php echo tag_replace($data['caption']['text']);?>
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
                echo $this->Html->link($comment['from']['username']." ",array('controller'=>'instagrams','action'=>'viewprofile',
                        $comment['from']['id']),array('style'=>'font-weight:bold;'));
                echo tag_replace($comment['text']); 
                $i++;
                ?>
                </div> <!-- /.comment-inner -->
            <?php endforeach ?>
        </div><!-- /comment -->
    </div><!-- /.display-block -->

<?php endforeach?>



