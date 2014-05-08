$(document).ready(function(){
    $("#navbar li a.mainnav").hover(function(){
        $("#navbar li ul").hide();
        $(this).parent().find("ul").css("display","inline");
    },function(){
        //$("#navbar li ul").hide();
    });
    $("#navbar li ul").hover(function(){},function(){
        $("#navbar li ul").hide(500);
    });
});