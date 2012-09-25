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
<?php /* ?>
<!-- location -->
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
<?php */?>
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


<!-- search -->
<script type="text/javascript">
    google.setOnLoadCallback(function()
    {
    	// Safely inject CSS3 and give the search results a shadow
    	var cssObj = { 'box-shadow' : '#888 5px 10px 10px', // Added when CSS3 is standard
    		'-webkit-box-shadow' : '#888 5px 10px 10px', // Safari
    		'-moz-box-shadow' : '#888 5px 10px 10px'}; // Firefox 3.5+
    	$("#suggestions").css(cssObj);
    	
    	// Fade out the suggestions box when not active
    	 $("input").blur(function(){
    	 	$('#suggestions').fadeOut();
    	 });
    });
    var root=<?php echo $this->webroot; ?>;
    $(document).ready(function(){
    $('#inputString').keyup(function(){
        var inputString=$(this).val();
        var mode=$('input:[name="searchby"]:checked').val();
        console.log(mode);        
   	    if(inputString.length == 0) {
	      $('#suggestions').fadeOut(); // Hide the suggestions box
       } else {
            $.ajax({
              url: root+"meshtiles/searchsuggest/"+inputString+"/"+mode,
              dataType: 'json',
              success: function(data){
                var item = [];
                var count=0;
                $('#suggestions').fadeIn();
                if(mode=='tag'){
                    $.each(data,function(key,value){
                        count ++;
                        item.push('<a href="<?php echo $this->webroot?>meshtiles/index/'+value[0]+'">');
                        item.push('<span class="searchheading">'+value[0]+'</span>');
                        item.push('<span>'+value[1]+' Posts</span>');
                        item.push('</a>');
                        if(count>10)
                            return false;
                    });
                }else{
                    $.each(data,function(key,value){
                        count ++;
                        item.push('<a href="<?php echo $this->webroot?>meshtiles/viewprofile/'+value[0]+'">');
                        item.push('<img alt="" width="30" height="30" src="'+value[3]+'"/>');
                        item.push('<span class="searchheading">'+value[1]+'</span>');
                        item.push('<span>'+value[2]+'</span>');
                        item.push('</a>');
                        if(count>10)
                            return false;
                    });
                    
                }
                $('#suggestions').html('<p id="searchresults">'+item.join('')+'</p>');
              }
            });
       }
    });
});
    
</script>