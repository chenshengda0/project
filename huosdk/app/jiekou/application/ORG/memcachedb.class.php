<?php
/**
 * 找法网Memcached中间层实现类
 * 
 * @author lijian <1610108938@qq.com>
 *
 */
/**
 * 找法网Memcached中间层实现类
 * 
 * @author lijian <1610108938@qq.com>
 *
 */
class memcachedb
{
    /**
    +----------------------------------------------------------
    * BeansDB对象
    +----------------------------------------------------------
    */
    var $_beansdb;
    /**
    +----------------------------------------------------------
    * Memcache对象
    +----------------------------------------------------------
    */
    var $_memcache;
    /**
    +----------------------------------------------------------
    * DB对象
    +----------------------------------------------------------
    */
    var $db;
    /**
    +----------------------------------------------------------
    * BeansDB 键名
    +----------------------------------------------------------
    */
    var $keyname;
    /**
    +----------------------------------------------------------
    * 数据类型
    +----------------------------------------------------------
    */
    var $type;
    /**
    +----------------------------------------------------------
    * 数据描述
    +----------------------------------------------------------
    */
    var $desc;
    /**
    +----------------------------------------------------------
    * 键值
    +----------------------------------------------------------
    */
    var $insert_id;
    /**
    +----------------------------------------------------------
    * Memcache 存活时间
    +----------------------------------------------------------
    */
    var $live_time;
    /**
    +----------------------------------------------------------
    * 数据表
    +----------------------------------------------------------
    */
    var $tableName;
    /**
    +----------------------------------------------------------
    * 更新数组
    +----------------------------------------------------------
    */
    var $sqlClause;
    /**
    +----------------------------------------------------------
    * 条件
    +----------------------------------------------------------
    */
    var $whereClause;
    
    /**
    +----------------------------------------------------------
    * BeansDB构造函数
    +----------------------------------------------------------
    */
    function memcachedb()
    {
        $this->live_time = 600;
        
        include_once 'config_beans_mem.php';
        if (is_array($beansdbcfginfo)) {
            $this->_beansdb = new Memcache();
            foreach ($beansdbcfginfo as $h) {
                $this->_beansdb->addServer($h[0], $h[1], true, 1, 3) or die(0);
            }
        } else {
            die(0);
        }

        if (is_array($memcachecfginfo)) {
            $this->_memcache = new Memcache();
            foreach ($memcachecfginfo as $h) {
                $this->_memcache->addServer($h[0], $h[1], true, 1, 3) or die(0);
            }
        } else {
            die(0);
        }
    }

    /**
     * 数据库连接函数
     * 
     * @return void
     */
    function dbconnect()
    {
        if (!$this->db) {
            // mysql://findlaw:123456@localhost:3306/findlaw_db
            $this->db = Db::getInstance(C('DB_CONNECT_URL'));
        }
    }

    /**
     * BeansDB 数据写函数 Detail 详细数据写入BeansDB
     * 
     * @param string $value    未知
     * @param string $_is_text 未知
     * 
     * @return void
     */
    function setBeans($value, $_is_text = '')
    {
        $keyname = $this->setKey($this->type, $this->desc, $this->insert_id, $_is_text);
        $this->_bset($keyname, $value);
    }
    
    /**
     * BeansDB 数据读函数 用于验证记录是否存在
     * 
     * @param int    $insert_id 未知
     * @param string $_is_text  未知
     * 
     * @return string
     */
    function getBeans($insert_id, $_is_text = '')
    {
        $keyname = $this->setKey($this->type, $this->desc, $insert_id, $_is_text);
        return $this->_bget($keyname);
    }

    /**
     * BeansDB 数据读函数 用于程序读取详细数据  Detail 详细数据从BeansDB读取
     * 
     * @param int    $insert_id 未知
     * @param string $_is_text  未知
     * 
     * @return string
     */
    function getDetail($insert_id, $_is_text = '')
    {
        $keyname = $this->setKey($this->type, $this->desc, $insert_id, $_is_text);
        if ($this->_bget($keyname)) {
            return $this->_bget($keyname);
        } else {
            $sql = $this->buildSelect('*', C('DB_PREFIX') . $this->type, 'id = ' . $insert_id);
            //连数据库
            $this->dbconnect();
            //查表
            $dataDetail = $this->db->query($sql);
            //存BeansDB
            $this->_bset($keyname, $dataDetail);
            //返回数据
            return $dataDetail;
        }
    }

