<?php /* ?>
<p id="searchresults">
    <?php if(isset($tags)){ ?>
    <span class="tags">Tags</span>
    <?php 
        foreach($tags['tag'] as $tag):
    ?>
    
    <a href="<?php echo $this->webroot.'meshtiles/index/'.$tag['tag_name'] ?>">
        <span class="searchheading"><?php echo $tag['tag_name'] ?></span>
        <span><?php echo $tag['number_post'] ?> Post</span>
    </a>
   <?php endforeach ?>
   <?php }?>
   <?php if(isset($users)){ ?>
        <span class="tags">User</span>
        <?php foreach($users['user'] as $user):?>
            <a href="<?php echo $this->webroot.'meshtiles/viewprofile/'.$user['user_id'] ?>">
                <img alt="" width="30" height="30" src="<?php echo $user['url_image'] ?>"/>
                <span class="searchheading"><?php echo $user['user_name'] ?></span>
                <span><?php echo $user['first_name'].$user['last_name'] ?> </span>
            </a>
        <?php endforeach ?>
   <?php }?>
</p>
<?php */ ?>
<?php 
    if(isset($encoded_result))
        echo $encoded_result;
?>