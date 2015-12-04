(function($){
    var parser = new Parser();
    var content = $('.article_content').each(function(_,value){
        var obj = $(this);
        var text = obj.text().trim();
        text = parser.makeHtml(text);
        obj.html(text);
        obj.fadeIn();
    });
})(jQuery);

hljs.initHighlightingOnLoad();