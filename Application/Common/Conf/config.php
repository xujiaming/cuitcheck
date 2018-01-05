<?php
return array(
	//不区分url的大小写，默认为true 但在调试模式下就默认为false
	'URL_CASE_INSENSITIVE'  =>  true, 
	//加载分块的配置项
	'LOAD_EXT_CONFIG'       => 'db,system,web',
	//模块
	'MODULE_ALLOW_LIST' => array('Student', 'Home'),
	//默认模块
	'DEFAULT_MODULE' => 'Home',
	//路由模式为2号模式
	'URL_MODEL'              => '2',
	//视图后缀名
	'TMPL_TEMPLATE_SUFFIX'=>'.html',
	//'TMPL_FILE_DEPR'=>'_',	//用下划线代替目录层次
	 //设置自定义标签文件路径
	'TAGLIB_BUILD_IN'       =>  'Cx,Common\Tag\mytag',
    'TMPL_PARSE_STRING'     =>  array(                        //定义常用路径
    '__PUBLIC__'      =>  __ROOT__.'/Public',
    '__SITE__'	=>'http://119.29.100.113/cuitcheck/',
    ),
    //配置session
    'SESSION_OPTIONS'       =>  array(
        'name'              =>  'cuitcheck',                 //设置session名
        //'expire'            =>  24*3600*15,                  //SESSION保存15天
        'expire'            =>  3600*24,
        'use_trans_sid'     =>  1,                           //跨页传递
        'use_only_cookies'  =>  0,                           //是否只开启基于cookies的session的会话方式
    ),
	// 数据库调试模式 开启后可以记录SQL日志
	'DB_DEBUG'  =>  TRUE,
	// 显示页面Trace信息
	'SHOW_PAGE_TRACE' =>true,
	//定义单页rows
	'PAGE_SIZE'=>10,
);