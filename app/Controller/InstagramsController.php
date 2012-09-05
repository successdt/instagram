<?php



/**

 * @author duythanh

 * @copyright 2012

 */

class InstagramsController extends AppController
{
    var $name = "Instagrams";
    var $helpers = array(
        "Html",
        "Form",
        "Paginator");
    var $uses = array();
    var $components = array('Email', 'Session');
    var $_sessionUsername = "Username";
    var $layout = 'bootstrap';
    //var $layout='default';
    function index($tags = null)
    {
        $config = new config();
        $this->set('config', $config);
        if (!($tags)) {
            $tags = 'tech';
        }
        $this->set('tags', $tags);
        $session = $this->Session->read('InstagramAccessToken');
//        $userinfo=$this->Session->read('UserInfo');
//        $this->set('userinfo',$userinfo);
        //debug($userinfo);
        $this->set('session', $session);
        //debug($session);
        if ($session) {
            //$instagram = new Instagram($config->cfg);
            //$instagram->setAccessToken($session);
            //$popular = $instagram->getRecentTags($tags);
            //$response = json_decode($popular, true);
            $this->Session->write('next_max_tag_id', '');
            $this->Session->write($tags.'end',false);
            //$this->set('response',$response);
        }
    }

    function lazyload($tags = null, $min_id = null, $max_id = null)
    {
        $config = new config();
        if (!($tags)) {
            $tags = 'tech';
        }
        $this->layout = '';
        $session = $this->Session->read('InstagramAccessToken');
        $this->set('session', $session);
        $end=$this->Session->read($tags.'end');
        //debug($session);
        if ($session&&(!$end)) {
            $instagram = new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $max_id = $this->Session->read('next_max_tag_id');
            $popular = $instagram->getRecentTags($tags, $max_id);
            $response = json_decode($popular, true);
            //debug($response);
            if(isset($response['pagination']['next_max_tag_id']))
                $this->Session->write('next_max_tag_id', $response['pagination']['next_max_tag_id']);
            else
                $this->Session->write($tags.'end',true);
            $this->set('response', $response);

        }
        else
            $this->render('loaduserrecent');
            
        

    }

