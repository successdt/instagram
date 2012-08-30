<?php if(isset($location)){ ?>
    <?php foreach($location['data'] as $data) :?>
        <div class="display-block">
            <div class="search-result">
                <span><?php echo $data['name'] ?></span>
                <div class="search-count"></div>            
            </div>
            <button class="follow white" onclick="window.location='<?php echo $this->webroot?>instagrams/locationrecentmedia/<?php echo $data['id'] ?>'">view</button>
        </div><!-- /display-block-->
    <?php endforeach ?>
<?php } ?>