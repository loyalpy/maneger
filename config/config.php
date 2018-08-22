<?php

return array(
    'logs' =>
        array(
            'path' => 'backup/logs',
            'type' => 'file',
        ),
    'DB' =>
        array(
            'type' => 'mysqli',
            'tablePre' => 'iwebshop_',
            'read' =>
                array(
                    0 =>
                        array(
                            'host' => 'localhost:3306',
                            'user' => 'root',
                            'passwd' => '',
                            'name' => 'man',
                        ),
                ),
            'write' =>
                array(
                    'host' => 'localhost:3306',
                    'user' => 'root',
                    'passwd' => '',
                    'name' => 'man',
                ),
        ),
    /**
     * 拦截器，在 iWeb 框架的各个环节可以进行调用绑定的事件函数
     */
    'interceptor' =>
        array(
            0 => 'themeroute@onCreateController',
            1 => 'layoutroute@onCreateView',
            2 => 'plugin',
        ),
    'langPath' => 'language',
    'viewPath' => 'views',
    'skinPath' => 'skin',
    'classes' => 'classes.*',
    /**
     * 伪静态设置，url：非伪静态；pathinfo：伪静态
     */
    'rewriteRule' => 'url',
    'theme' =>
        array(
            'pc' =>
                array(
                    'sysdm' => 'default',
                    'sysseller' => 'default',
                    'huawei' => 'default',
                ),
            'mobile' =>
                array(
                    'sysdm' => 'default',
                    'sysseller' => 'default',
                    'default' => 'black',
                ),
        ),
    'timezone' => 'Etc/GMT-8',//北京时间
    'upload' => 'upload',
    'dbbackup' => 'backup/database',
    'safe' => 'cookie',
    'lang' => 'zh_sc',
    /**
     * debug:是否为调试模式
     * 设置 0：隐藏所有的错误信息，让访问者无感知，应用在正式运营阶段。
     * 设置 1：仅显示严重的足以让 php 停止的错误；
     * 设置 2：所有的错误信息都直接显示到当前页面中，方便调试；
     */
    'debug' => '0',
    /**
     * 可扩展配置文件，array(‘配置名称’=> ‘配置文件的路径’)
     */
    'configExt' =>
        array(
            'site_config' => 'config/site_config.php',
        ),
    'encryptKey' => 'd41d8cd98f00b204e9800998ecf8427e',
    'authorizeCode' => '',
    'uploadSize' => '10',
)

?>