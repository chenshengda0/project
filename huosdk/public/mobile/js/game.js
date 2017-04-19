var bgColor=['D9FAEF','FAD0F6','F9E4B9','DDDEFA','F9D7CD','CEEFFC'];
$(".hot_search>.plane>.hotList>li").each(function(i){
    var x=i+1;
    $(this).css("background-color","#"+bgColor[i]);
    if(x%3==0){
        $(this).css("margin-right","0px");
    }
});
$(".showLineHeight").each(function(){
    $(this).css("line-height",$(this).css("height"));
});