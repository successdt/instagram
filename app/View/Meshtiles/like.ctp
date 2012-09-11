<?php
/*
foreach ($media['data'] as $like){
    echo $this->Html->link(' @'.$like['username'], array(
        'controller' => 'instagrams',
        'action' => 'viewprofile',
        $like['id']));
}
*/
 ?>
<?php echo $media['photo']['number_click']?>
<?php echo $this->Html->image('icons/likes.png', array('alt' => 'likes')) ?>