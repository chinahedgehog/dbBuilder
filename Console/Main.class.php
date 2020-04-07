<?php
/**
 * @File:Main.class.php
 * @Author:WangXiaohua creates the file in 2020/4/7
 * @Email:123695033@qq.com(Wechat && QQ)
 * @Blog:www.wangxiaohua.top
 */

namespace DbBuilder\Console;

use DbBuilder\Library\DbBuilder;
use DbBuilder\Library\Model\Model;

class Main {
    public static function run() {
        $tpl  = "dbBuilder action table; egg:dbBuilder create table1";
        $argv = DbBuilder::getArgv();
        if (3 != count($argv)) {
            exit($tpl);
        }
        list($_, $action, $table) = $argv;
        switch ($action) {
            case "create" :
                self::createBean($table);
                self::createModel($table);
                self::createController($table);
                break;
            default :
                exit($tpl);
        }
    }

    private static function makeTableName($table) {
        $table  = strtolower($table);
        $tables = explode("_", $table);
        $table  = '';
        foreach ($tables as $tableStr) {
            $table .= ucfirst($tableStr);
        }
        return $table;
    }

    private static function getFieldType($type) {
        $typeStruct = [
            'type' => '',
            'rule' => [
                'max' => 0,//最大
                'min' => 0,//最小
                'preg' => '', //匹配正则
            ],
        ];
        $type       = strtolower($type);
        if ('varchar' == substr($type, 0, 7)) {
            $typeStruct['type'] = 'String';
        } else if ('longtext' == $type) {
            $typeStruct['type'] = 'String';
        } else if ('text' == $type) {
            $typeStruct['type'] = 'String';
        } else if ('mediumblob' == $type) {
            $typeStruct['type'] = 'String';
        } else if ('longblob' == $type) {
            $typeStruct['type'] = 'String';
        } else if ('text' == $type) {
            $typeStruct['type'] = 'String';
        } else if ('char' == substr($type, 0, 4)) {
            $typeStruct['type'] = 'String';
        } else if ('decimal' == substr($type, 0, 6)) {
            $typeStruct['type'] = 'String';
        } else if ('double' == substr($type, 0, 6)) {
            $typeStruct['type'] = 'String';
        } else if ('int' == substr($type, 0, 3)) {
            $typeStruct['type'] = 'Integer';
            if (strpos($type, 'unsigned')) {
            } else {
            }
            $typeStruct['type'] = 'String';

        } else if ('tinyint' == substr($type, 0, 7)) {
            $typeStruct['type'] = 'Integer';
            if (strpos($type, 'unsigned')) {
            } else {
            }
            $typeStruct['type'] = 'String';
        }else if ('datetime' == substr($type, 0, 8)) {
            $typeStruct['type'] = 'String';

        }
        return $typeStruct;
    }

