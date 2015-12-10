//div 添加resize
(function($,h,c){var a=$([]),e=$.resize=$.extend($.resize,{}),i,k="setTimeout",j="resize",d=j+"-special-event",b="delay",f="throttleWindow";e[b]=250;e[f]=true;$.event.special[j]={setup:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.add(l);$.data(this,d,{w:l.width(),h:l.height()});if(a.length===1){g()}},teardown:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.not(l);l.removeData(d);if(!a.length){clearTimeout(i)}},add:function(l){if(!e[f]&&this[k]){return false}var n;function m(s,o,p){var q=$(this),r=$.data(this,d);r.w=o!==c?o:q.width();r.h=p!==c?p:q.height();n.apply(this,arguments)}if($.isFunction(l)){n=l;return m}else{n=l.handler;l.handler=m}}};function g(){i=h[k](function(){a.each(function(){var n=$(this),m=n.width(),l=n.height(),o=$.data(this,d);if(m!==o.w||l!==o.h){n.trigger(j,[o.w=m,o.h=l])}});g()},e[b])}})(jQuery,this);

function prettify() {
    $("pre").addClass("prettyprint");
    prettyPrint();
}

//滚动条添加监听 同步两边的滚动条
$(document).ready(function(){
    var syn = true;//防止两个滚动条相互监听而引起无穷无尽的死锁问题
    var is_syn_time = true;//是否实时
    function syn_scroll(source, target) {
        source.scroll(function(){
            if(!syn) return;
            syn = false;
            var source_scroll_top = source.scrollTop();
            var source_scroll_height = source.get(0).scrollHeight;
            var source_offset_height = source.get(0).offsetHeight;

            var target_offset_height = target.get(0).offsetHeight;
            var target_scroll_height = target.get(0).scrollHeight;

            target.scrollTop(source_scroll_top * (target_scroll_height - target_offset_height) / (source_scroll_height - source_offset_height));
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

    setInterval(function() {
        if(!is_syn_time)
            return;
        prettify();
    }, 500);//代码高亮

    //添加编辑 实时 预览工具栏
    var div = $('<div class="wmd-editlrtab"></div>');
    var edit_mode = $('<a href="#">编辑</a>');
    var both_mode = $('<a href="#">实时</a>');
    var show_mode = $('<a href="#">浏览</a>');
    div.append(edit_mode).append(both_mode).append(show_mode);
    $('#wmd-button-bar').prepend(div);
    function edit_change(obj, left, right, mode) {
        obj.click(function() {  
            if(obj.hasClass("active")) return;

            is_syn_time = mode;

            $('.wmd-editlrtab a').removeClass("active");
            if(left < 15) {
                textArea.hide();
            } else {
                textArea.show();
            }
            if(right < 15) {
                wmd_preview.hide();
            }else {
                wmd_preview.show();
            }

            textArea.animate({width:left + '%'}, 200);
            if(typeof right == 'string') {
                wmd_preview.animate({width:($('#wmd-editarea').width()/ $(window).width()) * 100 + '%'}, 200);
            } else {
                wmd_preview.animate({width:right + '%'}, 200);
            }
            
            obj.addClass('active');
        });
    }
    edit_change(edit_mode, 100, 10,false);
    edit_change(both_mode, 47, 47, true);
    edit_change(show_mode, 10, 'full',false);
    both_mode.addClass('active');

});

