<?php
namespace Yaf ;

use ReflectionClass;
use Yaf\View\Simple;

/**
 * <p><b>\Yaf\Dispatcher</b> purpose is to initialize the request environment, route the incoming request, and then dispatch any discovered actions; it aggregates any responses and returns them when the process is complete.</p><br/>
 * <p><b>\Yaf\Dispatcher</b> also implements the Singleton pattern, meaning only a single instance of it may be available at any given time. This allows it to also act as a registry on which the other objects in the dispatch process may draw.</p>
 *
 * @link http://www.php.net/manual/en/class.yaf-dispatcher.php
 */
final class Dispatcher {

	/**
	 * @var \Yaf\Dispatcher
	 */
	protected static $_instance;
	/**
	 * @var \Yaf\Router
	 */
	protected $_router;
	/**
	 * @var \Yaf\View_Interface
	 */
	protected $_view;
	/**
	 * @var \Yaf\Request_Abstract
	 */
	protected $_request;
	/**
	 * @var \Yaf\Plugin_Abstract
	 */
	protected $_plugins;
	/**
	 * @var bool
	 */
	protected $_auto_render = true;
	/**
	 * @var string
	 */
	protected $_return_response = "";
	/**
	 * @var string
	 */
	protected $_instantly_flush = "";
	/**
	 * @var string
	 */
	protected $_default_module;
	/**
	 * @var string
	 */
	protected $_default_controller;
	/**
	 * @var string
	 */
	protected $_default_action;

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.construct.php
	 */
	private function __construct(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.clone.php
	 */
	private function __clone(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.sleep.php
	 */
	public function __sleep(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.wakeup.php
	 */
	public function __wakeup(){ }

	/**
	 * enable view rendering
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.enableview.php
	 *
	 * @return \Yaf\Dispatcher
	 */
	public function enableView(){ }

	/**
	 * <p>disable view engine, used in some app that user will output by himself</p><br/>
	 * <b>Note:</b>
	 * <p>you can simply return FALSE in a action to prevent the auto-rendering of that action</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.disableview.php
	 *
	 * @return bool
	 */
	public function disableView(){ }

	/**
	 * Initialize view and return it
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.initview.php
	 *
	 * @param string $keys_dir
	 * @param array $options
	 * @return \Yaf\View_Interface
	 */
	public function initView($templates_dir, array $options = null){ }

	/**
	 * This method provides a solution for that if you want use a custom view engine instead of \Yaf\View\Simple
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.setview.php
	 *
	 * @param \Yaf\View_Interface $view A \Yaf\View_Interface instance
	 * @return \Yaf\Dispatcher
	 */
	public function setView(\Yaf\View_Interface $view){ }

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.setrequest.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 * @return \Yaf\Dispatcher
	 */
	public function setRequest(\Yaf\Request_Abstract $request){ 
		$this->_request = $request;
	}

	/**
	 * Retrieve the \Yaf\Application instance. same as \Yaf\Application::app().
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.getapplication.php
	 * @return \Yaf\Application
	 */
	public function getApplication(){ 
		return \Yaf\Application::app();
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.getrouter.php
	 *
	 * @return \Yaf\Router
	 */
	public function getRouter(){ 
		return $this->_router;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.getrequest.php
	 *
	 * @return \Yaf\Request_Abstract
	 */
	public function getRequest(){ 
		return $this->_request;
	}

	/**
	 * 返回默认module 
	 * @return string 
	 * add by phpseven
	 */
	public function getDefaultModule(){
		return $this->_default_module;
	}
	/**
	 * 返回默认controller
	 * @return string 
	 * add by phpseven
	 */
	public function getDefaultController(){
		return $this->_default_controller;
	}
	/**
	 * 返回默认action
	 * @return string 
	 */
	public function getDefaultAction(){
		return $this->_default_action;
	}

	/**
	 * <p>Set error handler for Yaf. when application.dispatcher.throwException is off, Yaf will trigger catch-able error while unexpected errors occurred.</p><br/>
	 * <p>Thus, this error handler will be called while the error raise.</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.seterrorhandler.php
	 *
	 * @param callable $callback a callable callback
	 * @param int $error_types YAF_ERR_* constants mask
	 *
	 * @return \Yaf\Dispatcher
	 */
	public function setErrorHandler(callable $callback, $error_types){ }

	/**
	 * Change default module name
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.setdefaultmodule.php
	 *
	 * @param string $module
	 * @return \Yaf\Dispatcher
	 */
	public function setDefaultModule($module){ 
		$this->_default_module = $module;
	}

	/**
	 * Change default controller name
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.setdefaultcontroller.php
	 *
	 * @param string $controller
	 * @return \Yaf\Dispatcher
	 */
	public function setDefaultController($controller){ 
		$this->_default_controller = $controller;
	}

	/**
	 * Change default action name
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.setdefaultaction.php
	 *
	 * @param string $action
	 * @return \Yaf\Dispatcher
	 */
	public function setDefaultAction($action){ 
		$this->_default_action = $action;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.returnresponse.php
	 *
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function returnResponse($flag){ }

	/**
	 * <p>\Yaf\Dispatcher will render automatically after dispatches an incoming request, you can prevent the rendering by calling this method with $flag TRUE</p><br/>
	 * <b>Note:</b>
	 * <p>you can simply return FALSE in a action to prevent the auto-rendering of that action</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.autorender.php
	 *
	 * @param bool $flag since 2.2.0, if this parameter is not given, then the current state will be set
	 * @return \Yaf\Dispatcher
	 */
	public function autoRender($flag = null){ 
		$this->_auto_render = $flag;
	}

	/**
	 * Switch on/off the instant flushing
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.flushinstantly.php
	 *
	 * @param bool $flag since 2.2.0, if this parameter is not given, then the current state will be set
	 * @return \Yaf\Dispatcher
	 */
	public function flushInstantly($flag = null){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.getinstance.php
	 *
	 * @return \Yaf\Dispatcher
	 */
	public static function getInstance(){ 
		if(empty(self::$_instance)) {
			$instance = new self();
			self::$_instance = $instance;
		}
		return self::$_instance;
	}

	/**
	 * <p>This method does the heavy work of the \Yaf\Dispatcher. It take a request object.</p><br/>
	 * <p>The dispatch process has three distinct events:</p>
	 * <ul>
	 * <li>Routing</li>
	 * <li>Dispatching</li>
	 * <li>Response</li>
	 * </ul>
	 * <p>Routing takes place exactly once, using the values in the request object when dispatch() is called. Dispatching takes place in a loop; a request may either indicate multiple actions to dispatch, or the controller or a plugin may reset the request object to force additional actions to dispatch(see \Yaf\Plugin_Abstract. When all is done, the \Yaf\Dispatcher returns a response.</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.dispatch.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 *
	 * @throws \Yaf\Exception\TypeError
	 * @throws \Yaf\Exception\RouterFailed
	 * @throws \Yaf\Exception\DispatchFailed
	 * @throws \Yaf\Exception\LoadFailed
	 * @throws \Yaf\Exception\LoadFailed\Action
	 * @throws \Yaf\Exception\LoadFailed\Controller
	 *
	 * @return \Yaf\Response_Abstract
	 */
	public function dispatch(\Yaf\Request_Abstract $request){ 
		/// 初始化response
		$request_class = get_class($request);
		if( $request_class === 'Yaf\Request\Http') {
			$response = new Response\Http();
		}else {
			$response = new Response\Cli();
		}

		/// 路由
		foreach($this->_plugins as $plugin) {
			$plugin->routerStartup($request, $response);
		}

		$this->_router = new Router();
		$this->_router->route($request );
		
		foreach($this->_plugins as $plugin) {
			$plugin->routerShutdown($request, $response);
		}



		
		foreach($this->_plugins as $plugin) {
			$plugin->dispatchLoopStartup($request, $response);
		}
		
		foreach($this->_plugins as $plugin) {
			$plugin->preDispatch($request, $response);
		}

		/**
		 * dispatch
		 */
		$module = $request->getModuleName();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$params = $request->getParams();
		
		$directory_path =	Application::app()->getAppDirectory();
		$default_module = $this->getDefaultModule();
		if($module === $default_module) {
			$view_dir = $directory_path.'views';
		}else {				
			$view_dir = $directory_path. DIRECTORY_SEPARATOR.'modules'. DIRECTORY_SEPARATOR.ucfirst($module). DIRECTORY_SEPARATOR.'views';
		}
		$view = new Simple($view_dir);

		
		$controller_class_name = ucfirst("{$controller}Controller");
		$reflection = new ReflectionClass($controller_class_name);
		$controller_class_object = $reflection->newInstance($request, $response, $view);
		$init_method = $reflection->getMethod( 'init');
		if($init_method) {
			$init_method->invoke($controller_class_object);
		}
		$action_method = $reflection->getMethod($action . 'Action');
		
		$ob_content = ob_get_contents();
		ob_end_clean();
		if(!empty($ob_content)) {
			\Core::log('start ob:'. $ob_content);
		}
		$error_msg = Application::app()->getLastErrorMsg();
		if(!empty($error_msg)) {
			\Core::log('Application error_msg:'. $ob_content);
		}
		$action_result = $action_method->invoke($controller_class_object);
		if($this->_auto_render  && $action_result !== false) {
			$view_reflection = new ReflectionClass($view);
			$display_method = $view_reflection->getMethod('display');
			$tpl_path =  str_replace('_', DIRECTORY_SEPARATOR, $controller).DIRECTORY_SEPARATOR.$action . '.tpl';
			$display_method->invoke($view, $tpl_path);
			\Core::log('auto_render:'. $tpl_path);
		}else {
			\Core::log('no auto_render:'. var_export($this->_auto_render, true). var_export($action_result, true));
			ob_end_flush();
		}
		
		
		foreach($this->_plugins as $plugin) {
			$plugin->postDispatch($request, $response);
		}
		
		foreach($this->_plugins as $plugin) {
			$plugin->dispatchLoopShutdown($request, $response);
		}

		if($this->_return_response === true) {
			return $response;
		}

		$response->response();
	}

	/**
	 * <p>Switch on/off exception throwing while unexpected error occurring. When this is on, Yaf will throwing exceptions instead of triggering catchable errors.</p><br/>
	 * <p>You can also use application.dispatcher.throwException to achieve the same purpose.</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.throwexception.php
	 *
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function throwException($flag = null){ }

	/**
	 * <p>While the application.dispatcher.throwException is On(you can also calling to <b>\Yaf\Dispatcher::throwException(TRUE)</b> to enable it), Yaf will throw \Exception whe error occurs instead of trigger error.</p><br/>
	 * <p>then if you enable <b>\Yaf\Dispatcher::catchException()</b>(also can enabled by set application.dispatcher.catchException), all uncaught \Exceptions will be caught by ErrorController::error if you have defined one.</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
	 *
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function catchException($flag = null){ }

	/**
	 * Register a plugin(see \Yaf\Plugin_Abstract). Generally, we register plugins in Bootstrap(see \Yaf\Bootstrap_Abstract).
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.registerplugin.php
	 *
	 * @param \Yaf\Plugin_Abstract $plugin
	 * @return \Yaf\Dispatcher
	 */
	public function registerPlugin(\Yaf\Plugin_Abstract $plugin){ 
		$this->_plugins[] = $plugin;
		return $this;
	}
}