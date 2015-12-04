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
        Typecho_Plugin::factory('admin/write-post.php')->bottom = array(__CLASS__, 'editor_mode');
        Typecho_Plugin::factory('admin/write-page.php')->bottom = array(__CLASS__, 'editor_mode');
    }
    /**
     *实现编辑器左右分
     *@return void
     */
    public static function editor_mode() {
        die("33");
        $potions = Helper::options()->personalPlugin('CodeStyle');
        $mode = $potions->editor_mode;
        $style = Helper::options()->pluginUrl . '/CodeStyle/markdown/styles/'.$potions->code_style;
        $lrcss = Helper::options()->pluginUrl . '/CodeStyle/markdown/styles/lr.css';
        if($mode == 1) {
            //如果开启了左右模式
            $html = <<<HTML
            <link rel="stylesheet" type="text/css" href="{$style}" />
            <link rel="stylesheet" type="text/css" href="{$lrcss}" />
HTML;
        die($mode);
        }
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
        $name = new Typecho_Widget_Helper_Form_Element_Select('code_style', $styles, 'default.css', _t('选择你的代码风格'));
        $form->addInput($name->addRule('enum', _t('必须选择配色样式'), $styles));

        $editor_mode = new Typecho_Widget_Helper_Form_Element_Radio('editor_mode', array(
            0   =>  _t('不'),
            1   =>  _t('是')
        ), 0, _t('Markdown编辑器模式'), _t('是否将Markdown编辑器分成左右（还在写。。。）'));
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
    }

    /**
     *为footer添加js文件
     *@return void
     */
    public static function footer() {
        $jsUrl = Helper::options()->pluginUrl . '/CodeStyle/markdown/highlight.pack.js';
        echo <<<HTML
            <script type="text/javascript" src="{$jsUrl}"></script>
            <script type="text/javascript">
                hljs.initHighlightingOnLoad();
            </script>
HTML;
    }
}
