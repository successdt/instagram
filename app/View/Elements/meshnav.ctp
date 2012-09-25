<div class="navbar-inner">
    <div class="container">
        <ul class="nav">
            <li class="active">
                <a class="brand" href="<?php echo $this->webroot?>meshtiles/index">Meshtiles</a>
            </li>
            <li class="dropdown">  
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-th icon-black"></i> 
                    Categories  
                    <b class="caret"></b>  
                </a>  
                <ul class="dropdown-menu">  
                    <li><?php echo $this->Html->link('Featured',array('controller'=>'meshtiles','action'=>'index','featured')) ?></li>
                    <li><?php echo $this->Html->link('Brands',array('controller'=>'meshtiles','action'=>'index','brands')) ?></li>
                    <li><?php echo $this->Html->link('Celebrities',array('controller'=>'meshtiles','action'=>'index','celebrities')) ?></li>
                    <li><?php echo $this->Html->link('Fashion',array('controller'=>'meshtiles','action'=>'index','fashion')) ?></li>
                    <li><?php echo $this->Html->link('Food',array('controller'=>'meshtiles','action'=>'index','food')) ?></li>
                    <li><?php echo $this->Html->link('Travel',array('controller'=>'meshtiles','action'=>'index','travel')) ?></li>
                    <li><?php echo $this->Html->link('Animals',array('controller'=>'meshtiles','action'=>'index','animals')) ?></li>
                    <li><?php echo $this->Html->link('Sports',array('controller'=>'meshtiles','action'=>'index','sports')) ?></li>
                    <li><?php echo $this->Html->link('Music',array('controller'=>'meshtiles','action'=>'index','music')) ?></li>
                    <li><?php echo $this->Html->link('Architecture',array('controller'=>'meshtiles','action'=>'index','architecture')) ?></li>
                    <li><?php echo $this->Html->link('Arts',array('controller'=>'meshtiles','action'=>'index','arts')) ?></li>
                    <li><?php echo $this->Html->link('Tech',array('controller'=>'meshtiles','action'=>'index','tech')) ?></li> 
                </ul>  
            </li>
            <li>
                <a class="" href="javascript:void(0)" id="nearby">
                    <i class="icon-map-marker icon-black"></i>
                    Nearby
                </a>
            </li>
            <li>
                <a class="" href="<?php echo $this->webroot ?>meshtiles/popular">
                    <i class="icon-user icon-black"></i>
                    Popular
                </a>
            </li>
            <li>
                <a href="<?php echo $this->webroot ?>meshtiles/viewprofile/<?php if (isset($userinfo['user_id']))echo $userinfo['user_id'] ?>">
                    <i class="icon-picture icon-black"></i>
                    My photos
                </a>
            </li>
            <li class="dropdown">  
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-search icon-black"></i> 
                    Search 
                    <b class="caret"></b>  
                </a>  
                <ul class="dropdown-menu">  
                    <li>
                        <form class="navbar-search pull-right input-append" method="post" action="<?php echo $this->webroot?>meshtiles/search">
                             <div class="input-append" style="padding: 10px;">
                                <input class="span2" style="top: 6px;" name="search" placeholder="Search" id="inputString" size="16" type="text">
                                <button type="submit" class="btn">Go!</button>
                                <div id="suggestions"></div>
                                <label class="radio" style="margin-top: 5px;">
                                    <input type="radio" name="searchby" value="tag"  checked="checked"/>
                                    Tag
                                </label>
                                <label class="radio">
                                    <input type="radio" name="searchby" value="user" /> 
                                    Username
                                </label>     
                            </div>
                        </form>  
                    </li>
                </ul>  
            </li> 
        </ul>
        <ul class="nav pull-right">
            <li class="dropdown">
                <li class="divider-vertical"/>
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                    <strong><?php echo $userinfo['user_name'] ?></strong> 
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <?php if(isset($userinfo['user_name'])){ ?>
                        <li><?php echo $this->Html->link('Account Settings',array('controller'=>'meshtile','action'=>'#')) ?></li>
                        <li class="divider"></li>
                        <li><?php echo $this->Html->link('Help',array('controller'=>'meshtiles','action'=>'help')) ?></li>
                        <li><?php echo $this->Html->link('About us',array('controller'=>'meshtiles','action'=>'about')) ?></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $this->webroot?>meshtiles/logout">Log Out</a></li>                            
                    <?php }else {?>
                        <li><?php echo $this->Html->link('Help',array('controller'=>'meshtiles','action'=>'help')) ?></li>
                        <li><?php echo $this->Html->link('About us',array('controller'=>'meshtiles','action'=>'about')) ?></li>
                    <?php }?>
                </ul>
            </li>
            <li>
                <?php 
                    if (isset($userinfo['data']['profile_picture']))
                        echo $this->Html->image($userinfo['data']['profile_picture'], array(
                            'width' =>'30',
                            'height' => '30',
                            'class'=>'profile-picture')) 
                ?>
            </li>
        </ul>
    </div><!-- /container -->
</div><!--/navbar-inner  -->