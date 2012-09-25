<?php
/**
 * @author duythanh
 * @copyright 2012
 */

?>
<?php if(isset($media)){?>
    <?php foreach($media['data'] as $data): ?>
        <div class="display-block">
            <div class="search-result">
                <span><?php echo $data['name'] ?></span>
                <div class="search-count"><?php echo $data['media_count'] ?></div>            
            </div>
            <button class="follow white" onclick="window.location='<?php echo $this->webroot?>instagrams/index/<?php echo $data['name'] ?>'">view</button>
        </div>
    <?php endforeach ?>
<?php }?>
<?php if(isset($user)){?>
    <?php foreach($user['user'] as $user):?>
        <div class="display-block" id="userinfo-block">
            <div class="block-avatar inline">
                <?php echo $this->Html->image($user['url_image'],array('width'=>'50','height'=>'50')) ?>
            </div> <!-- /block-avatar -->
            <div class="block-info inline">
                <div class="block-username">
                    <?php echo $this->Html->link($user['user_name'],array(
                        'controller'=>'meshtiles',
                        'action'=>'viewprofile',
                        $user['user_id'])) ?>
                </div><!-- block-username -->
                <div class="block-fullname"><?php echo $user['first_name'].$user['last_name'] ?></div><!-- block-fullname -->
            </div> <!-- /block-info -->
            <div class="block-bio">
                <?php echo $user['about'] ?>
                <?php 
                    if($user['is_following']) 
                        echo "Following";
                ?>
            </div><!--/block-bio  -->
            <?php if(!$user['is_following']) {?>
            <button class="follow white" data-user-id="<?php echo $user['user_id'] ?>">Follow</button>
            <?php }?>
            <button class="follow white" onclick="window.location='<?php echo $this->webroot ?>meshtiles/viewprofile/<?php echo $user['user_id'] ?>'">View profile
            </button>
        </div> <!-- /.display-block -->
    <?php endforeach ?>
<?php } ?>