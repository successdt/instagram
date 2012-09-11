<?php if($comments){?>
    <?php foreach($comments['comment'] as $comment): ?>
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
    <?php endforeach ?>
<?php }?>