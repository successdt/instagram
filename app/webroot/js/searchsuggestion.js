var Option=new Object();
Option.lang=new Object;
/*
require src='http://www.google.com/jsapi'
example:
    var root=webroot;
    Option.searchUrl=root+"meshtiles/searchsuggest/";
    Option.typeAHeadTagUrl=root+"meshtiles/typeaheadsearch/tag";
    Option.typeAHeadUserUrl=root+"meshtiles/typeaheadsearch/user";
    Option.viewTagUrl=root+'meshtiles/index/';
    Option.viewUserByIdUrl=root+'meshtiles/viewprofile/';
    Option.min_string=3;
    Option.inputName='inputString';
    Option.modeName='searchby';
    Option.suggestDiv='suggestions';
    Option.delay=600;
    Option.maxItem=10;
*/
Option.searchUrl='';
Option.typeAHeadTagUrl='';
Option.typeAHeadUserUrl='';
Option.viewTagUrl='';
Option.viewUserByIdUrl='';
Option.min_string=0;
Option.inputName='';
Option.modeName='';
Option.suggestDiv='';
Option.delay=0;
Option.maxItem=10;
Option.lang.user='User';
Option.lang.following='Following';
Option.lang.tags='Tags';
Option.lang.favouritetags='Favourite Tags';
Option.lang.noresult='No result';
Option.start=function(){
    var ajax_lock=false;
    var request;
    var user_loaded=[];
    var tag_loaded=[];
    google.setOnLoadCallback(function()
    {
      // Safely inject CSS3 and give the search results a shadow
      var cssObj = { 'box-shadow' : '#888 5px 10px 10px', // Added when CSS3 is standard
        '-webkit-box-shadow' : '#888 5px 10px 10px', // Safari
        '-moz-box-shadow' : '#888 5px 10px 10px'}; // Firefox 3.5+
      $("#"+Option.suggestDiv).css(cssObj);
      
      // Fade out the suggestions box when not active
       $("input").blur(function(){
        $("#"+Option.suggestDiv).fadeOut();
       });
    });
    function typeahead(){
        var mode=$('input:[name="'+Option.modeName+'"]:checked').val();
        if((mode=='tag')&&(tag_loaded.length==0)){
            $.ajax({
                url:Option.typeAHeadTagUrl,
                dataType:'json',
                success:function(data){
                    var count=0;
                    $.each(data,function(key,value){
                        tag_loaded=data;
                    });
               } 
            });
        }
        if((mode=='user')&&(user_loaded.length==0)){
            $.ajax({
                url:Option.typeAHeadUserUrl,
                dataType:'json',
                success:function(data){
                    var count=0;
                    $.each(data,function(key,value){
                        user_loaded=data;
                    });
               } 
            });
        } 
    }
    function suggestion(){
        var inputString=$("#"+Option.inputName).val();
        var mode=$('input:[name="'+Option.modeName+'"]:checked').val();       
        if(inputString.length == 0) {
        $("#"+Option.suggestDiv).fadeOut(); // Hide the suggestions box
        } 
        else if((inputString.length>=Option.min_string)) {
            if(request)
                request.abort();
            request=$.ajax({
              url: Option.searchUrl+inputString+"/"+mode,
              dataType: 'json',
              success: function(data){
                var item = [];
                
                $("#"+Option.suggestDiv).fadeIn();
                if(mode=='tag'){
                    var count=0;
                    item.push('<span class="tags">'+Option.lang.favouritetags+'</span>');
                    $.each(tag_loaded,function(key,value){
                        var reg = new RegExp(inputString,"i");
                        var isFound=value[0].search(reg);
                        if(isFound!=-1){
                            count ++;
                            item.push('<a href="'+Option.viewTagUrl+value[0]+'">');
                            item.push('<span class="searchheading">'+value[0]+'</span>');
                            item.push('<span>'+value[1]+' Photos</span>');
                            item.push('</a>');
                            if(count>Option.maxItem)
                                return false;  
                        }
                    });
                    if(item.push.length<2)
                        item.push('<a href=""><span class="searchheading">'+Option.lang.noresult+'</span></a>');                
                    item.push('<span class="tags">'+Option.lang.tags+'</span>');
                    $.each(data,function(key,value){
                        count ++;
                        item.push('<a href="'+Option.viewTagUrl+value[0]+'">');
                        item.push('<span class="searchheading">'+value[0]+'</span>');
                        item.push('<span>'+value[1]+' Photos</span>');
                        item.push('</a>');
                        if(count>Option.maxItem)
                            return false;
                    });
                }else{
                    var count=0;
                    item.push('<span class="tags">'+Option.lang.following+'</span>');
                    $.each(user_loaded,function(key,value){
                        var reg = new RegExp(inputString,"i");
                        var isFound=value[1].search(reg);
                        if(isFound!=-1){
                            count ++;
                            item.push('<a href="'+Option.viewUserByIdUrl+value[0]+'">');
                            item.push('<img alt="" width="30" height="30" src="'+value[3]+'"/>');
                            item.push('<span class="searchheading">'+value[1]+'</span>');
                            item.push('<span>'+value[2]+'</span>');
                            item.push('</a>');
                            if(count>Option.maxItem/2)
                                return false;  
                        }
                    });
                    if(item.push.length<2)
                        item.push('<a href=""><span class="searchheading">'+Option.lang.noresult+'</span></a>'); 
                    item.push('<span class="tags">'+Option.lang.user+'</span>');
                    $.each(data,function(key,value){
                        count ++;
                        item.push('<a href="'+Option.viewUserByIdUrl+value[0]+'">');
                        if(value[3])
                            item.push('<img alt="" width="30" height="30" src="'+value[3]+'"/>');
                        item.push('<span class="searchheading">'+value[1]+'</span>');
                        item.push('<span>'+value[2]+'</span>');
                        item.push('</a>');
                        if(count>Option.maxItem)
                            return false;
                    });
                }
                
                $("#"+Option.suggestDiv).html('<p id="searchresults">'+item.join('')+'</p>');
              }
            });   
        }
        else{
            $("#"+Option.suggestDiv).fadeIn();
            if(mode=='user'){
                    var count=1;
                    var item=[];
                    item.push('<span class="tags">Following</span>');
                    $.each(user_loaded,function(key,value){
                        var reg = new RegExp(inputString,"i");
                        var isFound=value[1].search(reg);
                        if(isFound!=-1){
                            count ++;
                            item.push('<a href="'+Option.viewUserByIdUrl+value[0]+'">');
                            item.push('<img alt="" width="30" height="30" src="'+value[3]+'"/>');
                            item.push('<span class="searchheading">'+value[1]+'</span>');
                            item.push('<span>'+value[2]+'</span>');
                            item.push('</a>');
                            if(count>Option.maxItem)
                                return false;  
                        }
                    });
                    $("#"+Option.suggestDiv).html('<p id="searchresults">'+item.join('')+'</p>');
            }
            else{
                    var count=1;
                    var item=[];
                    item.push('<span class="tags">Favourite Tags</span>');
                    $.each(tag_loaded,function(key,value){
                        var reg = new RegExp(inputString,"i");
                        var isFound=value[0].search(reg);
                        if(isFound!=-1){
                            count ++;
                            item.push('<a href="'+Option.viewTagUrl+value[0]+'">');
                            item.push('<span class="searchheading">'+value[0]+'</span>');
                            item.push('<span>'+value[1]+' Photos</span>');
                            item.push('</a>');
                            if(count>Option.maxItem)
                                return false;  
                        }
                    });
                    $("#"+Option.suggestDiv).html('<p id="searchresults">'+item.join('')+'</p>');
            }
            
        }
    }
    $("#"+Option.inputName).keyup(function(){
        setTimeout(function(){
            suggestion();
        },Option.delay);
    });
    $("#"+Option.inputName).click(function(){
        typeahead();
    });
    $('input:[name="'+Option.modeName+'"]').click(function(){
        suggestion();
    });
}