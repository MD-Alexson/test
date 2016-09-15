$(document).ready(function(){
    $(".toLocalTime").each(function () {
        var utc = moment.utc($(this).text()).toDate();
        if($(this).hasClass("onlydate")){
            var localTime = moment(utc).format('DD.MM.YYYY');
        } else {
            var localTime = moment(utc).format('DD.MM.YYYY HH:mm');
        }
        $(this).text(localTime);
    });
    var count = 0;
    $("#content iframe").each(function(){
        if(!$(this).attr('allowfullscreen')){
            $(this).attr('allowfullscreen', "");
        }
        if(!$(this).attr('mozallowfullscreen')){
            $(this).attr('mozallowfullscreen', "");
        }
        if(!$(this).attr('webkitallowfullscreen')){
            $(this).attr('webkitallowfullscreen', "");
        }
        $(this).before("<div class='video_wrap' id='video_wrap"+count+"'></div>");
        var iframe = $(this).remove();
        $("#video_wrap"+count).html(iframe);
    });
});
function updateCards(){
    $(".row.cards .card").css({'height':'auto'});
    $(".row.cards").each(function(){
        var max = 0;
        $(this).find(".card").each(function(){
            var temp = $(this).outerHeight();
            if(temp > max){
                max = temp;
            }
        });
        $(this).find(".card").css({'height':max+'px'});
    });
}
$(window).load(function(){
    updateCards();
});
$(window).resize(function(){
    updateCards();
});