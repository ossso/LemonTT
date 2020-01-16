<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('LemonTT')) {$zbp->ShowError(48);die();}

$blogtitle = '定时任务列表';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

$articleList = LemonTT_GetArtickeTark(false);

?>
<div id="divMain">
    <div class="divHeader"><?php echo $blogtitle;?></div>
    <div id="divMain2">
        <p style="line-height: 2; color: #033;">文章的定时任务规则，打开“定时发布”，文章状态为<b>草稿</b>或<b>审核</b>，发布时间大于当前时间</p>
        <table style="max-width: 900px;" border="1" class="tableFull tableBorder table_hover table_striped">
            <thead>
                <tr>
                    <th style="width: 100px">序号</th>
                    <th style="width: 100px">文章ID</th>
                    <th>文章标题（点击进入编辑页面）</th>
                    <th>设定的发布时间</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 0;
                    foreach ($articleList as $item) {
                        $i += 1;
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $item->Post->ID ?></td>
                    <td>
                        <a href="<?php echo $zbp->host ?>zb_system/cmd.php?act=ArticleEdt&id=<?php echo $item->Post->ID ?>"
                            target="_blank"
                            title="点击编辑文章">
                            <?php echo $item->Post->Title ?>
                        </a>
                    </td>
                    <td><?php echo $item->Time('Y/m/d H:i:s') ?></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
