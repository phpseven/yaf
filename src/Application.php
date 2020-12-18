<?php
namespace Yaf;

use Bootstrap;
use ReflectionMethod;
use Yaf\Exception\LoadFailed;
use Yaf\Exception\StartupError;

/**
 * \Yaf\Application provides a bootstrapping facility for applications which provides reusable resources, common- and module-based bootstrap classes and dependency checking.
 * <br/>
 * <b>Note:</b>
 * <p>
 * \Yaf\Application implements the singleton pattern, and \Yaf\Application can not be serialized or un-serialized which will cause problem when you try to use PHPUnit to write some test case for Yaf.<br/>
 * You may use &#64;backupGlobals annotation of PHPUnit to control the backup and restore operations for global variables. thus can solve this problem.
 * </p>
 * @link http://www.php.net/manual/en/class.yaf-application.php
 */

final class Application {

	/**
	 * @var \Yaf\Application
	 * 单例
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
	 * @var array
	 * 允许操作的modules
	 */
	protected $_modules;
	/**
	 * @var string  未找到相关调用点
	 */
	protected $_running = "";
	/**
	 * @var string 
	 * 默认product
	 */
	protected $_environ = 'product';
	/**
	 * @since 2.1.2
	 * @var int
	 */
	protected $_err_no = 0;
	/**
	 * @since 2.1.2
	 * @var string
	 */
	protected $_err_msg = "";

	/**
	 * add by phpseven
	 * @var \Yaf\Loader
	 */
	private $__loader = null;

	private $__version = '0.2';

	private $__app_directory = '';

	/**
	 * @link http://www.php.net/manual/en/yaf-application.construct.php
	 *
	 * @throws \Yaf\Exception\TypeError|\Yaf\Exception\StartupError
	 */
	public function __construct($config, $envrion = null){
		ob_start();			
		$this->_environ =  $envrion!==null ? $envrion : getenv('envType');
		define("YAF\ENVIRON", $this->_environ);
		define("YAF\VERSION", $this->__version);
		$this->config = new \Yaf\Config\Ini($config);
		
		self::$_app = $this;
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
		$this->__loader = Loader::getInstance($library_path);



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
		$bootstrap = new Bootstrap();		
		$reflection = new \ReflectionClass($bootstrap);
		$methods_public = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
		if(!empty($methods_public)) foreach($methods_public as  $method) {
			$method->invoke($bootstrap, $this->dispatcher);
		}
		return $this;
	}


	/**
	 * @link http://www.php.net/manual/en/yaf-application.getconfig.php
	 *
	 * @return \Yaf\Config_Abstract
	 */
	public function getConfig($name = null){ 
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
	 * @since 2.1.2
	 * @link http://www.php.net/manual/en/yaf-application.getlasterrorno.php
	 *
	 * @return int
	 */
	public function getLastErrorNo(){ 
		return $this->_err_no;
	}

	/**
	 * @since 2.1.2
	 * @link http://www.php.net/manual/en/yaf-application.getlasterrormsg.php
	 *
	 * @return string
	 */
	public function getLastErrorMsg(){ 
		return $this->_err_msg;
	}
	
	/**
	 * add by phpseven
	 * @param string $msg 
	 * @return string 
	 */
	public function appendErrorMsg(string $msg){ 
		$this->_err_msg .= $msg . "\n";
		return $this->_err_msg;
	}

	/**
	 *
	 * @since 2.1.2
	 * @link http://www.php.net/manual/en/yaf-application.clearlasterror.php
	 */
	public function clearLastError(){ 
		$this->_err_msg = '';
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