    /**
     * Memcache 数据写函数
     * 
     * @param unknown_type $keyname   SELECT SQl语句 MD5后做Memcache键值使用
     * @param unknown_type $value     未知
     * @param unknown_type $live_time 未知
     * @param unknown_type $table     未知
     * 
     * @return 列表数据写入Memcache缓存
     */
    function setMemcache($keyname, $value, $live_time = 0, $table = '')
    {
        if ($live_time == 0) {
            $live_time = $this->live_time;
        }
        $this->_memcache->set($keyname, $value, $live_time);
    }

    /**
     * Memcache 数据读函数
     * 
     * @param unknown_type $sql 未知
     * 
     * @return unknown
     */
    function getMemcache($sql)
    {
        //Memcache中是否存在该列表
        //Memcache键值
        $keyname = md5($sql);
        if ($this->_memcache->get($keyname)) {
            //返回数据
            return $this->_memcache->get($keyname);
        } else {
            //连数据库
            $this->dbconnect();
            $dataList = $this->db->query($sql);
            //存Memcache
            $this->setMemcache($keyname, $dataList);
            //返回数据
            return $dataList;
        }
    }
    
    /**
     * 生成插入数SQL
     * 
     * @param string $sqlClause 字段、值对应表
     * @param string $tableName 数据表名
     * @param string $fieldType 字段类型
     * 
     * @return unknown
     */
    function buildInsert($sqlClause = array(), $tableName = '', $fieldType = '')
    {
        //插入数组
        if (count($sqlClause) == 0) {
            $sqlClause = $this->sqlClause;
        }
        if (count($sqlClause) == 0) {
            return;
        }
        //数据表名
        if ($tableName == '') {
            $tableName = $this->tableName;
        }
        if ($tableName == '') {
            return;
        }

        $sql1 = array();
        $sql2 = array();
        foreach ($sqlClause as $ids => $val) {
            if (!strstr($ids, '`')) {
                $sql1[] = $ids;
            } else {
                $sql1[] = $ids;
            }
            if ($fieldType[$ids] == 'int') {
                $sql2[] = $val;
            } else {
                $sql2[] = '\'' . $val . '\'';
            }
        }
        $sql = 'INSERT INTO ' . C('DB_PREFIX') . $tableName . '(' . implode(',', $sql1) . ') VALUES(' . implode(',', $sql2) . ')';
        
        //return $sql;
        //连数据库
        $this->dbconnect();
        $this->db->execute($sql);
        //写入BeansDB
        //暂未写，以后补
        return $this->db->lastInsID;
    }

    /**
     * 生成删除记录SQL
     * 
     * @param string $tableName   数据表名
     * @param string $whereClause 删除条件  
     * 
     * @return unknown
     */
    function buildDelete($tableName = '', $whereClause = '')
    {
        //数据表名
        if ($tableName == '') {
            $tableName = $this->tableName;
        } 
        if ($tableName == '') {
            return;
        }
        //删除条件
        if ($whereClause == '') {
            $whereClause = $this->whereClause;
        }
        if ($whereClause == '') {
            return;
        }
        $sql = 'DELETE FROM ' . C('DB_PREFIX') . $tableName . ' WHERE ' . $whereClause;
        
        //return $sql;
        //连数据库
        $this->dbconnect();
        $this->db->execute($sql);
        return $this->db->numRows;
    }

    /**
     * 生成更新SQL
     * 
     * @param string $sqlClause   字段、值对应表
     * @param string $tableName   数据表名
     * @param string $whereClause 更新条件
     * @param string $fieldsType  字段类型
     * 
     * @return unknown
     */
    function buildUpdate($sqlClause = array(), $tableName = '', $whereClause = '', $fieldsType = '')
    {
        //更新数组
        if (count($sqlClause) == 0) {
            $sqlClause = $this->sqlClause;
        }
        if (count($sqlClause) == 0) {
            return;
        }
        //数据表名
        if ($tableName == '') {
            $tableName = $this->tableName;
        }
        if ($tableName == '') {
            return;
        }
        //更新条件
        if ($whereClause == '') {
            $whereClause = $this->whereClause;
        }
        if ($whereClause == '') {
            return;
        }
        
        $sql = array();
        foreach ($sqlClause as $ids => $val) {
            if (isset($fieldsType[$ids]) && $fieldsType[$ids] == 'int') {
                $sql[] = $ids . ' = ' . $val;
            } else {
                $sql[] = $ids . ' = \'' . $val . '\'';
            }
        }
        $sql = implode(',', $sql);
        $sql = 'UPDATE ' . C('DB_PREFIX') . $tableName . ' SET ' . $sql . ' WHERE ' . $whereClause;
        
        //return $sql;
        //连数据库
        $this->dbconnect();
        $this->db->execute($sql);
        //写入BeansDB
        //暂未写，以后补
        return $this->db->numRows;
    }

