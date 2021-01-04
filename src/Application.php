<?php
/**
  *----------------------------------------------------------------------------------------------------------
  * @attention Apache2.0 LICENSE
  * Copyright [YAFPlus] [phpseven]
  * 
  * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in 
  * compliance with the License.You may obtain a copy of the License at
  * http://www.apache.org/licenses/LICENSE-2.0
  * 
  * Unless required by applicable law or agreed to in writing, software distributed under the License is 
  * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
  * See the License for the specific language governing permissions and limitations under the License.
  *----------------------------------------------------------------------------------------------------------
  *  This product includes PHP software, freely available from
  *  <http://www.php.net/software/>
  *  This product Development Get ideas from Yet Another Framework, freely available from
  *  <https://github.com/laruence/yaf>
  *----------------------------------------------------------------------------------------------------------
  *  Author: phpseven  <phpseven@php.net>    
  *----------------------------------------------------------------------------------------------------------
  */
namespace Yaf;

use Bootstrap;
use ReflectionMethod;
use Yaf\Config\Ini;
use Yaf\Config\Simple;
use Yaf\Exception\LoadFailed;
use Yaf\Exception\StartupError;
use Yaf\Exception\TypeError;

/**
 * \Yaf\Application provides a bootstrapping facility for applications which provides reusable resources, common- and module-based bootstrap classes and dependency checking.
 * Yaf_Application代表一个产品/项目, 它负责接收请求, 协调路由, 分发, 执行, 输出, 单例模式。
 *  Note: 
 *  
 * \Yaf\Application implements the singleton pattern, and \Yaf\Application can not be serialized or un-serialized which will cause problem when you try to use PHPUnit to write some test case for Yaf.<br/>
 * You may use  backupGlobals annotation of PHPUnit to control the backup and restore operations for global variables. thus can solve this problem.
 * 
 * @link http://www.php.net/manual/en/class.yaf-application.php
 */

final class Application {

	/**
	 * @var \Yaf\Application
	 * 单例的实例
	 */
	protected static $_app;
	/**
	 * @var \Yaf\Config_Abstract
	 * 全局配置
	 */
	protected $config;
	/**
	 * @var \Yaf\Dispatcher
	 * 调度器
	 */
	protected $dispatcher;
/**
	 * @var string[]
	 * 允许操作的modules
	 */
	protected $_modules;
	/**
	 * @var false  
	 * @deprecated not used
	 */
	protected $_running = false;
	/**
	 * @var string 
	 * 环境：在构造函数传入，或者从系统环境变量 evnType 获取
	 * 默认 product
	 */
	protected $_environ = 'product';

	/**
	 * Loader变量
	 * @author phpseven
	 * @var \Yaf\Loader
	 */
	private $__loader = null;

	/**
	 * yafphp版本号
	 * @var string
	 */
	private $__version = '0.5';

	/**
	 * 本系统的根目录
	 * @var string
	 */
	private $__app_directory = '';


	/**
	 * @link http://www.php.net/classes/Yaf-Application.html#method___construct
	 *
	 * @throws \Yaf\Exception\TypeError|\Yaf\Exception\StartupError
	 */
	public function __construct($config, $envrion = null){
		// ob_start();			
		$this->_environ =  $envrion!==null ? $envrion : getenv('envType');
		$this->__initConst();
		$excetion_hanler = ExceptionHandler::instance();	
		$this->__loader = Loader::getInstance();

		self::$_app = $this;
		if(is_string($config)) {
			$this->config = new Ini($config, $this->_environ);
		}else if(is_array($config)) {
			$this->config = new Simple($config, $this->_environ);
		}else {
			throw new  TypeError("config type error". var_export($config, true));
		}
	}

	protected function __initConst() {		
		if(!defined('YAF_VERSION')) {
			define("YAF\ENVIRON", $this->_environ);
			define("YAF_ENVIRON", \YAF\ENVIRON);
			define("YAF\VERSION", $this->__version);
			define("YAF_VERSION", \YAF\VERSION);
		}
	}


