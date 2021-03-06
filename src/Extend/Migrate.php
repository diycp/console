<?php
namespace Console\Extend;

use Console\System;

use Console\config;

/**
 * Created by PhpStorm.
 * User: 007
 * Date: 2016/8/8
 * Time: 11:23
 */
class Migrate
{
    /**
     * 创建数据库
     */
    static public function migrate(){
        $dir = config::database().'/migrations/';
        $file=scandir($dir);
        unset($file[0]);unset($file[1]);
        foreach ($file as $name) {
            $name      = pathinfo($name)['filename'];
            $namespace = 'Console\\Database\\migrations\\'.$name;
            $obj       = new $namespace();
            $obj->up();
            echo "\ncreate $name successfully";
        }
        echo "\n";
    }

    /**
     * 回滚所有迁移并且再执行一次
     */
    static public function refresh(){
        self::reset();
        self::migrate();
        $param      = config::param();
        if ( $param[0]=='--seed' ){
            Db::seed();
        }
    }

    /**
     * 复原所有表
     */
    static public function reset(){
        $dir = config::database().'/migrations/';
        $file=scandir($dir);
        unset($file[0]);unset($file[1]);
        foreach ($file as $name) {
            $name      = pathinfo($name)['filename'];
            $namespace = 'Console\\Database\\migrations\\'.$name;
            $obj       = new $namespace();
            $obj->down();
            echo "\ndelete $name successfully";
        }
    }
}