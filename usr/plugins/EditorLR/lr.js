//div 添加resize
(function($,h,c){var a=$([]),e=$.resize=$.extend($.resize,{}),i,k="setTimeout",j="resize",d=j+"-special-event",b="delay",f="throttleWindow";e[b]=250;e[f]=true;$.event.special[j]={setup:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.add(l);$.data(this,d,{w:l.width(),h:l.height()});if(a.length===1){g()}},teardown:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.not(l);l.removeData(d);if(!a.length){clearTimeout(i)}},add:function(l){if(!e[f]&&this[k]){return false}var n;function m(s,o,p){var q=$(this),r=$.data(this,d);r.w=o!==c?o:q.width();r.h=p!==c?p:q.height();n.apply(this,arguments)}if($.isFunction(l)){n=l;return m}else{n=l.handler;l.handler=m}}};function g(){i=h[k](function(){a.each(function(){var n=$(this),m=n.width(),l=n.height(),o=$.data(this,d);if(m!==o.w||l!==o.h){n.trigger(j,[o.w=m,o.h=l])}});g()},e[b])}})(jQuery,this);

function prettify() {
    $("pre").addClass("prettyprint");
    prettyPrint();
}

// $(function() {
//     /*Show wmd-preview DOM*/
//     $('.wmd-edittab').remove();
//     $('#wmd-preview').removeClass('wmd-hidetab');
//     // setInterval("$('#wmd-preview').css('height', (parseInt($('#text').height()) - 5)+'px');", 500);
    
// });

//滚动条添加监听 同步两边的滚动条
$(document).ready(function(){
    var syn = true;//防止两个滚动条相互监听而引起无穷无尽的死锁问题
    function syn_scroll(source, target) {
        source.scroll(function(){
            if(!syn) return;
            syn = false;
            var source_scroll_height = source.scrollTop();
            var source_area_height = source.height();
            var bl = source_scroll_height / source_area_height;//滚动条占的百分比
            var target_area_height = target.height();
            target.scrollTop(Math.ceil(bl * target_area_height));
            setTimeout(function(){
                syn = true;
            },20);
        });
    }
    var textArea = $('#text');
    var wmd_preview = $('#wmd-preview');
    syn_scroll(textArea, wmd_preview);
    syn_scroll(wmd_preview, textArea);

    $('.wmd-edittab').remove();
    wmd_preview.removeClass('wmd-hidetab');

    textArea.resize(function() {
        wmd_preview.outerHeight(textArea.outerHeight());
    });
    textArea.resize();//update resize

    setInterval("prettify()", 500);//代码高亮
});

