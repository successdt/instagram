<div id="left-nav">
    <form method="post" action="<?php echo $this->webroot?>instagrams/search">
    <div class="start gray" id="show-hide-sidebar">show/hide</div>
        <input type="text" class="middle" name="search" id="search" />
        <!--<button class="end gray" id="search-button">search</button>-->
        <input type="submit" value="search" class="end gray inline" />
        <div class="searchby inline">
            <input type="radio" name="searchby" value="tag"  checked="checked"/>Tags
            <input type="radio" name="searchby" value="username" />Username    
        </div>        
    </form>
</div>
<div id="right-nav">
    <div class="inline">
        <button class="start gray" onclick="window.location='<?php echo
            $this->webroot ?>instagrams'">Home</button>
        <button class="middle gray nearby" id="nearby">Nearby</button>
        <button class="middle gray" onclick="window.location='<?php echo $this->webroot ?>instagrams/popular'">Popular</button>
        <button class="middle gray" onclick="window.location='<?php echo
            $this->webroot ?>instagrams/viewprofile/<?php if ($userinfo['data']['profile_picture'])
            echo $userinfo['data']['id'] ?>'">My photos</button>
        <button class="middle gray" onclick="window.location='<?php echo $this->
            webroot ?>instagrams/favorites'"> My favorites</button>
        <button class="end gray" onclick="window.location='<?php echo $this->webroot?>instagrams/logout'">Logout</button>           
    </div>
    <div id="profile-picture" class="inline" onclick="window.location='<?php echo
        $this->webroot ?>instagrams/viewprofile/<?php echo
            $userinfo['data']['id'] ?>'">
        <?php if ($userinfo['data']['profile_picture'])
                echo $this->Html->image($userinfo['data']['profile_picture'], array('width' =>
                    '30', 'height' => '30')) ?>
    </div><!-- /profile-picture -->
</div><!-- /right-nav -->