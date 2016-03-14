    </div>
</div>

<footer>
    <div class="container">
        <p>
            &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>.
            <?php _e('All Rights Reserved. 版权所有.'); ?>
        </p>
    </div>
</footer>
<!-- 多说公共JS代码 start (一个网页只需插入一次) -->
<script type="text/javascript">
var duoshuoQuery = {short_name:"hongweipeng"};
    (function() {
        var ds = document.createElement('script');
        ds.type = 'text/javascript';ds.async = true;
        ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js';
        ds.charset = 'UTF-8';
        (document.getElementsByTagName('head')[0] 
         || document.getElementsByTagName('body')[0]).appendChild(ds);
    })();
</script>
<!-- 多说公共JS代码 end -->
<?php $this->footer(); ?>
<script src="<?php $this->options->adminUrl('js/jquery.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('js/script.js'); ?>"></script>
    </body>
</html>
