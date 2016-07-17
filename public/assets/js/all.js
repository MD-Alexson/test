function urlRusLat(str) {
    str = str.toLowerCase();
    var cyr2latChars = new Array(
        ['а', 'a'], ['б', 'b'], ['в', 'v'], ['г', 'g'],
        ['д', 'd'],  ['е', 'e'], ['ё', 'yo'], ['ж', 'zh'], ['з', 'z'],
        ['и', 'i'], ['й', 'y'], ['к', 'k'], ['л', 'l'],
        ['м', 'm'],  ['н', 'n'], ['о', 'o'], ['п', 'p'],  ['р', 'r'],
        ['с', 's'], ['т', 't'], ['у', 'u'], ['ф', 'f'],
        ['х', 'h'],  ['ц', 'c'], ['ч', 'ch'],['ш', 'sh'], ['щ', 'shch'],
        ['ъ', ''],  ['ы', 'y'], ['ь', ''],  ['э', 'e'], ['ю', 'yu'], ['я', 'ya'],

        ['А', 'A'], ['Б', 'B'],  ['В', 'V'], ['Г', 'G'],
        ['Д', 'D'], ['Е', 'E'], ['Ё', 'YO'],  ['Ж', 'ZH'], ['З', 'Z'],
        ['И', 'I'], ['Й', 'Y'],  ['К', 'K'], ['Л', 'L'],
        ['М', 'M'], ['Н', 'N'], ['О', 'O'],  ['П', 'P'],  ['Р', 'R'],
        ['С', 'S'], ['Т', 'T'],  ['У', 'U'], ['Ф', 'F'],
        ['Х', 'H'], ['Ц', 'C'], ['Ч', 'CH'], ['Ш', 'SH'], ['Щ', 'SHCH'],
        ['Ъ', ''],  ['Ы', 'Y'],
        ['Ь', ''],
        ['Э', 'E'],
        ['Ю', 'YU'],
        ['Я', 'YA'],

        ['a', 'a'], ['b', 'b'], ['c', 'c'], ['d', 'd'], ['e', 'e'],
        ['f', 'f'], ['g', 'g'], ['h', 'h'], ['i', 'i'], ['j', 'j'],
        ['k', 'k'], ['l', 'l'], ['m', 'm'], ['n', 'n'], ['o', 'o'],
        ['p', 'p'], ['q', 'q'], ['r', 'r'], ['s', 's'], ['t', 't'],
        ['u', 'u'], ['v', 'v'], ['w', 'w'], ['x', 'x'], ['y', 'y'],
        ['z', 'z'],

        ['A', 'A'], ['B', 'B'], ['C', 'C'], ['D', 'D'],['E', 'E'],
        ['F', 'F'],['G', 'G'],['H', 'H'],['I', 'I'],['J', 'J'],['K', 'K'],
        ['L', 'L'], ['M', 'M'], ['N', 'N'], ['O', 'O'],['P', 'P'],
        ['Q', 'Q'],['R', 'R'],['S', 'S'],['T', 'T'],['U', 'U'],['V', 'V'],
        ['W', 'W'], ['X', 'X'], ['Y', 'Y'], ['Z', 'Z'],

        [" ", '-'],['0', '0'],['1', '1'],['2', '2'],['3', '3'],
        ['4', '4'],['5', '5'],['6', '6'],['7', '7'],['8', '8'],['9', '9'],
        ['-', '-']
    );
    var newStr = new String();
    for (var i = 0; i < str.length; i++) {
        var ch = str.charAt(i);
        var newCh = '';
        for (var j = 0; j < cyr2latChars.length; j++) {
            if (ch === cyr2latChars[j][0]) {
                newCh = cyr2latChars[j][1];
            }
        }
        newStr += newCh;
    }
    return newStr.replace(/[-]{2,}/gim, '-').replace(/\n/gim, '');
}
function phoneValidate(phone){
    var allowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '-', '(', ')', ' '];
    var arr = phone.split("");
    for(var i = 0; i < arr.length; i++){
        if(allowed.indexOf(arr[i]) === -1){
            arr.splice(i, 1);
        }
    }
    phone = arr.join("");
    return phone;
}

