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
    var $layout = 'instagram';
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
        $this->set('session', $session);
        //debug($session);
        if ($session) {
            //$instagram = new Instagram($config->cfg);
            //$instagram->setAccessToken($session);
            //$popular = $instagram->getRecentTags($tags);
            //$response = json_decode($popular, true);
            $this->Session->write('next_max_tag_id', '');
            //$this->set('response',$response);

        }
    }

    function lazyload($tags = null, $min_id = null, $max_id = null)
    {
        $config = new config();
        if (!($tags)) {
            $tags = 'tech';
        }
        $session = $this->Session->read('InstagramAccessToken');
        $this->set('session', $session);
        //debug($session);
        if ($session) {
            $instagram = new Instagram($config->cfg);
            $instagram->setAccessToken($session);
            $max_id = $this->Session->read('next_max_tag_id');
            $popular = $instagram->getRecentTags($tags, $max_id);
            $response = json_decode($popular, true);
            $this->Session->write('next_max_tag_id', $response['pagination']['next_max_tag_id']);
            $this->set('response', $response);

        }
        $this->layout = '';

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
            $this->set('media', $media);
        }
        $this->layout = '';
    }
    function success()
    {
        $config = new config();
        $instagram = new Instagram($config->cfg);
        $accessToken = $instagram->getAccessToken();
        $this->Session->write('InstagramAccessToken', $accessToken);
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


}





?>