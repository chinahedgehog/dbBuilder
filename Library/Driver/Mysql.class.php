<?php
/**
 * @File:Mysql.php
 * @Author:WangXiaohua creates the file in 2020/4/7
 * @Email:123695033@qq.com(Wechat && QQ)
 * @Blog:www.wangxiaohua.top
 *
 * #
 */

namespace DbBuilder\Library\Driver;
class Mysql {
    /**
     * @var \PDO
     */
    static $pdo;

    public function __construct($host, $username, $password, $db, $port) {
        if (self::$pdo) {
            return self::$pdo;
        }
        self::$pdo = new \PDO("mysql:$host;port:$port;dbname:$db;", $username, $password);
        self::$pdo->query("use `$db`");
    }

    public function query($sql) {

        $ps = self::$pdo->query($sql);
        if ($ps) {
            return $ps->fetchAll();
        } else return false;

    }
}