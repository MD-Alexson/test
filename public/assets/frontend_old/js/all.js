function footer(){
//    var height = $("body > header").outerHeight() + $("body > .container.menu").outerHeight() + $("body > .container").not('.menu').outerHeight() + $("body > footer").outerHeight();
//    if(height < $(window).height()){
//        $("body > footer").addClass('fixed');
//    } else {
//        $("body > footer").removeClass('fixed');
//    }
}

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
});