<?php
/**
 * 数据库信息列表
 */
$lemon_tt_database = array(
    /**
     * 文章任务表
     */
    'LemonTTArticle'    => array(
        'name'          => '%pre%lemon_tt_article',
        'info'          => array(
            'ID'            => array('a_ID', 'integer', '', 0),
            'LogID'         => array('a_LogID', 'integer', '', 0),
            'Status'        => array('a_Status', 'integer', 'tinyint', 0),
            'PostTime'      => array('a_PostTime', 'integer', 'bigint', 0),
        	'Meta'          => array('a_Meta', 'string', '', ''),
        ),
    ),
);

foreach ($lemon_tt_database as $k => $v) {
    $table[$k] = $v['name'];
    $datainfo[$k] = $v['info'];
}

/**
 * 检查是否有创建数据库
 */
function LemonTT_CreateTable()
{
    global $zbp, $lemon_tt_database;
    foreach ($lemon_tt_database as $k => $v) {
        if (!$zbp->db->ExistTable($v['name'])) {
            $s = $zbp->db->sql->CreateTable($v['name'], $v['info']);
        	$zbp->db->QueryMulit($s);
        }
    }
}

