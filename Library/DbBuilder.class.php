<?php
/**
 * @File:DbBuilder.class.php
 * @Author:WangXiaohua creates the file in 2020/4/7
 * @Email:123695033@qq.com(Wechat && QQ)
 * @Blog:www.wangxiaohua.top
 */

namespace DbBuilder\Library;

class DbBuilder {
    static $config;
    static $argv;

    public static function init() {
        //注册 loader
        spl_autoload_register("self::loader");
        self::        $config = include(DB_BUILDER_APP_PATH . "/config.php");
    }

    /**
     * @return mixed
     */
    public static function getConfig($key = null) {
        return $key ? self::$config[$key] : self::$config;
    }
    public static function getArgv() {
        return self::$argv;
    }

    public static function loader($class) {

        $class = substr(strtr($class, '\\', '/'), 10);
        $path  = realpath(DB_BUILDER_APP_PATH . "/" . $class . ".class.php");
        include $path;
    }
}