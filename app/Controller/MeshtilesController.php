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
            $this->Session->write($tags.'next_page', 1);
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
            $next_page = $this->Session->read($tags.'next_page');
            $popular = $meshtiles->getRecentTags($tags, $next_page);
            $response = json_decode($popular, true);
            //debug($response);
            if(count($response['photo']==32))
                $this->Session->write($tags.'next_page', $next_page+1);
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
        //debug($accessToken);
        $meshtiles->setAccessToken($accessToken);
        $response=$meshtiles->getUser($config->cfg);
        $userinfo=json_decode($response,true);
        //debug($userinfo);
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
    function load_comment($id){
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        if ($session) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);           
            $comments=$meshtiles->getMediaComments($id);
            $comment=json_decode($comments,true);
            $this->set('comments',$comment);
            $this->render('new_comment');
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
        if(!$id)
            $id=$userinfo['user_id'];
        $meshtiles = new Meshtiles($config->cfg);
        $session = $this->Session->read('MeshtilesAccessToken');
        if($session){
            $this->set('session', $session);
            $meshtiles->setAccessToken($session);
            $response=$meshtiles->getUserViewDetail($id);
            $user=json_decode($response,true);
            $this->set('user',$user);
            //debug($user);
            //Bắt load từ đầu
            $this->Session->write($id.'_end',false);
            $this->Session->write($id.'next_cursor','');
            $this->Session->write($id.'next_page', 1);
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
                $page_index = $this->Session->read($id.'next_page');
                $response = $meshtiles->getUserRecent($id,$page_index);
                $media = json_decode($response, true);
                //debug($media);
                if(count($media['photo'])==32){
                    $this->Session->write($id.'next_page',$page_index+1 );                       
                }
                else{
                    $this->Session->write($id.'_end',true);
                }
                $this->set('response', $media);
                $this->render('lazyload');                    
            }
        }            
    }
    function popular(){
        $config = new meshconfig();
        $meshtiles=new Meshtiles($config->cfg);
        $this->set('tags',$tags);
        $session = $this->Session->read('MeshtilesAccessToken');
        $this->set('session', $session);
        if($session){
            $this->Session->write('popular_next_page', 1);
            $this->Session->write('popular_end',false);
        }else{
            $meshtiles->openAuthorizationUrl();
        }

    }
    function loadpopular(){
        $config = new meshconfig();
        $this->layout = '';
        $session = $this->Session->read('MeshtilesAccessToken');
        $this->set('session', $session);
        $end=$this->Session->read('popular_end');
        if ($session&&(!$end)) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $next_page = $this->Session->read('popular_next_page');
            $popular = $meshtiles->getPopularMedia($next_page);
            $response = json_decode($popular, true);
            //debug($response);
            if(count($response['photo']==32))
                $this->Session->write('popular_next_page', $next_page+1);
            else
                $this->Session->write('popular_end',true);
            $this->set('response', $response);
            $this->render('lazyload');
        }
        else
            $this->render('loaduserrecent');
    }
    function userfollows($id=null){

        $config = new meshconfig();
        $this->layout='';
        $session = $this->Session->read('MeshtilesAccessToken');
        if($session&&$id){
            $this->set('id',$id);
            $this->Session->write($id.'next_page',1);
        }
        
                   
    }
    function loaduserfollows($id=null){

        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $next_page=$this->Session->read($id.'next_page');
        if($session&&$id&&$next_page){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $followed=$meshtiles->getUserFollows($id,$next_page);
            $data=json_decode($followed,true);
            $this->set('data',$data);
            //debug($data);
            if(count($data['user'])==100){
                $this->Session->write($id.'next_page',$next_page+1);
                //debug($data);                
            }else{
                $this->Session->write($id.'next_page',0);
            }

        }
        $this->layout='';
    }
    function userfollowedby($id=null){
        $config = new meshconfig();
        //$this->layout='';
        $session = $this->Session->read('MeshtilesAccessToken');
        if($session&&$id){
            $this->set('id',$id);
            $this->Session->write($id.'next_page_by',1);
        }
                   
    }
    function loaduserfollowedby($id=null){
        $config = new meshconfig();
        $session = $this->Session->read('MeshtilesAccessToken');
        $next_page=$this->Session->read($id.'next_page_by');
        if($session&&$id&&$next_page){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($session);
            $followed=$meshtiles->getUserFollowedBy($id,$next_page);
            $data=json_decode($followed,true);
            $this->set('data',$data);
            //debug($data);
            if(count($data['user'])==100){
                $this->Session->write($id.'next_page_by',$next_page+1);
                //debug($data);                
            }else{
                $this->Session->write($id.'next_page_by',0);
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