    public static function createController($table) {
        $model         = new Model($table);
        $primaryKey = $model->getPrimaryKey();
        $ucPrimaryKey = ucfirst($primaryKey);
        if (!$primaryKey) {
            exit('该表无主键,暂时无法创建 Model');
        }
        $config        = DbBuilder::getConfig('build');
        $extends = $config['model_extends'];
        $modelPath      = $config['output'] . "/" . strtr($config['namespace'], '\\', '/') . "/Controller";
        if ($config['plural']) $modelPath .= 's';
        $className = self::makeTableName($table);
        $modelFile  = $modelPath . "/" . $className . "Controller.class.php";
        //生成注释
        $namespaceStr = "  {$config['namespace']}\Controller" . ($config['plural'] ? 's' : '') ;
        $controllerName =  $className;
        $find = [];
        $replace = [];
        $find[] = '__NAMESPACE__';
        $find[] = '__CONTROLLER__';
        $find[] = '__BASE_CONTROLLER__';
        $find[] = '__LIST__';
        $find[] = '__DEL__';
        $find[] = '__ADD__';
        $find[] = '__EDIT__';
        $find[] = '__VIEW__';
        $replace[] = $namespaceStr;
        $replace[] = $controllerName;
        $replace[] = $config['controller_extends'];
        $replace[] = $config['controller_list_action'];
        $replace[] = $config['controller_add_action'];
        $replace[] = $config['controller_del_action'];
        $replace[] = $config['controller_edit_action'];
        $replace[] = $config['controller_view_action'];


        $tpl = file_get_contents(DB_BUILDER_APP_PATH . '/Library/Tpl/Controller.tpl.php');
        $tpl = str_replace($find, $replace, $tpl);


        if (!is_dir($modelPath))mkdir($modelPath, 0777, 1);
        file_put_contents($modelFile, $tpl);
    }
    public static function createModel($table) {
        $model         = new Model($table);
        $primaryKey = $model->getPrimaryKey();
        $ucPrimaryKey = ucfirst($primaryKey);
        if (!$primaryKey) {
            exit('该表无主键,暂时无法创建 Model');
        }
        $config        = DbBuilder::getConfig('build');
        $extends = $config['model_extends'];
        $modelPath      = $config['output'] . "/" . strtr($config['namespace'], '\\', '/') . "/Model";
        if ($config['plural']) $modelPath .= 's';
        $className = self::makeTableName($table);
        $beanName = lcfirst($className);
        $modelFile  = $modelPath . "/" . $className . "Model.class.php";
        $fileContent = '';
        //生成注释
        $fileContent .= "<?php /** \n*@author dbBuilder\n */\n";
        $fileContent .= "namespace {$config['namespace']}\Model" . ($config['plural'] ? 's' : '') . ";\n\n";
        $fileContent .= "class {$className}Model ". ($extends ? ' extends ' . $extends : '') ."{\n";

        $fileContent .= "\t/** \n\t *@param \${$beanName}Bean  \\{$config['namespace']}\Bean" . ($config['plural'] ? 's' : '') . "\\{$className}Bean    \n\t*/\n";
        $fileContent .= "\tpublic function create{$className}ByBean(\${$beanName}Bean) {\n\t\t return \$this->{$config['create_method']}(\${$beanName}Bean->asArray());\n\t}\n" ;
        $fileContent .= "\t/** \n\t *@param \$id int\n\t *@return   \\{$config['namespace']}\\Bean" . ($config['plural'] ? 's' : '') . "\\{$className}Bean  \n\t*/\n";
        $fileContent .= "\tpublic function get{$className}By{$ucPrimaryKey}(\$$primaryKey) {\n" ;

        $fileContent .= "\t\t\${$beanName}Bean = new   \\{$config['namespace']}\\Bean" . ($config['plural'] ? 's' : '') . "\\{$className}Bean();\n" ;
        $fileContent .= "\t\t\$data = \$this->where(['$primaryKey' => \$$primaryKey])->find();\n\t\tif(\$data) {\n\t\t\t\${$beanName}Bean->setAttributes(\$data);\n\t\t}\n\t\treturn  \${$beanName}Bean;\n\t}\n" ;

        $fileContent .= "}\n";

        if (!is_dir($modelPath))mkdir($modelPath, 0777, 1);
        file_put_contents($modelFile, $fileContent);


    }
    public static function createBean($table) {
        $config        = DbBuilder::getConfig('build');
        $model         = new Model($table);
        $fieldInfoList = $model->getTableStruct();
        $beanPath      = $config['output'] . "/" . strtr($config['namespace'], '\\', '/') . "/Bean";
        if ($config['plural']) $beanPath .= 's';
        $className = self::makeTableName($table);
        $beanFile  = $beanPath . "/" . $className . "Bean.class.php";

        $fileContent = '';
        //生成注释
        $fileContent .= "<?php /** \n*@author dbBuilder\n */\n";
        $fileContent .= "namespace {$config['namespace']}\Bean" . ($config['plural'] ? 's' : '') . ";\n\n";
        $fileContent .= "class {$className}Bean {\n";


        $varStr        = '';
        $geterSeterStr = '';
        $toArrayStr = "\t\t\$toArray = [];\n";
        //取出表结构. 生成 bean
        if ($fieldInfoList) foreach ($fieldInfoList as $field) {
            //Field Type Null Key Default Extra
            $fieldName = $field['Field'];
            $fieldType = self::getFieldType($field['Type']);
            if ($config['field_camel']) {
                $fieldName = lcfirst(self::makeTableName($fieldName));
            }
            $varStr        .= "\t/** \n\t *@var {$fieldType['type']} {$fieldName}\n\t */\n\tprivate $" . "{$fieldName};\n";
            $geterSeterStr .= "\t/** \n\t *@return  {$fieldType['type']} \n\t*/\n\tpublic function get$fieldName() {\n\t\t" . 'return $this->' . "$fieldName;\n\t}\n";
            $geterSeterStr .= "\t/** \n\t *@param \$$fieldName  {$fieldType['type']}    \n\t*/\n\tpublic function set$fieldName(\$$fieldName) {\n\t\t" . '$this->' . "$fieldName = \$$fieldName;\n\t}\n";
            $toArrayStr .= "\t\t\$toArray['{$fieldName}'] = \$this->{$fieldName};\n";
        } else {
            exit("表未找到\n");
        }
        $fileContent .= $varStr;


        $fileContent .= $geterSeterStr ;
        $fileContent .= "\t/** \n\t *@return [] \n\t*/\n\tpublic function asArray() {\n$toArrayStr\n\t\treturn \$toArray;\n\t}";
        $fileContent .= "\t/** \n\t *@return void \n\t*/\n\tpublic function setAttributes(\$Attributes) {\n\t\t\tforeach (\$Attributes as \$key => \$value){\n\t\t\t\tif(isset(\$this->\$\$key))\$this->\$\$key = \$value;\n\t\t}\n\t}\n";
        $fileContent .= "}\n";

        if (!is_dir($beanPath))mkdir($beanPath, 0777, 1);
        file_put_contents($beanFile, $fileContent);
    }
}
