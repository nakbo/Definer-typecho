<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 定义器助手
 *
 * @package Definer
 * @author 南博工作室
 * @version 1.0.0
 * @link https://github.com/kraity/Definer-typecho
 */
class Definer_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件
     * @return string|void
     */
    public static function activate()
    {
        Typecho_Plugin::factory('index.php')->begin = ['Definer_Plugin', 'definer'];
        Typecho_Plugin::factory('admin/common.php')->begin = ['Definer_Plugin', 'definer'];

        return _t('插件已经激活，需先配置插件信息！');
    }

    /**
     * 禁用插件
     */
    public static function deactivate()
    {
    }

    /**
     * @throws Typecho_Plugin_Exception
     */
    public static function definer()
    {
        $option = Helper::options()->plugin('Definer');

        if ($secure = $option->secure) {
            define('__TYPECHO_SECURE__', $secure == 2);
        }

        if ($option->ip_source) {
            define('__TYPECHO_IP_SOURCE__', 'HTTP_X_FORWARDED_FOR');
        }

        if ($gravatar_prefix = $option->gravatar_prefix) {
            define('__TYPECHO_GRAVATAR_PREFIX__', $gravatar_prefix);
        }

        if ($debug = $option->debug) {
            define('__TYPECHO_DEBUG__', $debug == 2);
        }

        if ($upload_dir = $option->upload_dir) {
            define('__TYPECHO_UPLOAD_DIR__', $upload_dir);
        }

        if ($theme_writeable = $option->theme_writeable) {
            define('__TYPECHO_THEME_WRITEABLE__', $theme_writeable == 2);
        }
    }

    /**
     * config
     * @param Typecho_Widget_Helper_Form $form
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $input = new Typecho_Widget_Helper_Form_Element_Radio(
            'secure', array(
            '0' => '默认',
            '1' => '关闭',
            '2' => '打开',
        ), '0', 'HTTPS', '<strong>默认表示不定义此配置</strong> 开启SSL启用全站HTTPS');
        $form->addInput($input);

        $input = new Typecho_Widget_Helper_Form_Element_Radio(
            'debug', array(
            '0' => '默认',
            '1' => '关闭',
            '2' => '打开',
        ), '0', 'DEBUG', '<strong>默认表示不定义此配置</strong> 打开后开启DEBUG模式');
        $form->addInput($input);

        $input = new Typecho_Widget_Helper_Form_Element_Radio(
            'theme_writeable', array(
            '0' => '默认',
            '1' => '关闭',
            '2' => '打开',
        ), '0', '主题编辑能力', '<strong>默认表示不定义此配置</strong> 关闭后，不能在后台编辑主题文件');
        $form->addInput($input);

        $input = new Typecho_Widget_Helper_Form_Element_Radio(
            'ip_source', array(
            '0' => '默认',
            '1' => '打开',
        ), '0', '真实IP', '<strong>默认表示不定义此配置</strong> 解决使用CDN后获取真实IP');
        $form->addInput($input);

        $input = new Typecho_Widget_Helper_Form_Element_Text(
            'upload_dir', null, '',
            '上传文件目录', '上传文件目录，<strong>如果为空表示不重新定义上传路径</strong>，默认为 /usr/uploads');
        $form->addInput($input);

        $server = new Typecho_Widget_Helper_Form_Element_Radio('gravatar_prefix', array(
            'https://gravatar.loli.net/avatar/' => 'LOLI镜像 <small>https://gravatar.loli.net</small>',
            'https://gravatar.cat.net/avatar/' => 'CAT镜像 <small>https://gravatar.cat.net</small>',
            'https://sdn.geekzu.org/avatar/' => '极客镜像 <small>https://sdn.geekzu.org</small>',
            'https://cdn.v2ex.com/gravatar/' => 'V2EX镜像 <small>https://cdn.v2ex.com</small>',
            'https://dn-qiniu-avatar.qbox.me/avatar/' => '七牛镜像 <small>https://dn-qiniu-avatar.qbox.me</small>',
            'http://cn.gravatar.com/avatar/' => 'CN镜像 <small>http://cn.gravatar.com</small>',
            'https://secure.gravatar.com/avatar/' => '源镜像 <small>https://secure.gravatar.com</small>',
            '' => '不定义镜像地址',),
            'https://gravatar.loli.net/avatar/', _t('Gravatar镜像'), _t('替换使Gravatar头像服务器'));
        $form->addInput($server->multiMode());
    }

    /**
     * @param Typecho_Widget_Helper_Form $form
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }
}
