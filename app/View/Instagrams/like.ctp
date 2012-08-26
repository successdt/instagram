<?php
foreach ($media['data'] as $like){
    echo $this->Html->link(' @'.$like['username'], array(
        'controller' => 'instagrams',
        'action' => 'viewprofile',
        $like['id']));
}
 ?>