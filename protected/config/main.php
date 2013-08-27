<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Service Matrix',
	'theme'=>'classic',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.extensions.CAdvancedArBehavior',
                'application.extensions.ECompositeUniqueKeyValidatable',
                'application.extensions.ldap.adLDAP',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
                /*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii',
		),
                */
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
                'ldap'=>array(
                        'class'=>'application.extensions.ldap.AuthLdap',
                        'accountsuffix'=>'',
                        'basedn' => 'DC=domain,DC=local',
                        'domaincontrollers' => array('127.0.0.1'),
                        'adminusername' => '',
                        'adminpassword' => '',
                        'realprimarygroup' => true,
                        'usessl' => false,
                        'usetls' => false,
                        'recursivegroups' => true,
                        'port' => 389,
                        'sso' => false,
                ),
                'graphviz'=>array(
                        'class'=>'application.extensions.visualization.GraphViz',
                        'tmpdir'=>'assets/graph',
                        'sfdpCommand'=>'sfdp',
                        'sfdpConfig'=>array(
                                'overlap'=>'false',
                                //'splines'=>'true',
                                'fontsize'=>'8',
                                'fontcolor'=>'#999999',
                                'label'=>'znx2011 - sfdp',
                        ),
                        'fdpCommand'=>'fdp',
                        'fdpConfig'=>array(
                                'overlap'=>'false',
                                //'splines'=>'true',
                                'fontsize'=>'8',
                                'fontcolor'=>'#999999',
                                'label'=>'znx2011 - fdp',
                        ),
                        'neatoCommand'=>'neato',
                        'neatoConfig'=>array(
                                'overlap'=>'false',
                                'splines'=>'true',
                                'dim'=>'3',
                                'domen'=>'3',
                                'fontsize'=>'8',
                                'fontcolor'=>'#999999',
                                'label'=>'znx2011 - neato',
                        ),
                        'dotCommand'=>'dot',
                        'dotConfig'=>array(
                                'concentrate'=>'false',
                                'splines'=>'true',
                                'ratio'=>'auto',
                                'clusterrank'=>'global',
                                'compound'=>'true',
                                'ranksep'=>'0.5',
                                'nodesep'=>'0.1',
                                'rankdir'=>'TB',
                                'fontsize'=>'8',
                                'fontcolor'=>'#999999',
                                'label'=>'znx2011 - dot',
                        ),
                ),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
                                // REST patterns
                                array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
                                array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
                                array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
                                array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
                                array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
                                // REST patterns end
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'cache'=>array(
			'class'=>'system.caching.CMemCache',
			'servers'=>array(
				array('host'=>'127.0.0.1', 'port'=>11211, 'weight'=>100),
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;port=3306;dbname=servicematrix',
			'emulatePrepare' => true,
			'username' => 'servicematrix',
			'password' => '',
			'charset' => 'utf8',
			'schemaCachingDuration'=>3600,
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'admin@tld.com',
		'nodeImages'=>'images/nodes/',
		'ldapauth'=>false,
	),
);
