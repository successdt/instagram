<?php
/**
 * @author duythanh
 * @copyright 2012
 */

/**
 * Nếu không tồn tại SESSION access token thì phải đăng nhập
 *
 */

if (!$session) {
    $instagram = new Instagram($config->cfg);
    $instagram->openAuthorizationUrl();
}
/**
 * Nếu tồn tại rồi thì bắt đầu lấy dữ liệu
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
    <?php foreach($user['data'] as $user):?>
        <div class="display-block" id="userinfo-block">
            <div class="block-avatar inline">
                <?php echo $this->Html->image($user['profile_picture'],array('width'=>'50','height'=>'50')) ?>
            </div> <!-- /block-avatar -->
            <div class="block-info inline">
                <div class="block-username"><?php echo $this->Html->link($user['username'],array(
                    'controller'=>'instagrams',
                    'action'=>'viewprofile',
                    $user['id'])) ?>
                </div><!-- block-username -->
                <div class="block-fullname"><?php echo $user['full_name'] ?></div><!-- block-fullname -->
            </div> <!-- /block-info -->
            <div class="block-bio">
                <?php if($user['website']) echo $this->Html->link($user['website'],$user['website']) ?>
                <?php echo $user['bio'] ?>
            </div><!--/block-bio  -->
            <button class="follow white" data-user-id="<?php echo $user['id'] ?>">Follow</button>
            <button class="follow white" onclick="window.location='<?php echo $this->webroot ?>instagrams/viewprofile/<?php echo $user['id'] ?>'">View profile
            </button>
        </div> <!-- /.display-block -->
    <?php endforeach ?>
<?php } ?>