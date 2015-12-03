<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

        </div><!-- end .row -->
    </div>
</div><!-- end #body -->

<footer id="footer" role="contentinfo">
    &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>.
    <?php _e('由 <a href="http://www.typecho.org">Typecho</a> 强力驱动'); ?>.
</footer><!-- end #footer -->
<script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="<?php $this->options->themeUrl('markdown/highlight.pack.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
    });
});
</script>
<?php $this->footer(); ?>
</body>
</html>