function notRead(not_id){
    var request = $.ajax({
        url: "/notifications/" + not_id + '/read',
        type: "GET"
    });
    request.success(function(data){
        var not = $(".not[data-id="+not_id+"]");
        not.addClass('read');
        var count = parseInt($("#not_counter").text());
        count -= 1;
        if(count <= 0){
            $("#not_counter").text("0").hide();
        } else {
            $("#not_counter").text(count);
        }
    });
    request.fail(function (jqXHR, textStatus) {
        
    });
}

function notDelete(not_id){
    var request = $.ajax({
        url: "/notifications/" + not_id + '/delete',
        type: "GET"
    });
    request.success(function(data){
        var not = $(".not[data-id="+not_id+"]");
        not.remove();
        var count = parseInt($('.not').length);
        if(!count){
            $("ul.content-top-hidden").html("<li class='default'><div class='content-hidden-title'>Уведомления</div><div class='content-hidden-text'>Уведомлений пока нет</div></li>");
        }
        $.fancybox.close();
    });
    request.fail(function (jqXHR, textStatus) {
        
    });
}

function toLocalDate(server){
    var utc = moment.utc(server).toDate();
    var localTime = moment(utc).format('DD.MM.YYYY');
    return localTime;
}
function toLocalDatetime(server){
    var utc = moment.utc(server).toDate();
    var localTime = moment(utc).format('DD.MM.YYYY HH:mm');
    return localTime;
}

