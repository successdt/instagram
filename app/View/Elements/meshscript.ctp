<script>
$(function(){
      $('#content').masonry({
        // options
            itemSelector : '.display-block',
            columnWidth : 252
      });
    });                
</script>
<script>
    $(document).ready(function(){
        $('#show-hide-sidebar').toggle(function(){
            $('#sidebar').hide();
            $('#top-nav').css('margin-left','0px');
            $('#right-nav').css('right','30px');
            $('#content').masonry('reload');
        },function(){
            $('#sidebar').show();
            $('#top-nav').css('margin-left','200px');
            $('#right-nav').css('right','230px');
            $('#content').masonry('reload');
        });        
    });
</script>
<!-- timeago -->
<script>
    function prettyTime(){
        jQuery(document).ready(function() {
          jQuery("abbr.timeago").timeago();
        });    
    }
    prettyTime();
    setInterval(function(){prettyTime()},10000);
</script>

<!-- location -->
<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        function getLocation()
        {
            if (navigator.geolocation)
            {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        
        }
        function showPosition(position)
        {
          window.location.href="<?php echo $this->webroot?>meshtiles/nearby/"+position.coords.latitude+'/'+ position.coords.longitude;	
        }
        $('#nearby').click(function(){
            getLocation();
        });        
    });
</script>
<!-- facebook -->
<script>
function facebook(){
    $('.facebook, .main-facebook').click(function(){
        var id=$(this).attr('data-media-id');
        var url='http://ltt.web44.net/meshtiles/media/'+id;
        var title='Myinsta';
        window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(url)+'&t='+encodeURIComponent(title),'sharer','toolbar=0,status=0,width=626,height=436');
    });
}
</script>
<!-- twitter -->
<script>
    function twitter(){
        $('.twitter').click(function(){
            var id=$(this).attr('data-media-id');
            window.open('https://twitter.com/share?url=http%3A%2F%2Fltt.web44.net%2Fmeshtiles%2Fmedia%2F'+id,'media','toolbar=0,status=0,width=626,height=436');
        });        
    }
</script>
<!-- load sidebar -->
<script type="text/javascript">
function loaduser(){
    var root=<?php echo $this->webroot; ?>;
    $.ajax({
        url: root+"meshtiles/loaduser",
        success:function(html)
        {
            $('.sidebar').html(html);
        }
    });
}
$(document).ready(function(){
    loaduser();
    setInterval(function(){loaduser()},60000);
});
</script>