<?php

/**

 * @author duythanh

 * @copyright 2012

 */

class MeshtilesController extends AppController
{
    var $name = "Meshtiles";
    var $helpers = array(
        "Html",
        "Form",
        "Paginator");
    var $uses = array();
    var $components = array('Email', 'Session');
    var $_sessionUsername = "Username";
    var $layout = 'meshbootstrap';
    //var $layout='default';
    function index($tags = null)
    {
        $config = new meshconfig();
        $meshtiles=new Meshtiles($config->cfg);
        if (!($tags)) {
            $tags = 'tech';
        }
        $this->set('tags',$tags);
        $session = $this->Session->read('MeshtilesAccessToken');
        $this->set('session', $session);
        if($session){
            $this->Session->write('next_page', 1);
            $this->Session->write($tags.'end',false);
        }else{
            $meshtiles->openAuthorizationUrl();
        }
        
    }

    function lazyload($tags = null,$next_page = null)
    {
        $config = new meshconfig();
        if (!($tags)) {
            $tags = 'tech';
        }
        $this->layout = '';
        $session = $this->Session->read('MeshtilesAccessToken');
        //debug($session);
        $this->set('session', $session);
        $end=$this->Session->read($tags.'end');
        if ($session&&(!$end)) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $next_page = $this->Session->read('next_page');
            $popular = $meshtiles->getRecentTags($tags, $next_page);
            $response = json_decode($popular, true);
            //debug($response);
            if(count($response['photo']==32))
                $this->Session->write('next_page', $next_page+1);
            else
                $this->Session->write($tags.'end',true);
            $this->set('response', $response);
        }
        else
            $this->render('loaduserrecent');
    }

    function photo($id = null)
    {
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        if ($session) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $response = $meshtiles->getMedia($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
            $tags=split(',',$media['photo']['list_tags']);
            $this->set('tags',$tags);
        }
        $this->layout = '';
    }
    function success()
    {
        $config = new meshconfig();
        $meshtiles = new Meshtiles($config->cfg);
        $accessToken = $_GET['access_token'];
        debug($accessToken);
        $meshtiles->setAccessToken($accessToken);
        $response=$meshtiles->getUser($config->cfg);
        $userinfo=json_decode($response,true);
        debug($userinfo);
        $this->Session->write('MeshtilesAccessToken', $accessToken);
        $this->Session->write('UserInfo',$userinfo);
        //$this->set('config',$config);
        $this->render('success');
        $this->redirect(array('controller' => 'meshtiles', 'action' => 'index'));
    }
    function logout()
    {
        $this->Session->write('MeshtilesAccessToken', null);
        $this->Session->destroy();
        //$this->redirect(array('controller'=>'meshtiless','action'=>'index'));

    }
    function new_comment(){
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        if ($session) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);           
            $response = $meshtiles->postMediaComment($_POST['id'],$_POST['text']);
            $data = json_decode($response, true);
            $comments=$meshtiles->getMediaComments($_POST['id']);
            $comment=json_decode($comments,true);
            $this->set('comments',$comment);
            //debug($comment);
            //$this->set('data', $data);
        }
        $this->layout = '';        
    }
    function like($like=null,$id=null){
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        if ($session) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            if($like)           
                $meshtiles->postLike($id);
            else
                $meshtiles->removeLike($id);
            $response=$meshtiles->getMedia($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
        }
        $this->layout = '';  
    }
    function viewprofile($id=null){
        $config = new meshconfig();
        $this->set('config', $config);
        $userinfo=$this->Session->read('UserInfo');
//        $this->set('userinfo',$userinfo);
        if(!$id)
            $id=$userinfo['data']['id'];
        $meshtiles = new Meshtiles($config->cfg);
        $session = $this->Session->read('MeshtilesAccessToken');
        if($session){
            $this->set('session', $session);
            $meshtiles->setAccessToken($session);
            $response=$meshtiles->getUser($id);
            $user=json_decode($response,true);
            $this->set('user',$user);
            //Bắt load từ đầu
            $this->Session->write($id.'_end',false);
            $this->Session->write($id.'next_cursor','');
            $this->Session->write($id.'_next_max_tag_id', '');
        }
        else $this->redirect(array('controller'=>'meshtiless','action'=>'index'));

        //debug($user);
        
    }
    function loaduserrecent($id=null){
        $config = new meshconfig();
        $this->layout = '';
        $session = $this->Session->read('MeshtilesAccessToken');
        $this->set('session', $session);
        //debug($session);
        //kiểm tra tồn tại accesstoken
        if ($session&&$id) {
            $end=$this->Session->read($id.'_end');
            //Kiểm tra xem là trang cuối cùng hay chưa
            if($end){
                $this->render('loaduserrecent');
            }else{
                $meshtiles = new Meshtiles($config->cfg);
                $meshtiles->setAccessToken($session);
                $max_id = $this->Session->read($id.'_next_max_tag_id');
                $response = $meshtiles->getUserRecent($id,$max_id);
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
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
//        $userinfo=$this->Session->read('UserInfo');
//        $this->set('userinfo',$userinfo);
        //debug($userinfo);
        $this->set('session', $session);
        //debug($session);
        if ($session) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $popular = $meshtiles->getPopularMedia();
            $media = json_decode($popular, true);
            $this->set('media',$media);
            //debug($media);
        }
        else $this->redirect(array('controller'=>'meshtiless','action'=>'index'));
    }
    function userfollows($id=null){

        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        if($session&&$id){
            $this->set('id',$id);
            $this->Session->write($id.'next_cursor','');
        }
        $this->layout='';           
    }
    function loaduserfollows($id=null){

        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $next_cursor=$this->Session->read($id.'next_cursor');
        if($session&&$id&&($next_cursor!=1)){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $followed=$meshtiles->getUserFollows($id,$next_cursor);
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
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        if($session&&$id){
            $this->set('id',$id);
            $this->Session->write($id.'next_cursor_by','');
            $this->layout='';
        }
        else $this->redirect(array('controller'=>'meshtiless','action'=>'index'));
                   
    }
    function loaduserfollowedby($id=null){
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $next_cursor=$this->Session->read($id.'next_cursor_by');
        if($session&&$id&&($next_cursor!=1)){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $followed=$meshtiles->getUserFollowedBy($id,$next_cursor);
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
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $option=$_POST['searchby'];
        $keyword=$_POST['search'];
        $this->set('option',$option);
        $this->set('session', $session);
//        $userinfo=$this->Session->read('UserInfo');
//        $this->set('userinfo',$userinfo);
        if($option&&$keyword){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            if($option=='tag'){
                $response=$meshtiles->searchTags($keyword);
                $result=json_decode($response,true);
                $this->set('media',$result);
                //debug($result);
            }
            if($option=='username'){
                $response=$meshtiles->searchUser($keyword);
                $result=json_decode($response,true);
                $this->set('user',$result);
                //debug($result);
            }
            
        }
        else $this->redirect(array('controller'=>'meshtiless','action'=>'index'));
    }
    function feed(){
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $this->set('session', $session);
        if($session){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $response=$meshtiles->getUserFeed();
            $feed=json_decode($response,true);
            debug($feed);
        }
        else $this->redirect(array('controller'=>'meshtiless','action'=>'index'));        
    }
    function locationrecentmedia($id=null){
        $config=new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $this->set('session', $session);
        if($session&&$id){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $response=$meshtiles->getLocationRecentMedia($id);
            $media=json_decode($response,true);
            //debug($media);
            $this->set('media',$media);
            $this->render('popular');            
        }
        else $this->redirect(array('controller'=>'meshtiless','action'=>'index'));        
    }
    function nearby($latitude=null, $longitude=null){
        $config=new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $this->set('session', $session);
        if($session&&isset($latitude)&&isset($longitude)){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $response=$meshtiles->searchLocation($latitude,$longitude);
            $location=json_decode($response,true);
            $this->set('location',$location);
            //debug($location);
        }else
        $this->redirect(array('controller'=>'meshtiless','action'=>'index'));       
        
    }
    function media($id = null){
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        if ($session&&$id) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $response = $meshtiles->getMedia($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
            $this->render('photo');
        }
        else 
            $this->redirect(array('controller'=>'meshtiless','action'=>'index'));
    }
}





?>