    function photo($id = null)
    {
        $config = new config();
        //$this->set('config',$config);
        //$this->set('id', $id);
        $session = $this->Session->read('InstagramAccessToken');
        if ($session) {
            $instagram = new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $response = $instagram->getMedia($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
        }
        $this->layout = '';
    }
    function success()
    {
        $config = new config();
        $instagram = new Instagram($config->cfg);
        $accessToken = $instagram->getAccessToken();
        $response=$instagram->getUser($accessToken);
        $userinfo=json_decode($response,true);
        $this->Session->write('InstagramAccessToken', $accessToken);
        $this->Session->write('UserInfo',$userinfo);
        //$this->set('config',$config);
        $this->render('success');
        $this->redirect(array('controller' => 'instagrams', 'action' => 'index'));
    }
    function logout()
    {
        $this->Session->write('InstagramAccessToken', null);
        $this->Session->destroy();
        //$this->redirect(array('controller'=>'instagrams','action'=>'index'));

    }
    function new_comment(){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        if ($session) {
            $instagram = new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            debug($_POST['id']);            
            $response = $instagram->postMediaComment($_POST['id'],$_POST['text']);
            $data = json_decode($response, true);
            debug($data);
            //$this->set('data', $data);
        }
        $this->layout = '';        
    }
    function like($like=null,$id=null){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        if ($session) {
            $instagram = new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            if($like)           
                $instagram->postLike($id);
            else
                $instagram->removeLike($id);
            $response=$instagram->getLikes($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
        }
        $this->layout = '';  
    }
    function viewprofile($id=null){
        $config = new config();
        $this->set('config', $config);
        $userinfo=$this->Session->read('UserInfo');
//        $this->set('userinfo',$userinfo);
        if(!$id)
            $id=$userinfo['data']['id'];
        $instagram = new Instagram($config->cfg);
        $session = $this->Session->read('InstagramAccessToken');
        if($session){
            $this->set('session', $session);
            $instagram->setAccessToken($session);
            $response=$instagram->getUser($id);
            $user=json_decode($response,true);
            $this->set('user',$user);
            //Bắt load từ đầu
            $this->Session->write($id.'_end',false);
            $this->Session->write($id.'next_cursor','');
            $this->Session->write($id.'_next_max_tag_id', '');
        }
        else $this->redirect(array('controller'=>'instagrams','action'=>'index'));

        //debug($user);
        
    }
    function loaduserrecent($id=null){
        $config = new config();
        $this->layout = '';
        $session = $this->Session->read('InstagramAccessToken');
        $this->set('session', $session);
        //debug($session);
        //kiểm tra tồn tại accesstoken
        if ($session&&$id) {
            $end=$this->Session->read($id.'_end');
            //Kiểm tra xem là trang cuối cùng hay chưa
            if($end){
                $this->render('loaduserrecent');
            }else{
                $instagram = new Instagram($config->cfg);
                $instagram->setAccessToken($session);
                $max_id = $this->Session->read($id.'_next_max_tag_id');
                $response = $instagram->getUserRecent($id,$max_id);
                $media = json_decode($response, true);
                //debug($media);
                if(isset($media['pagination']['next_max_id'])){
                    $this->Session->write($id.'_next_max_tag_id', $media['pagination']['next_max_id']);                       
                }
                else{
                    $this->Session->write($id.'_end',true);
                }
                $this->set('media', $media);
                $this->render('popular');                    
            }
        }            
    }//chưa hoàn thành
    function popular(){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
//        $userinfo=$this->Session->read('UserInfo');
//        $this->set('userinfo',$userinfo);
        //debug($userinfo);
        $this->set('session', $session);
        //debug($session);
        if ($session) {
            $instagram = new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $popular = $instagram->getPopularMedia();
            $media = json_decode($popular, true);
            $this->set('media',$media);
            //debug($media);
        }
        else $this->redirect(array('controller'=>'instagrams','action'=>'index'));
    }
    function userfollows($id=null){

        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        if($session&&$id){
            $this->set('id',$id);
            $this->Session->write($id.'next_cursor','');
        }
        $this->layout='';           
    }
    function loaduserfollows($id=null){

        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        $next_cursor=$this->Session->read($id.'next_cursor');
        if($session&&$id&&($next_cursor!=1)){
            $instagram=new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $followed=$instagram->getUserFollows($id,$next_cursor);
            $data=json_decode($followed,true);
            $this->set('data',$data);
            //debug($data);
            if(isset($data['pagination']['next_cursor'])){
                $this->Session->write($id.'next_cursor',$data['pagination']['next_cursor']);
                //debug($data);                
            }else{
                $this->Session->write($id.'next_cursor','1');
            }

        }
        $this->layout='';
    }
    function userfollowedby($id=null){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        if($session&&$id){
            $this->set('id',$id);
            $this->Session->write($id.'next_cursor_by','');
            $this->layout='';
        }
        else $this->redirect(array('controller'=>'instagrams','action'=>'index'));
                   
    }
    function loaduserfollowedby($id=null){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        $next_cursor=$this->Session->read($id.'next_cursor_by');
        if($session&&$id&&($next_cursor!=1)){
            $instagram=new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $followed=$instagram->getUserFollowedBy($id,$next_cursor);
            $data=json_decode($followed,true);
            $this->set('data',$data);
            if(isset($data['pagination']['next_cursor'])){
                $this->Session->write($id.'next_cursor_by',$data['pagination']['next_cursor']);
                //debug($data);                
            }else{
                $this->Session->write($id.'next_cursor_by','1');
            }

        }
        $this->layout='';
        $this->render('loaduserfollows');
    }
    function search(){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        $option=$_POST['searchby'];
        $keyword=$_POST['search'];
        $this->set('option',$option);
        $this->set('session', $session);
//        $userinfo=$this->Session->read('UserInfo');
//        $this->set('userinfo',$userinfo);
        if($option&&$keyword){
            $instagram=new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            if($option=='tag'){
                $response=$instagram->searchTags($keyword);
                $result=json_decode($response,true);
                $this->set('media',$result);
                //debug($result);
            }
            if($option=='username'){
                $response=$instagram->searchUser($keyword);
                $result=json_decode($response,true);
                $this->set('user',$result);
                //debug($result);
            }
            
        }
        else $this->redirect(array('controller'=>'instagrams','action'=>'index'));
    }
    function feed(){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        $this->set('session', $session);
        if($session){
            $instagram=new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $response=$instagram->getUserFeed();
            $feed=json_decode($response,true);
            debug($feed);
        }
        else $this->redirect(array('controller'=>'instagrams','action'=>'index'));        
    }
    function locationrecentmedia($id=null){
        $config=new config();
        $session = $this->Session->read('InstagramAccessToken');
        $this->set('session', $session);
        if($session&&$id){
            $instagram=new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $response=$instagram->getLocationRecentMedia($id);
            $media=json_decode($response,true);
            //debug($media);
            $this->set('media',$media);
            $this->render('popular');            
        }
        else $this->redirect(array('controller'=>'instagrams','action'=>'index'));        
    }
    function nearby($latitude=null, $longitude=null){
        $config=new config();
        $session = $this->Session->read('InstagramAccessToken');
        $this->set('session', $session);
        if($session&&isset($latitude)&&isset($longitude)){
            $instagram=new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $response=$instagram->searchLocation($latitude,$longitude);
            $location=json_decode($response,true);
            $this->set('location',$location);
            //debug($location);
        }else
        $this->redirect(array('controller'=>'instagrams','action'=>'index'));       
        
    }
    function media($id = null){
        $config = new config();
        $session = $this->Session->read('InstagramAccessToken');
        if ($session&&$id) {
            $instagram = new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $response = $instagram->getMedia($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
            $this->render('photo');
        }
        else 
            $this->redirect(array('controller'=>'instagrams','action'=>'index'));
    }
}





?>