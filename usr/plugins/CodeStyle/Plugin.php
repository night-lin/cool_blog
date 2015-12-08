<?php
/**
 * 代码显示样式风格 可多用户不同风格
 * 
 * @package CodeStyle 
 * @author hongweipeng
 * @version 0.5.1
 * @link http://blog.west2online.com
 */
class CodeStyle_Plugin implements Typecho_Plugin_Interface {
     /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
        Typecho_Plugin::factory('admin/write-post.php')->bottom = array(__CLASS__, 'editor_show_style');
        Typecho_Plugin::factory('admin/write-page.php')->bottom = array(__CLASS__, 'editor_show_style');
    }
    /**
     *实现编辑器左右分
     *@return void
     */
    public static function editor_show_style() {
        
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){}

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){
        $styles = array_map('basename', glob(dirname(__FILE__) . '/markdown/styles/*.css'));
        $styles = array_combine($styles, $styles);
        $name = new Typecho_Widget_Helper_Form_Element_Select('code_style', $styles, 'segmentfault.css', _t('选择你的代码风格'));
        $form->addInput($name->addRule('enum', _t('必须选择配色样式'), $styles));

        $editor_mode = new Typecho_Widget_Helper_Form_Element_Radio('editor_mode', array(
            0   =>  _t('不'),
            1   =>  _t('是')
        ), 0, _t('Markdown编辑器左右模式'), _t('是否将Markdown编辑器分成左右（还在写。。。）'));
        $form->addInput($editor_mode->addRule('enum', _t('必须选择一个模式'), array(0, 1)));
    }

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render() {
        
    }

    /**
     *为header添加css文件
     *@return void
     */
    public static function header() {
        $style = Helper::options()->personalPlugin('CodeStyle')->code_style;
        $cssUrl = Helper::options()->pluginUrl . '/CodeStyle/markdown/styles/' . $style;
        echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />';
        echo <<<HTML
        <style>
pre {
    position: relative;
    margin-bottom: 24px;
    border-radius: 3px;
    border: 1px solid #C3CCD0;
    background: #FFF;
    overflow: hidden;
}

code {

}

code.has-numbering {
    margin-left: 21px;
}

.pre-numbering {
    position: absolute;
    top: 0;
    left: 0;
    width: 20px;
    padding: 0.5em 2px 12px 0;
    border-right: 1px solid #C3CCD0;
    border-radius: 3px 0 0 3px;
    background-color: #EEE;
    text-align: right;
    font-family: Menlo, monospace;
    font-size: 0.92857em;
    color: #AAA;
    margin-top: 0px;
    list-style:none;
}
</style>
HTML;
    }

    /**
     *为footer添加js文件
     *@return void
     */
    public static function footer() {
        $jsUrl = Helper::options()->pluginUrl . '/CodeStyle/markdown/highlight.pack.js';
        echo <<<HTML
            <script src="http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
            <script type="text/javascript" src="{$jsUrl}"></script>
            <script type="text/javascript">
                hljs.initHighlightingOnLoad();
            $(function(){
            $('pre code').each(function(){
                var lines = $(this).text().split('\\n').length - 1;
                var numbering = $('<ul/>').addClass('pre-numbering');
                $(this)
                    .addClass('has-numbering')
                    .parent()
                    .append(numbering);
                for(i=1;i<=lines;i++){
                    numbering.append($('<li/>').text(i));
                }
            });
        });
            </script>
HTML;
    }
}
