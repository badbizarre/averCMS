<?php

$config = array (
  'env' => Array (
    'timezone' => 'Europe/Minsk',
    'doc_encoding' => 'utf-8',
    'session_auto_start' => true,
    'cache' => false,
  ),
  'components' => Array (
    'log' => Array (
      'display_errors' => false,
      'email_notify' => Array (
        'enable' => false,
        'email' => '',
      ),
      'logging' => Array (
        'enable' => true,
        'log_file' => Array (
          'path' => 'system/errors.log',
          'max_size' => '1m',
        ),
      ),
    ),
    'db' => Array (
      'db_host' => 'localhost',
      'db_name' => 'povarenok',
      'db_user' => 'root',
      'db_pass' => '',
      'db_charset' => 'utf8',
    ),	
    'load' => Array (
      'dirs' => Array (
        'components' => 'system/components/',
        'controllers' => 'system/controllers/',
        'models' => 'system/models/',
        'library' => 'system/library/',
      ),
      'autoload' => Array (
        'library' => Array (
		  0 => 'functions.php',
		  1 => 'admin.php',
		  2 => 'Pagination.php',		  
		  3 => 'oauthvk.php',		  
		  4 => 'user.php',		  
		  5 => 'html.php',		  	  
        ),
        'components' => Array (
          0 => 'Config',
          1 => 'DB',
          2 => 'URL',
          3 => 'Render',
          4 => 'UI',
        ),
        'models' => Array (
		  0 => 'Database',
		  1 => 'Pages',
		  2 => 'Friends',
		  3 => 'Users',
        ),
        'controller' => 'main',
      ),
    ),
    'url' => Array (
      'permitted_url_chars' => 'a-z0-9&~%?.:+=_\-/',
      'routes' => Array (
        '/^$/' => 'index',
        '/^adminpanel$/' => 'adminpanel/adminpanel',
        '/^main$/' => 'page_404',
        '/^page$/' => 'page_404',
        '/^recept\/(.+)$/' => 'recept/default/$1',			
        '/^recepty\/search$/' => 'recepty/search',			
        '/^recepty\/update_html$/' => 'recepty/update_html',			
        '/^recepty\/update_comment$/' => 'recepty/update_comment',					
        '/^recepty\/edit_form$/' => 'recepty/edit_form',			
        '/^recepty\/add_recept$/' => 'recepty/add_recept',			
        '/^recepty\/save$/' => 'recepty/save',			
		'/^recepty\/(\d+)$/' => 'recepty/default/$1',
        '/^recepty\/(.+)$/' => 'recepty/detailed/$1',			
		'/^ingredients\/(\d+)$/' => 'ingredients/default/$1',
        '/^ingredients\/(.+)$/' => 'ingredients/detailed/$1',		
        '/^article\/update_html$/' => 'article/update_html',			
        '/^article\/update_comment$/' => 'article/update_comment',					
        '/^article\/edit_form$/' => 'article/edit_form',		
		'/^article\/(\d+)$/' => 'article/default/$1',
        '/^article\/(.+)$/' => 'article/detailed/$1',		
		'/^users\/(\d+)$/' => 'users/default/$1',
        '/^users\/(.+)$/' => 'users/detailed/$1',		
      ),
    ),
    'render' => Array (
      'dirs' => Array (
        'letters' => 'system/templates/letters/',
        'views' => 'system/templates/views/',
        'layouts' => 'system/templates/layouts/',
      ),
    ),
  ),
  'modules' => Array (		    
	'pages' => Array (
      'table' => 'np_pages',
    ),	
	'adminusers' => Array (
      'table' => 'np_adminusers',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/adminusers/small/',
          'width' => 200,
          'height' => 200,
        ),
        'big' => Array (
          'path' => '/assets/images/adminusers/big/',
          'width' => 1200,
        ),
	  ),		
    ),	
	'navigation' => Array (
      'table' => 'np_navigation',
    ),	
	'catalog' => Array (
      'table' => 'np_catalog',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/catalog/small/',
          'width' => 500,
          'height' => 230,
        ),
        'big' => Array (
          'path' => '/assets/images/catalog/big/',
          'width' => 1200,
        ),
      ),
      'pagination' => Array (
        'rows_on_page' => 18,
        'link_by_side' => 3,
        'url_segment' => 3,
      ),	  
    ),		
	'catalog_tree' => Array (
      'table' => 'np_catalog_tree',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/catalog_tree/small/',
          'width' => 300,
          'height' => 300,
        ),
        'big' => Array (
          'path' => '/assets/images/catalog_tree/big/',
          'width' => 1200,
        ),
	  ),	  
    ),	
	'catalog_categories' => Array (
      'table' => 'np_catalog_categories',
    ),		
	'catalog_ingredients' => Array (
      'table' => 'np_catalog_ingredients',
    ),		
	'ingredients' => Array (
      'table' => 'np_ingredients',
    ),		
	'measures' => Array (
      'table' => 'np_measures',
    ),
	'article' => Array (
      'table' => 'np_article',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/article/small/',
          'width' => 500,
          'height' => 230,
        ),
        'big' => Array (
          'path' => '/assets/images/article/big/',
          'width' => 1200,
        ),
      ),
      'pagination' => Array (
        'rows_on_page' => 18,
        'link_by_side' => 3,
        'url_segment' => 3,
      ),	  
    ),	
	'article_tree' => Array (
      'table' => 'np_article_tree',
    ),			
	'article_categories' => Array (
      'table' => 'np_article_categories',
    ),
    'users' => Array (
      'table' => 'np_users',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/users/small/',
          'width' => 200,
          'height' => 200,
        ),
        'big' => Array (
          'path' => '/assets/images/users/big/',
          'width' => 1200,
        ),
      ),	  
      'cookie' => Array (
        'expire' => 31536000,
      ),
      'pagination' => Array (
        'rows_on_page' => 16,
        'link_by_side' => 3,
        'url_segment' => 3,
      ),	  
	  'letter' => 'registration',
    ),		
	'users_like' => Array (
      'table' => 'np_users_like',
    ),	
	'users_comment' => Array (
      'table' => 'np_users_comment',
    ),	
	'users_friends' => Array (
      'table' => 'np_users_friends',
    ),	
	'users_recept' => Array (
      'table' => 'np_users_recept',
    ),		
	'photos' => Array (
      'table' => 'np_photos',
      'image' => Array (
        'small' => Array (
          'path' => '/assets/images/photos/small/',
          'width' => 207,
          'height' => 207,
        ),
        'big' => Array (
          'path' => '/assets/images/photos/big/',
          'width' => 1200,
        ),
      ),
    ),		
	'photos_tree' => Array (
      'table' => 'np_photos_tree',
    ),
  ),
);