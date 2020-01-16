<?php
/**
 * 定时发布文章插件
 */

include_once __DIR__ . '/database.php';
include_once __DIR__ . '/article.php';
include_once __DIR__ . '/function.php';

/**
 * 注册插件
 */
RegisterPlugin('LemonTT', 'ActivePlugin_LemonTT');

/**
 * 激活插件工具
 */
function ActivePlugin_LemonTT()
{
    // 挂载系统接口
	Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu', 'LemonTT_Plugin_Admin_TopMenu');
    Add_Filter_Plugin('Filter_Plugin_ViewIndex_Begin', 'LemonTT_ViewIndex_Begin');
    Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'LemonTT_OutputDateSelector');
    Add_Filter_Plugin('Filter_Plugin_PostArticle_Succeed', 'LemonTT_PostArticle_Succeed');
    Add_Filter_Plugin('Filter_Plugin_Admin_ArticleMng_Table', 'LemonTT_Admin_ArticleMng_Table');
}

/**
 * 安装插件执行内容
 */
function InstallPlugin_LemonTT()
{
    global $zbp;
    LemonTT_CreateTable();
}

/**
 * 卸载插件执行内容
 */
function UninstallPlugin_LemonTT()
{
}

/**
 * 后台顶部添加菜单入口
 */
function LemonTT_Plugin_Admin_TopMenu(&$menu)
{
    global $zbp;
    $menu[] = MakeTopMenu('root', '文章定时任务', $zbp->host . 'zb_users/plugin/LemonTT/main.php', '', '');
}