$(document).ready(function () {

    if ($(".project-url-button").length) {
        new Clipboard('.project-url-button');
    }

    $("select[name=select_project]").on('selectmenuselect', function () {
        var project = $(this).val();

        var request = $.ajax({
            url: "/select/" + project,
            type: "GET"
        });
        request.success(function(){
            window.location.replace("/dashboard");
        });
        request.fail(function (jqXHR, textStatus) {
            alert("Ошибка: " + textStatus);
        });

    });
    
    $('.material-table input[name=check_all]').on('click', function(){
        if($(this).prop('checked')){
            $(".material-table input[name=check]").prop('checked', true);
            var ids = "";
            $(".material-table input[name=check]").each(function(){
                ids += ","+$(this).closest('tr').data('id');
            });
            $(".material-controls input[name=ids]").val(ids);
        } else {
            $(".material-table input[name=check]").prop('checked', false);
            $(".material-controls input[name=ids]").val("");
        }
        $.uniform.update();
    });
    
    $('.material-table input[name=check]').on('click', function(){
        var all = true;
        $('.material-table input[name=check]').each(function(){
            if(!$(this).prop('checked')){
                all = false;
            }
        });
        if(all){
            $(".material-table input[name=check_all]").prop('checked', true);
        } else {
            $(".material-table input[name=check_all]").prop('checked', false);
        }
        $.uniform.update();
        
        var id = $(this).closest("tr").data('id').toString();
        var ids = $("form.material-controls input[name=ids]").val();
        var arr = ids.split(",");
        
        if($(this).prop("checked")){
            if(arr.indexOf(id) === -1){
                arr.push(id);
            }
        } else {
            if(arr.indexOf(id) !== -1){
                arr.splice(arr.indexOf(id), 1);
            }
        }
        ids = arr.join(',');
        $("form.material-controls input[name=ids]").val(ids);
    });
    
    $("form.material-controls select[name=action]").on('selectmenuselect', function(){
        $("form.material-controls .material-control-item.batch, form.material-controls .material-control-item.batch2").hide();
        
        var batch = $(this).val();
        if($("form.material-controls .material-control-item.batch#batch_"+batch).length){
            $("form.material-controls .material-control-item.batch#batch_"+batch).show();
        }
    });
    
    $("form.material-controls .material-control-item.batch select").on('selectmenuselect', function(){
        $("form.material-controls .material-control-item.batch2").hide();
        var batch = $(this).attr('name');
        var batch2 = $(this).val();
        if($("form.material-controls .material-control-item.batch2#batch2_"+batch+"_"+batch2).length){
            $("form.material-controls .material-control-item.batch2#batch2_"+batch+"_"+batch2).show();
        }
    });

    // slider
    $('.carousel').carousel({
        carouselWidth: 930,
        carouselHeight: 330,
        vMargin: 0.12,
        directionNav: false,
        shadow: false,
        mouse: false,
        description: true,
        descriptionContainer: '.description',
        vAlign: 'top',
        buttonNav: 'bullets'
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 80) {
            $('.header-fix').addClass('fixed');
        } else {
            $('.header-fix').removeClass('fixed');
        }
    });

    // select styled
    $('.styled').selectmenu({
        position: {
            my: "left top", // default
            at: "left bottom", // default

            // "flip" will show the menu opposite side if there
            // is not enough available space

            collision: "flip"  // default is ""
        }
    });
    // Radio
    $(".radio, .check").uniform();
    // fancybox
    $('.fancybox').fancybox({
        closeBtn: false
    });
    $('.fancybox-big').fancybox({
        closeBtn: false,
        width: 960,
        height: 518,
        autoDimensions: false,
        autoSize:false,
        afterShow: function(){
            $("body").css({'overflow':'hidden'});
        },
        afterClose: function(){
            $("body").css({'overflow':'auto'});
        }
    });
    $('.popup-min button.close').on('click', function (e) {
        e.stopPropagation();
        $.fancybox.close();
    });
    // popover
    $('.tooltip-icon').popover({
        trigger: 'hover',
        placement: 'right',
        html: true,
        container: 'body'
    });
    $('.tooltip-icon-left').popover({
        trigger: 'hover',
        placement: 'left',
        html: true,
        container: 'body'
    });

    // scroll to selected
    $('.scroll').click(function (e) {
        e.preventDefault();
        var selected = $(this).attr('href').replace('/', '');
        $.scrollTo(selected, 1000, {offset: -100});
        return false;
    });

    // material-table checkbox change
    $(document).on('change', '.material-table input[type=checkbox]', function () {
        var $boxes = $('.material-table input[type=checkbox]:checked');
        if ($boxes.length > 0) {
            $('.material-controls').not('#filters').removeClass('disabled');
        } else {
            $('.material-controls').not('#filters').addClass('disabled');
        }
    });


    // cabinet top icons
    $(document).on('click', '.content-top-show', function (e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            $(this).next('.content-top-hidden').slideUp();
        } else {
            $(this).addClass('active')
            $(this).next('.content-top-hidden').slideDown();
        }
    });
    $(document).mouseup(function (e) {
        var container = $('.content-top-hidden, .content-login-hidden, .content-login-link, .content-top-show');
        var slide = $('.content-top-hidden, .content-login-hidden');
        if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            slide.slideUp();
            $('.content-login-link').removeClass('active');
            $('.content-top-show').removeClass('active');
        }
    });
    // cabinet login hidden
    $(document).on('click', '.content-login-link', function (e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            $(this).next('.content-login-hidden').slideUp();
        } else {
            $(this).addClass('active')
            $(this).next('.content-login-hidden').slideDown();
        }

    });
    // show hidden submenu
    $(document).on('click', '.side-parent', function (e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            $('.side-subnav').slideUp(100);
            $('.side-parent').removeClass('active');
        } else {
            $('.side-subnav').slideUp(100);
            $('.side-parent').removeClass('active');
            $(this).next('.side-subnav').slideDown(100);
            $(this).addClass('active');
        }
    });

    // show hidden submenu
    $(document).on('click', '.note-close', function (e) {
        e.preventDefault();
        $('.material-note').slideUp();
    });

    //Скрипт всплывающих окон  
    $('.modal').click(function (e) {
        e.preventDefault();
        $('#mask, #mask2, .window').hide();
        var id = $(this).attr('href');
        $('body').css('overflow', 'hidden');
        if(id === "#info1" || id === "#info2" || id === "#info3" || id === "#info4" || id === "#info5"){
            
        } else {
            var winH = $(window).height();
            var winW = $(window).width();
            $(id).css('top', winH / 2 - $(id).outerHeight() / 2);
            $(id).css('left', winW / 2 - $(id).outerWidth() / 2);
            $("#mask").fadeIn();
        }
        $(id).fadeIn(500);
        if(id === "#info1" || id === "#info2" || id === "#info3" || id === "#info4" || id === "#info5"){
            function temp(){
                $(id).find('img').addClass('showed');
            }
            setTimeout(temp, 250);
        }
    });
    $('.window .close').click(function (e) {
        e.preventDefault();
        $('body').css('overflow', 'auto');
        $('#mask, #mask2, .window').hide();
        $('.info_popup_screen img').removeClass("showed");
    });
    $('#mask, #mask2, .info_mask').click(function () {
        $('body').css('overflow', 'auto');
        $('#mask, #mask2, .window').hide();
        $('.info_popup_screen img').removeClass('showed');
    });
    //Скрипт всплывающих окон

    //Timezones

    $(".toLocalTime").each(function () {
        if($(this).hasClass("onlydate")){
            $(this).text(toLocalDate($(this).text()));
        } else {
            $(this).text(toLocalDatetime($(this).text()));
        }
    });
    
    $(".add-mat-optional-title").on('click', function(){
        var title = $(this);
        if($("#optional").hasClass('active')){
            title.removeClass('active');
            $("#optional").removeClass('active').slideUp(200);
        } else {
            title.addClass('active');
            $("#optional").addClass('active').slideDown(200);
        }
    });
    
    $(".not").on('click', function(){
        var id = $(this).data('id');
        var title = $(this).children('.content-hidden-title').text();
        var text = $(this).children('.content-hidden-text').html();
        
        notRead(id);

        $("#popup_not").find('.popup-min-title').text(title);
        $("#popup_not").find('.popup-min-text').html(text);
        $("#popup_not .popup-min-bottom").find('a').attr('href', "javascript: notDelete("+id+")");

        $.fancybox("#popup_not");
    });
    
    $("input[name=phone]").keyup(function(){
        var old = $(this).val();
        new_val = phoneValidate(old);
        if(old !== new_val){
            $(this).val(new_val);
        }
    });
    
    $(".my_tabs_controll a").on('click', function(e){
        e.preventDefault();
        var controll = $(this).parent();
        var tabs = $(this).closest('.my_tabs');
        controll.find('a').removeClass('disabled').removeClass('green-button').addClass('white-button');
        $(this).addClass('disabled').addClass('green-button').removeClass('white-button');
        var id = $(this).attr('href');
        tabs.children('.tab').hide();
        $(id).show();
    });
    
    $(".add-material label").on('click', function(e){
        var target = e.target.nodeName;
        if(target === "SPAN" || target === "Span" || target === "span"){
            e.preventDefault();
        }
    });
    
    $(".add-material form").on('submit', function(){
        $("#loading_screen").fadeIn(200);
        $('body').css('overflow', 'hidden');
    });
});
$(window).load(function () {
    // cabinet main page vertical align
    var calc_height = ($(window).height() - 131 - $('.add-project').height()) / 2;
    $('.add-project').css('paddingTop', calc_height + 'px');
    $("form.material-controls .material-control-item.batch, form.material-controls .material-control-item.batch2").hide();
    $("#batch_"+$("form.material-controls select[name=action]").val()).show();
    $("#optional select, .tab select").selectmenu({ width: 250 });
});