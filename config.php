<?php
    return [
        //数据库配置
        'db' => [
            'driver' => 'Mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'username' => 'root',
            'password' => 'root',
            'db' => 'test',
        ],
        'build' => [
            'plural' => true,//目录名为复数, 如 Models Beans
            'field_camel' => true, // 字段驼峰命名 如   user_id 则变量名为 $userId
            'model_extends' => 'Model', //  默认继承类. TP 为 Model.
            'create_method' => 'add', // 默认创建数库记录的方法. TP 默认为 add
            'output' => DB_BUILDER_APP_PATH . '/output',//生成目录
            'namespace' => 'App',//默认命名空间  如 App 生成后则为  App\Controllers App\Models App\Service 等
            'controller_extends'=>'Controller',
            //下面4项对应的是 列表 增 删 改 查 对应的就去名 TP 默认是 xxxxAction
            'controller_list_action' => 'lists',
            'controller_add_action' => 'add',
            'controller_del_action' => 'del',
            'controller_edit_action' => 'edit',
            'controller_view_action' => 'view',
        ]
    ];