	/**
	 * 
	 * @param callable $callback 
	 * @return bool
	 */
	public function callFunction(callable $callback, array $args){
		$is_handled = false;
		if(is_array($callback) && isset($callback[0]) &&  isset($callback[1]) ) {
			$reflection_method = new \ReflectionMethod($callback[0], $callback[1]);
			$method_caller = null;
			if(is_object($callback[0])) {
				$method_caller = $callback[0];
			}
			if($reflection_method) {
				$is_handled = $reflection_method->invokeArgs($method_caller,$args);
			}
		}else  if(is_callable($callback)){
			$reflection_function = new \ReflectionFunction($callback);
			if($reflection_function) {
				$is_handled = $reflection_function->invoke($args);
			}
		}
		return $is_handled;
	}


	/**
	 * Run a \Yaf\Application, let the \Yaf\Application accept a request, and route the request, dispatch to controller/action, and render response.
	 * return response to client finally.
	 *
	 * @link http://www.php.net/manual/en/yaf-application.run.php
	 * @throws \Yaf\Exception\StartupError
	 */
	public function run(){
		$base_uri = $this->config->get('application.baseUri');
		$request = new Request\Http(null,$base_uri);

		$response = new Response\Http();
		$this->getDispatcher()->dispatch($request, $response);		
		if(!$this->getDispatcher()->isFlushInstantly()) {
			$response->response();
		}
	}

	/**
	 * This method is typically used to run \Yaf\Application in a crontab work.
	 * Make the crontab work can also use the autoloader and Bootstrap mechanism.
	 *
	 * @link http://www.php.net/manual/en/yaf-application.execute.php
	 * TODO: CLI 的场景
	 *
	 * @param callable $entry a valid callback
	 * @param string $_ parameters will pass to the callback
	 */
	public function execute(callable $entry, $_ = "..."){ }

	/**
	 * Retrieve the \Yaf\Application instance, alternatively, we also could use \Yaf\Dispatcher::getApplication().
	 *
	 * @link http://www.php.net/manual/en/yaf-application.app.php
	 *
	 * @return \Yaf\Application|NULL an \Yaf\Application instance, if no \Yaf\Application initialized before, NULL will be returned.
	 */
	public static function app(){
		return self::$_app;
	}

	/**
	 * Retrieve environ which was defined in yaf.environ which has a default value "product".
	 *
	 * @link http://www.php.net/manual/en/yaf-application.environ.php
	 *
	 * @return string
	 */
	public function environ(){ 
		return $this->_environ;
	}

	/**
	 * Run a Bootstrap, all the methods defined in the Bootstrap and named with prefix "_init" will be called according to their declaration order, if the parameter bootstrap is not supplied, Yaf will look for a Bootstrap under application.directory.
	 *
	 * @link http://www.php.net/manual/en/yaf-application.bootstrap.php
	 *
	 * @param \Yaf\Bootstrap_Abstract $bootstrap A \Yaf\Bootstrap_Abstract instance
	 * @return \Yaf\Application
	 */
	public function bootstrap(\Yaf\Bootstrap_Abstract $bootstrap = null){
		/// 1. Dispatcher Init
		$this->dispatcher = $this->getDispatcher();
		$default_module = $this->config->get('application.dispatcher.defaultModule');
		if(empty($default_module)) {
			$default_module = 'Index';
		}
		$this->dispatcher->setDefaultModule($default_module);
		$default_controller = $this->config->get('application.dispatcher.defaultController');
		if(empty($default_controller)) {
			$default_controller = 'Index';
		}
		$this->dispatcher->setDefaultController($default_controller);
		$default_action = $this->config->get('application.dispatcher.defaultAction');
		if(empty($default_action)) {
			$default_action = 'index';
		}
		$this->dispatcher->setDefaultAction($default_action);
		$modules_str = $this->config->get('application.modules');
		if(!empty($modules_str)) {
			$this->_modules = explode(',', $modules_str);
		}else {
			$this->_modules = [$default_module];
		}
		array_walk($this->_modules, function (&$var)
		{
			$var = strtolower(trim($var));
		});

		///	2. Loader
		$directory_path =	$this->config->get('application.directory');
		$this->setAppDirectory($directory_path);
		$library_path = $this->config->get('application.library');
		$this->__loader->initLibrary($library_path);



		///	3. Bootstrap
		$bootstrap_file =  	$this->config->get('application.bootstrap');
		if(!empty($bootstrap_file)){
			if(!file_exists($bootstrap_file)) {
				throw new LoadFailed("bootstrap_file {$bootstrap_file} is NOT EXISTS!");
			}
			if(!is_readable($bootstrap_file)) {
				throw new LoadFailed("bootstrap_file {$bootstrap_file} CAN NOT READ!");
			}			
			require_once($bootstrap_file);		
		}
		if(!class_exists('Bootstrap')) {
			throw new LoadFailed("bootstrap class is NOT EXISTS!");
		}
		try {
			$bootstrap = new Bootstrap();		
			$reflection = new \ReflectionClass($bootstrap);
			$methods_public = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
			if(!empty($methods_public)) foreach($methods_public as  $method) {
				$method->invoke($bootstrap, $this->dispatcher);
			}		
		}catch(\ReflectionException $reflection_exception) {
			throw new Exception\LoadFailed\Action("reflection exception:".$reflection_exception->getMessage(), $reflection_exception->getCode(), $reflection_exception );
		}catch(\Throwable $t) {
			throw $t;
		}
		return $this;
	}	


