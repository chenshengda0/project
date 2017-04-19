<?php
return array(

	//'配置项'=>'配置值'
	'URL_MODEL' => '0',
	//'APP_GROUP_LIST'=>'Index',
	
	// 默认输出编码
	'DEFAULT_CHARSET' => 'UTF-8', 
	// 数据库配置,模块的配置在模块对于的model里面设置
	'DB_TYPE' => 'mysql',
	'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'db_sdk',    
	'DB_USER' => 'lianfafajiesql',   
	'DB_PWD' => 'fafalian##341345AA', 
    
    'DB_PORT' => '3306',
    'DB_PREFIX' => 'c_',
    'DB_CHARSET' => 'UTF8', // 数据库编码默认采用UTF

	// 项目设置
	'MEMCACHE_STATUS' => TRUE,
    'BEANSDB_STATUS' => TRUE,	

	/* 语言设置 */
    'LANG_SWITCH_ON'        => TRUE,   // 默认关闭多语言包功能
    'LANG_AUTO_DETECT'      => FALSE,   // 自动侦测语言 开启多语言功能后有效

	// url 模式
	'URL_DISPATCH_ON' => FALSE,
	
	// 不区分大小写
	'URL_CASE_INSENSITIVE' =>   TRUE,
	// 自动加载
	'APP_AUTOLOAD_PATH'=> '@.Common.,@.Model.,',
	//配置后缀
	'TMPL_TEMPLATE_SUFFIX' => '.tpl',
	//模板分隔符
	'TMPL_L_DELIM'          => '<{',			// 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          => '}>',			// 模板引擎普通标签结束标记

	// 调试模式
	'APP_DEBUG' => FALSE ,	// => FALSE
	'SHOW_DB_TIMES'=> FALSE,  	// => FALSE
	'DB_FIELDTYPE_CHECK'=>true,  // 开启字段类型验证
	'VAR_FILTERS'=>'htmlspecialchars'  //系统变量的全局过滤
);