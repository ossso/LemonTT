<?php
/**
 * 获取文章定时任务列表
 */
function LemonTT_GetArtickeTark($nowEnd = false, $num = null)
{
    global $zbp;
    $w = array();
    $w[] = array('=', 'a_Status', '0');
    if ($nowEnd) {
        $w[] = array('<=', 'a_PostTime', time());
    }
    $order = array('a_PostTime' => 'ASC');
    $limit = null;
    if ($num) {
        $limit = array($num);
    }

    $sql = $zbp->db->sql->Select(
        $zbp->table['LemonTTArticle'],
        array('*'),
        $w,
        $order,
        $limit,
        null
    );
    $result = $zbp->GetListType('LemonTTArticle', $sql);

    if (count($result) > 0) {
        $ids = array();
        foreach ($result as $item) {
            $ids[] = $item->LogID;
        }
        LemonTT_CachePostList($ids);
    }
    
    return $result;
}

/**
 * 缓存文章列表
 */
function LemonTT_CachePostList($ids)
{
    global $zbp;
    $w = array();
    $w[] = array('IN', 'log_ID', $ids);
    $zbp->GetArticleList(array('*'), $w);
}

/**
 * 挂载刷新定时任务
 */
function LemonTT_ViewIndex_Begin()
{
    global $zbp;
    $list = LemonTT_GetArtickeTark(true);
    if (count($list) > 0) {
        $GLOBALS['lemonTTPostSucceed'] = true;
        foreach ($list as $item) {
            $post = $item->Post;
            $post->Status = 0;
            $post->PostTime = $item->PostTime;
            $post->Save();
            foreach ($GLOBALS['hooks']['Filter_Plugin_PostArticle_Succeed'] as $fpname => &$fpsignal) {
                $fpname($post);
            }
            $item->Status = 1;
            $item->Save();
        }
    }
}

/**
 * 在文章页输出任务定时选项
 */
function LemonTT_OutputDateSelector()
{
    global $zbp, $article;
    $type = GetVars('act', 'GET');
    if ($type != 'ArticleEdt') {
        return null;
    }
    echo '
        <div class="editmod">
        <label for="meta_lemonTT_Task" class="editinputname">定时发布</label>
        <input type="text" id="meta_lemonTT_Task" name="meta_lemonTT_Task" value="' . $article->Metas->lemonTT_Task . '" class="checkbox" style="display:none;" >
        </div>
    ';
    $lemonTTPostDate = $article->Metas->lemonTT_PostDate;
    echo '
    <div class="editmod">
        <label for="meta_lemonTT_PostDate" class="editinputname" style="max-width:85px;text-overflow:ellipsis;">发布日期</label>
        <input type="text" name="meta_lemonTT_PostDate" id="meta_lemonTT_PostDate" value="' . $lemonTTPostDate . '" style="width:150px;">
    </div>
    ';
    echo '
    <script>
        $(function() {
            $(\'#meta_lemonTT_PostDate\').datetimepicker({
                showSecond: true
            });
        });
    </script>
    ';
}

/**
 * 在文章保存成功的地方，判断是否加上任务
 */
function LemonTT_PostArticle_Succeed($article)
{
    global $zbp, $lemonTTPostSucceed;
    if (empty($lemonTTPostSucceed)) {
        $ttArt = new LemonTTArticle();
        $ttArt->LoadInfoByLogID($article->ID);
        if ($ttArt->ID == 0) {
            $ttArt->LogID = $article->ID;
        }
        if ($article->Metas->lemonTT_Task == 1 && $article->Status != 0) {
            $lemonTTPostDate = $article->Metas->lemonTT_PostDate;
            $lemonTTPostTime = strtotime($lemonTTPostDate);
            if ($lemonTTPostTime > time()) {
                $ttArt->Status = 0;
                $ttArt->PostTime = $lemonTTPostTime;
            } else {
                $ttArt->Status = 2;
            }
        } else {
            $ttArt->Status = 2;
        }
        $ttArt->Save();
    }
}

/**
 * 后台文章管理页面，输出定时标记
 */
function LemonTT_Admin_ArticleMng_Table(&$article, &$tabletds)
{
    global $zbp;
    if ($article->Metas->lemonTT_Task == 1 && $article->Status != 0) {
        $lemonTTPostDate = $article->Metas->lemonTT_PostDate;
        $lemonTTPostTime = strtotime($lemonTTPostDate);
        if ($lemonTTPostTime > time()) {
            $tabletds[4] = str_replace('<td>', '<td title="定于[' . $lemonTTPostDate . ']发布">[定时]', $tabletds[4]);
        }
    }
}
