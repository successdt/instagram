<?php
/*
session_start();
$_SESSION = array();
session_destroy();
*/
?>
<h1>You are now logged out</h1>
<?php
echo $this->Html->link('Home',array('controller'=>'meshtiles','action'=>'index'));
 ?>