	/**
	 * @link http://www.php.net/manual/en/yaf-application.getconfig.php
	 *
	 * @return \Yaf\Config_Abstract
	 */
	public function getConfig($name = null){ 
		if(empty($this->config)){
			echo 'Config 类未初始化成功';
			exit();
		}
		if(!empty($name)) {
			return $this->config->get($name);
		}
		return $this->config;
	}
		
	/**
	 * Get the modules list defined in config, if no one defined, there will always be a module named "Index".
	 *
	 * @link http://www.php.net/manual/en/yaf-application.getmodules.php
	 *
	 * @return array
	 */
	public function getModules(){ 
		return $this->_modules;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-application.getdispatcher.php
	 *
	 * @return \Yaf\Dispatcher
	 */
	public function getDispatcher(){ 
		return Dispatcher::getInstance();
	}

	/**
	 * Change the application directory
	 *
	 * @since 2.1.4
	 * @link http://www.php.net/manual/en/yaf-application.setappdirectory.php
	 *
	 * @param string $directory
	 * @return \Yaf\Application
	 */
	public function setAppDirectory($directory){ 
		if(!file_exists($directory)) {
			throw new StartupError("setAppDirectory error: Directory is not exists" .$directory);
		}
		$this->__app_directory = $directory;
	}

	/**
	 * @since 2.1.4
	 * @link http://www.php.net/manual/en/yaf-application.getappdirectory.php
	 *
	 * @return string
	 */
	public function getAppDirectory(){ 
		if(empty($this->__app_directory)) {
			$this->__app_directory = APPLICATION_ROOT.'application'.DIRECTORY_SEPARATOR;
		}
		return $this->__app_directory;
	}

	/**
	 *  调用 ExceptionHandler::instance() 返回最后的错误码
	 * @since 2.1.2
	 * @link http://www.php.net/manual/en/yaf-application.getlasterrorno.php
	 * @deprecated move to  ExceptionHandler
	 *
	 * @return int
	 */
	public function getLastErrorNo(){
		return ExceptionHandler::instance()->getCode();
	}

	/**
	 *  调用 ExceptionHandler::instance() 返回最后的错误信息
	 * @since 2.1.2
	 * @link http://www.php.net/manual/en/yaf-application.getlasterrormsg.php
	 *
	 * @deprecated move to  ExceptionHandler
	 * @return string
	 */
	public function getLastErrorMsg(){ 
		return ExceptionHandler::instance()->getMessage();
	}
	

	
	/**
	 *  调用 ExceptionHandler::instance() 清空最后的错误信息
	 * @since 2.1.2
	 * @link http://www.php.net/manual/en/yaf-application.clearlasterror.php
	 * @deprecated move to  ExceptionHandler
	 */
	public function clearLastError(){ 
		ExceptionHandler::instance()->clearLastError();
	}

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-application.destruct.php
	 */
	public function __destruct(){ }

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-application.clone.php
	 */
	private function __clone(){ }

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-application.sleep.php
	 */
	public function __sleep(){ }

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-application.wakeup.php
	 */
	public function __wakeup(){ }
}