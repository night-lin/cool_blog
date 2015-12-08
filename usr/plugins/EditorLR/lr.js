
function prettify() {
    $("pre").addClass("prettyprint");
    prettyPrint();
}

$(function() {
    /*Show wmd-preview DOM*/
    $('.wmd-edittab').remove();
    $('#wmd-preview').removeClass('wmd-hidetab');
    setInterval("$('#wmd-preview').css('height', (parseInt($('#text').height()) - 5)+'px');", 500);
    setInterval("prettify()", 500);
});

//滚动条添加监听 同步两边的滚动条
$(document).ready(function(){
    var syn = true;//防止两个滚动条相互监听而引起无穷无尽的死锁问题
    function syn_scroll(source, target) {
        //console.log(target);
        source.scroll(function(){
            console.log(syn);
            if(!syn) return;
            syn = false;
            setTimeout(function(){
                var source_scroll_height = source.scrollTop();
                var source_area_height = source.height();
                var bl = source_scroll_height / source_area_height;//滚动条占的百分比

                var target_area_height = target.height();
                target.scrollTop(Math.ceil(bl * target_area_height));
                //target.animate({scrollTop:Math.ceil(bl * target_area_height)}, 1000);
                setTimeout(function(){
                    syn = true;
                },50);
            }, 20);
        });
    }
    var textArea = $('#text');
    var wmd_preview = $('#wmd-preview');
    syn_scroll(textArea, wmd_preview);
    syn_scroll(wmd_preview, textArea);
});