    /**
     * 生成查询数SQL
     * 
     * @param string $selectClause SELECT字段
     * @param string $tableName    数据表名
     * @param string $whereClause  查询条件
     * @param string $orderClause  排序字段
     * @param string $groupClause  BROUP BY 字段
     * @param int    $limitClause  记录数
     * @param int    $offertClause 起始记录
     * 
     * @return void|string
     */
    function buildSelect($selectClause = '*', $tableName = '', $whereClause = '', $orderClause = '', $groupClause = '', $limitClause = '', $offertClause = 0)
    {
        //数据表名
        if ($tableName == '') {
            $tableName = $this->tableName;
        }
        if ($tableName == '') {
            return;
        }
        //查询条件
        if ($whereClause == '') {
            $whereClause = $this->whereClause;
        }

        $sql = 'SELECT ' . $selectClause . ' FROM ' . C('DB_PREFIX') . $tableName;
        if ($whereClause != '') {
            $sql .= ' WHERE ' . $whereClause;
        }
        if ($groupClause != '') {
            $sql .= ' GROUP BY ' . $groupClause;
        }
        if ($orderClause != '') {
            $sql .= ' ORDER BY ' . $orderClause;
        }
        //MYSQL
        if (strlen($limitClause) >= 1) {
            $sql .= ' LIMIT ' . $limitClause;
        }
        if ($offertClause != 0) {
            $sql .= ' , ' . $offertClause;
        }
        return $sql;
    }

    /**
     *  Memcache 数据写函数
     *  
     * @param string       $keyname   SELECT SQl语句 MD5后做Memcache键值使用
     * @param unknown_type $value     未知
     * @param unknown_type $live_time 未知
     * @param unknown_type $table     未知
     * 
     * @return 列表数据写入Memcache缓存
     */
    function _set($keyname, $value, $live_time = 0, $table = '')
    {
        if ($live_time == 0) {
            $live_time = $this->live_time;
        }
        $this->_memcache->set($keyname, $value, false, $live_time);
    }

    /**
     * Memcache 数据读函数
     * 
     * @param string $keyname SELECT SQl语句 MD5后做Memcache键值使用
     * 
     * @return 列表数据从Memcache缓存读取
     */
    function _get($keyname)
    {
        //Memcache缓存
        if ($this->_memcache->get($keyname)) {
            return $this->_memcache->get($keyname);
        } else {
            return false;
        }
    }
    
    /**
     * 未知
     * 
     * @param unknown_type $keyname 未知
     * 
     * @return 未知
     */
    function _mdelete($keyname)
    {
        $this->_memcache->delete($keyname);
    }
    
    /**
     * 未知
     *
     * @return 未知
     */
    function _mclose()
    {
        @$this->_memcache->close();
    }

    /**
     * 未知
     * 
     * @param unknown_type $type      数据类别
     * @param unknown_type $desc      描述
     * @param unknown_type $insert_id 键值
     * @param unknown_type $_is_text  是否大文本
     * 
     * @return string
     */
    function setKey($type, $desc = '', $insert_id = 0, $_is_text = '')
    {
        return $type . '_' . $desc . '_' . $insert_id . $_is_text;
    }
    
    /**
     * 未知
     * 
     * @param unknown_type $keyname 未知
     * @param unknown_type $value   未知
     * 
     * @return unknown
     */
    function _bset($keyname, $value)
    {
        $this->_beansdb->set($keyname, $value);
    }
    
    /**
     * 未知
     *
     * @param unknown_type $keyname 未知
     *
     * @return unknown
     */
    function _bget($keyname)
    {
        return $this->_beansdb->get($keyname);
    }
    
    /**
     * 未知
     *
     * @param unknown_type $keyname 未知
     *
     * @return unknown
     */
    function _bdelete($keyname)
    {
        $this->_beansdb->set($keyname, '');
    }
    
    /**
     * 未知
     *
     * @return unknown
     */
    function _bclose()
    {
        @$this->_beansdb->close();
    }

    /**
     * 析构方法
     * 
     * @return unknown
     */
    public function __destruct()
    {
        // 关闭连接
        @$this->_bclose();
        @$this->_mclose();
    }
}

?>