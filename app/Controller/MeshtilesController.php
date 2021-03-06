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
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        if($cookie){
            $this->Cookie->write($tags.'next_page', 1);
            $this->Cookie->write($tags.'end',false);
        }else{
            $meshtiles->openAuthorizationUrl();
        }
        
    }
    //Load list photo
    function lazyload($tags = null,$next_page = null)
    {
        $config = new meshconfig();
        if (!($tags)) {
            $tags = 'tech';
        }
        $this->layout = '';
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        //debug($cookie);
        $this->set('session', $cookie);
        $end=$this->Cookie->read($tags.'end');
        if ($cookie&&(!$end)) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $next_page = $this->Cookie->read($tags.'next_page');
            $popular = $meshtiles->getRecentTags($tags, $next_page);
            $response = json_decode($popular, true);
            //debug($response);
            if(!$response['is_success'])
                $meshtiles->openAuthorizationUrl();
            if(count($response['photo']==32))
                $this->Cookie->write($tags.'next_page', $next_page+1);
            else
                $this->Cookie->write($tags.'end',true);
            $this->set('response', $response);
        }
        else
            $this->render('loaduserrecent');
    }
    
    function photo($id = null)
    {
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if ($cookie) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
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
        $this->Cookie->write('MeshtilesAccessToken', $accessToken);
        $this->Cookie->write('UserInfo',$userinfo);
        $this->render('success');
        $this->redirect(array('controller' => 'meshtiles', 'action' => 'index'));
    }
    function logout()
    {
        $this->Cookie->write('MeshtilesAccessToken', null);
        $this->Cookie->destroy();
        //$this->redirect(array('controller'=>'meshtiles','action'=>'index'));

    }
    function new_comment(){
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if ($cookie) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);           
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
        $this->layout = '';
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if ($cookie) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);           
            $comments=$meshtiles->getMediaComments($id);
            $comment=json_decode($comments,true);
            $this->set('comments',$comment);
            $this->render('new_comment');
        }
                 
    }
    function like($like=null,$id=null){
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if ($cookie) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
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
        $userinfo=$this->Cookie->read('UserInfo');
        if(!$id)
            $id=$userinfo['user_id'];
        $meshtiles = new Meshtiles($config->cfg);
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if($cookie){
            $this->set('session', $cookie);
            $meshtiles->setAccessToken($cookie);
            $response=$meshtiles->getUserViewDetail($id);
            $user=json_decode($response,true);
            $this->set('user',$user);
            //debug($user);
            //Bắt load từ đầu
            $this->Cookie->write($id.'_end',false);
            $this->Cookie->write($id.'next_cursor','');
            $this->Cookie->write($id.'next_page', 1);
        }
        else $this->redirect(array('controller'=>'meshtiles','action'=>'index'));

        //debug($user);
        
    }
    function loaduserrecent($id=null){
        $config = new meshconfig();
        $this->layout = '';
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        //debug($cookie);
        //kiểm tra tồn tại accesstoken
        if ($cookie&&$id) {
            $end=$this->Cookie->read($id.'_end');
            //Kiểm tra xem là trang cuối cùng hay chưa
            if($end){
                $this->render('loaduserrecent');
            }else{
                $meshtiles = new Meshtiles($config->cfg);
                $meshtiles->setAccessToken($cookie);
                $page_index = $this->Cookie->read($id.'next_page');
                $response = $meshtiles->getUserRecent($id,$page_index);
                $media = json_decode($response, true);
                //debug($media);
                if(count($media['photo'])==32){
                    $this->Cookie->write($id.'next_page',$page_index+1 );                       
                }
                else{
                    $this->Cookie->write($id.'_end',true);
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
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        if($cookie){
            $this->Cookie->write('popular_next_page', 1);
            $this->Cookie->write('popular_end',false);
        }else{
            $meshtiles->openAuthorizationUrl();
        }

    }
    function loadpopular(){
        $config = new meshconfig();
        $this->layout = '';
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        $end=$this->Cookie->read('popular_end');
        if ($cookie&&(!$end)) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $next_page = $this->Cookie->read('popular_next_page');
            $popular = $meshtiles->getPopularMedia($next_page);
            $response = json_decode($popular, true);
            //debug($response);
            if(count($response['photo']==32))
                $this->Cookie->write('popular_next_page', $next_page+1);
            else
                $this->Cookie->write('popular_end',true);
            $this->set('response', $response);
            $this->render('lazyload');
        }
        else
            $this->render('loaduserrecent');
    }
    function userfollows($id=null){

        $config = new meshconfig();
        $this->layout='';
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if($cookie&&$id){
            $this->set('id',$id);
            $this->Cookie->write($id.'next_page',1);
        }
        
                   
    }
    function loaduserfollows($id=null){

        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $next_page=$this->Cookie->read($id.'next_page');
        if($cookie&&$id&&$next_page){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $followed=$meshtiles->getUserFollows($id,$next_page);
            $data=json_decode($followed,true);
            $this->set('data',$data);
            //debug($data);
            if(count($data['user'])==100){
                $this->Cookie->write($id.'next_page',$next_page+1);
                //debug($data);                
            }else{
                $this->Cookie->write($id.'next_page',0);
            }

        }
        $this->layout='';
    }
    function userfollowedby($id=null){
        $config = new meshconfig();
        //$this->layout='';
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if($cookie&&$id){
            $this->set('id',$id);
            $this->Cookie->write($id.'next_page_by',1);
        }
                   
    }
    function loaduserfollowedby($id=null){
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $next_page=$this->Cookie->read($id.'next_page_by');
        if($cookie&&$id&&$next_page){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $followed=$meshtiles->getUserFollowedBy($id,$next_page);
            $data=json_decode($followed,true);
            $this->set('data',$data);
            //debug($data);
            if(count($data['user'])==100){
                $this->Cookie->write($id.'next_page_by',$next_page+1);
                //debug($data);                
            }else{
                $this->Cookie->write($id.'next_page_by',0);
            }

        }
        $this->layout='';
        $this->render('loaduserfollows');
    }
    function search(){
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $option=$_POST['searchby'];
        $keyword=$_POST['search'];
        $this->set('option',$option);
        $this->set('session', $cookie);
        //debug($_REQUEST);
        if($cookie){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            if($option=='user'){
                $responser = $meshtiles->searchUser($keyword);
                $user=json_decode($responser,true);
                $this->set('user',$user);
                //debug($user);
            }
            if($option=='tag'){
                $this->redirect(array('controller'=>'meshtiles','action'=>'index',$keyword));
            } 
        }
        else $this->redirect(array('controller'=>'meshtiles','action'=>'index'));
    }
    function feed(){
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        if($cookie){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $response=$meshtiles->getUserFeed();
            $feed=json_decode($response,true);
            debug($feed);
        }
        else $this->redirect(array('controller'=>'meshtiles','action'=>'index'));        
    }
    function locationrecentmedia($id=null){
        $config=new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        if($cookie&&$id){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $response=$meshtiles->getLocationRecentMedia($id);
            $media=json_decode($response,true);
            //debug($media);
            $this->set('media',$media);
            $this->render('popular');            
        }
        else $this->redirect(array('controller'=>'meshtiles','action'=>'index'));        
    }
    function nearby($latitude=null, $longitude=null){
        $config=new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        if($cookie&&isset($latitude)&&isset($longitude)){
            $meshtiles=new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $response=$meshtiles->searchLocation($latitude,$longitude);
            $location=json_decode($response,true);
            $this->set('location',$location);
            //debug($location);
        }else
        $this->redirect(array('controller'=>'meshtiles','action'=>'index'));       
        
    }
    //View a photo
    function media($id = null){
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if ($cookie&&$id) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $response = $meshtiles->getMedia($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
            $this->render('photo');
        }
        else 
            $this->redirect(array('controller'=>'meshtiles','action'=>'index'));
    }
    function loaduser(){
        $config=new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->layout='';
        if ($cookie) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $response = $meshtiles->getUserStatus();
            $user_status=json_decode($response,true);
            //debug($user_status);
            $this->set('user',$user_status['status']);
        }
        else 
            $this->redirect(array('controller'=>'meshtiles','action'=>'index'));
    }
    //Xem vị trí 1 ảnh trên bản đồ
    function viewmap($id=null){
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        if ($cookie&&$id) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            $response = $meshtiles->getMedia($id);
            $media = json_decode($response, true);
            //debug($media);
            $this->set('media', $media);
        }
        else
            $this->redirect(array('controller'=>'meshtiles','action'=>'index'));
    }
    //xem list ảnh trên bản đồ
    function map($tags=null){
        $config = new meshconfig();
        if (!($tags)) {
            $tags = 'food';
        }   
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        $meshtiles = new Meshtiles($config->cfg);
        $meshtiles->setAccessToken($cookie);
        $next_page =1;
        $response = $meshtiles->getRecentTags($tags, $next_page);
        $media = json_decode($response, true);
        //debug($media);
        if(!$media['is_success'])
            $meshtiles->openAuthorizationUrl();
        else
            $this->set('media',$media);
    }
    function typeaheadsearch($mode=null){
        $this->layout='';      
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $you=$this->Cookie->read('UserInfo');
        $this->set('session', $cookie);
        if ($cookie) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            if($mode=='tag'){
                $response = $meshtiles->getFavouristTags();
                $tags = json_decode($response,true);
                $result = array();
                foreach($tags['tag'] as $tag){
                    array_push($result,array($tag['tag_name'],$tag['number_post']));
                }
                $encode_result=json_encode($result);
                $this->set('encoded_result',$encode_result);
            }
            if($mode=='user'){
                $result = array();
                $response=$meshtiles->getUserFollows($you['user_id'],1);
                $response2 = $meshtiles->getUserFollowedBy($you['user_id'],1);
                $users = json_decode($response,true);
                foreach($users['user'] as $user){
                    array_push($result,array($user['user_id'],$user['user_name'],urldecode($user['first_name'].$user['last_name']),$user['url_image']));
                }
                $users=json_decode($response2,true);
                foreach($users['user'] as $user){
                    array_push($result,array($user['user_id'],$user['user_name'],urldecode($user['first_name'].$user['last_name']),$user['url_image']));
                }                
                $encode_result=json_encode($result);
                $this->set('encoded_result',$encode_result);
            
                }                            
            }
        else 
            $this->redirect(array('controller'=>'meshtiles','action'=>'index'));
    }
    function searchsuggest($keyword=null,$mode=null){
        $this->layout='';       
        $config = new meshconfig();
        $cookie = $this->Cookie->read('MeshtilesAccessToken');
        $this->set('session', $cookie);
        if ($cookie) {
            $meshtiles = new Meshtiles($config->cfg);
            $meshtiles->setAccessToken($cookie);
            if($mode=='tag'&&$keyword){
                $response = $meshtiles->searchTags($keyword);
                $tags = json_decode($response,true);
                $result = array();
                if($tags['tag']){
                    foreach($tags['tag'] as $tag){
                        array_push($result,array($tag['tag_name'],$tag['number_post']));
                    }
                }
                else{
                    array_push($result,array('No result','0'));
                }
                $encode_result=json_encode($result);
                $this->set('encoded_result',$encode_result);
                //debug ($tags);
            }
            if($mode=='user'&&$keyword){
                $response2 = $meshtiles->searchUser($keyword);
                $users = json_decode($response2,true);
                $result = array();
                if($users['user']){
                    foreach($users['user'] as $user){
                        array_push($result,array($user['user_id'],$user['user_name'],urldecode($user['first_name'].$user['last_name']),$user['url_image']));
                    }
                }
                else{
                    array_push($result,array('#','No result','',''));
                }
                $encode_result=json_encode($result);
                $this->set('encoded_result',$encode_result);   
               
            }                            
        }
        else 
            $this->redirect(array('controller'=>'meshtiles','action'=>'index'));
    }
}





?>