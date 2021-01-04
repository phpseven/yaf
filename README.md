# yaf

```
Yaf write by origin php
使用原生php重写的一个Yaf版本，
完整根据laruence大神的yaf思路，加上自己认识做出来的一个轻量级框架，
可用于将现有代码迁移更容易维护的原生PHP，兼容php8.0！

目前进度：
├── Application.php           已实现，其中的错误处理部分抽离出到 ExceptionHandler，此外throw_exception目前为固定触发，即所有错误必定会转换为exception
├── Action_Abstract.php       未实现（没找到使用场景）
├── Bootstrap_Abstract.php    已实现
├── Config                    
│   ├── Ini.php               已实现
│   └── Simple.php            未实现
├── Config_Abstract.php       已实现
├── Controller_Abstract.php   已实现
├── Dispatcher.php            已实现（现在控制的init方法可以返回false，让后面的action不再执行，并且不影响hook使用了）
├── Exception                 已实现
│   ├── DispatchFailed.php    已实现
│   ├── LoadFailed            已实现
│   │   ├── Action.php        已实现
│   │   ├── Controller.php    已实现
│   │   ├── Model.php         已实现
│   │   ├── Plugin.php        已实现
│   │   └── View.php          已实现
│   ├── LoadFailed.php        已实现
│   ├── RouterFailed.php      目前只有Route_Static，暂无此类异常抛出
│   ├── StartupError.php      已实现
│   └── TypeError.php         已实现
├── ExceptionHandler.php      【新增类】
├── Exception.php             已实现
├── Loader.php                已实现yaf的自身的自动加载以及model/controller/plugin的自动加载，并自动加载时module内的models/controller，以及module外的文件夹都会寻找
├── Plugin_Abstract.php       已实现
├── Registry.php              未实现（需要借助yac/redis时）
├── Request   
│   ├── Http.php              已实现
│   └── Simple.php            未具体测试
├── Request_Abstract.php      已实现
├── Response
│   ├── Cli.php               已实现
│   └── Http.php              未具体测试
├── Response_Abstract.php     已实现
├── Route                     route均在后续中实现
│   ├── Map.php
│   ├── Regex.php
│   ├── Rewrite.php
│   ├── Simple.php
│   └── Supervar.php
├── Route_Interface.php       已实现
├── Router.php                已实现
├── Route_Static.php          已实现
├── Session.php               已实现
├── View  
│   └── Simple.php            已实现
├── View_Interface.php        已实现
└── yaf.inc.php               脱离compose的框架加载方法

通过这些天摸索，我只想说

```
# laruence大神牛逼！


## 安装方法


- 1   composer require phpseven/yaf
- 2.1 添加 composer auto 到入库文件 index.php 
```
define('APPLICATION_ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR); 
require_once('path/to/vendor/autoload.php');

```

- 2.2 或者下载最新稳定版本，并引入yaf.inc.php
```
define('APPLICATION_ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR); 
require_once('path/to/vendor/yaf.inc.php');

```
以下是Yaf 原介绍

## Yaf - Yet Another Framework
PHP framework written in c and built as a PHP extension.

## Requirement
- PHP 7.0+  (master branch))
- PHP 5.2+  ([php5 branch](https://github.com/laruence/yaf/tree/php5))

## Install
### Install Yaf 
Yaf is a PECL extension, thus you can simply install it by:

```
$pecl install yaf
```
### Compile Yaf in Linux
```
$/path/to/phpize
$./configure --with-php-config=/path/to/php-config
$make && make install
```

## Document
Yaf manual could be found at: http://www.php.net/manual/en/book.yaf.php

## IRC
efnet.org #php.yaf

## For IDE
You could find a documented prototype script here: https://github.com/elad-yosifon/php-yaf-doc

## Tutorial

### layout
A classic application directory layout:

```
- .htaccess // Rewrite rules
+ public
  | - index.php // Application entry
  | + css
  | + js
  | + img
+ conf
  | - application.ini // Configure 
- application/
  - Bootstrap.php   // Bootstrap
  + controllers
     - Index.php // Default controller
  + views    
     |+ index   
        - index.phtml // View template for default controller
  + library // libraries
  + models  // Models
  + plugins // Plugins
```
### DocumentRoot
You should set `DocumentRoot` to `application/public`, thus only the public folder can be accessed by user

### index.php
`index.php` in the public directory is the only way in of the application, you should rewrite all request to it(you can use `.htaccess` in Apache+php mod) 

```php
<?php
define("APPLICATION_PATH",  dirname(dirname(__FILE__)));

$app  = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
$app->bootstrap() //call bootstrap methods defined in Bootstrap.php
    ->run();
```
### Rewrite rules

#### Apache

```conf
#.htaccess
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .* index.php
```

#### Nginx

```
server {
  listen ****;
  server_name  domain.com;
  root   document_root;
  index  index.php index.html index.htm;
 
  if (!-e $request_filename) {
    rewrite ^/(.*)  /index.php/$1 last;
  }
}
```

#### Lighttpd

```
$HTTP["host"] =~ "(www.)?domain.com$" {
  url.rewrite = (
     "^/(.+)/?$"  => "/index.php/$1",
  )
}
```

### application.ini
`application.ini` is the application config file
```ini
[product]
;CONSTANTS is supported
application.directory = APPLICATION_PATH "/application/" 
```
Alternatively, you can use a PHP array instead: 
```php
<?php
$config = array(
   "application" => array(
       "directory" => application_path . "/application/",
    ),
);

$app  = new yaf_application($config);
....
  
```
### default controller
In Yaf, the default controller is named `IndexController`:

```php
<?php
class IndexController extends Yaf_Controller_Abstract {
   // default action name
   public function indexAction() {  
        $this->getView()->content = "Hello World";
   }
}
?>
```

### view script
The view script for default controller and default action is in the application/views/index/index.phtml, Yaf provides a simple view engine called "Yaf_View_Simple", which support the view template written in PHP.

```php
<html>
 <head>
   <title>Hello World</title>
 </head>
 <body>
   <?php echo $content; ?>
 </body>
</html>
```

## Run the Application
  http://www.yourhostname.com/

## Alternative
You can generate the example above by using Yaf Code Generator:  https://github.com/laruence/php-yaf/tree/master/tools/cg
```
./yaf_cg -d output_directory [-a application_name] [--namespace]
```

## More
More info could be found at Yaf Manual: http://www.php.net/manual/en/book.yaf.php
