<?php
/**
 * 文章任务表
 */
class LemonTTArticle extends Base
{
    public function __construct()
    {
        global $zbp;
        parent::__construct($zbp->table['LemonTTArticle'], $zbp->datainfo['LemonTTArticle'], __CLASS__);
    }

    /**
     * @param $name
     * @return array|int|mixed|null|string
     */
    public function __set($name, $value)
    {
        switch($name) {
            case 'Post':
                return null;
            break;
            default:
        }
        parent::__set($name, $value);
    }

    /**
     * @param $name
     * @return array|int|mixed|null|string
     */
    public function __get($name)
    {
        global $zbp;
        switch($name) {
            case 'Post':
                return $zbp->GetPostByID($this->LogID);
            default:
        }
        return parent::__get($name);
    }

    /**
     * @param string $s
     *
     * @return bool|string
     */
    public function Time($s = 'Y-m-d H:i:s')
    {
        return date($s, (int) $this->PostTime);
    }

    /**
     * 获取数据库内指定LogID的数据
     * @param int $id 指定LogID
     * @return bool
     */
    public function LoadInfoByLogID($id)
    {
        $id = (int) $id;
        $s = $this->db->sql->Select($this->table, array('*'), array(
            array('=', 'a_LogID', $id),
        ), null, null, null);

        $array = $this->db->Query($s);
        if (count($array) > 0) {
            $this->LoadInfoByAssoc($array[0]);
            return true;
        } else {
            return false;
        }
    }
}
