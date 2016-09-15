$(document).ready(function () {
    $(".ckedit").ckeditor({
        language: 'ru',
        extraPlugins: 'undo,base64image,oembed,justify,colorbutton,font',
        removePlugins: 'image',
        extraAllowedContent: 'style; *{*}'
    });
});