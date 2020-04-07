<?php
/**
 * @File:Model.class.php
 * @Author:WangXiaohua creates the file in 2020/4/7
 * @Email:123695033@qq.com(Wechat && QQ)
 * @Blog:www.wangxiaohua.top
 */

namespace DbBuilder\Library\Model;

use DbBuilder\Library\DbBuilder;

class Model {
    private $driver;
    private $table;
    private $fieldInfoList;

    public function __construct($table) {
        $config       = DbBuilder::getConfig('db');
        $driver       = "DbBuilder\\Library\\Driver\\" . ucfirst($config['driver']);
        $this->driver = new $driver($config['host'], $config['username'], $config['password'], $config['db'], $config['port']);
        $this->table  = $table;
    }

    public function getTableStruct() {
        $sql = "desc `$this->table`";
        $fieldInfoList  = $this->driver->query($sql);
        return $this->fieldInfoList = $fieldInfoList;
    }

    public function getPrimaryKey(){
        foreach ($this->getTableStruct() as $field) {
            if ($field['Key'] == 'PRI') {
                return $field['Field'];
            }
        }

    }
}