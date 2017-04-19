document.querySelector(".back a img").onclick=function(event){
    event.preventDefault();
    window.history.back();
}
var main_title=document.getElementsByClassName("main_title")[0];
main_title.style.transform="translateX(-"+parseInt(window.getComputedStyle(main_title).width)/2+"px)";