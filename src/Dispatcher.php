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
namespace Yaf ;

use ReflectionClass;
use ReflectionException;
use Yaf\Exception\LoadFailed\Action;
use Yaf\Exception\LoadFailed\Controller;
use Yaf\Exception\LoadFailed\View;
use Yaf\Exception\TypeError;
use Yaf\View\Simple;

/**
 *   \Yaf\Dispatcher  purpose is to initialize the request environment, route the incoming request, and then dispatch any discovered actions; it aggregates any responses and returns them when the process is complete.<br/>
 *   \Yaf\Dispatcher  also implements the Singleton pattern, meaning only a single instance of it may be available at any given time. This allows it to also act as a registry on which the other objects in the dispatch process may draw.
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
	protected $_plugins = [];
	/**
	 * @var bool
	 */
	protected $_auto_render = true;
	/**
	 * @var bool
	 * @deprecated response is Pass by reference, dispacher will always return action result 
	 */
	protected $_return_response = false;
	/**
	 * @var bool
	 * true： 调用display 方法，直接输出，那么将忽略Yaf_Dispatcher::$_return_response
	 * false：调用render，将数据写入response 的默认body
	 */
	protected $_instantly_flush = false;
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
	 * add by phpseven
	 * 记录当前dispatch次数（主要为了防止出现互相dispatch引起的死循环）
	 * @var int
	 */
	protected $_dispatcher_times = 0;


	/**
	 * @link http://www.php.net/manual/en/yaf-dispatcher.construct.php
	 */
	private function __construct(){ 

	}

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
	public function enableView(){ 
		$this->_auto_render = true;
	}

	/**
	 *  disable view engine, used in some app that user will output by himself<br/>
	 *  Note: 
	 *  you can simply return FALSE in a action to prevent the auto-rendering of that action
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.disableview.php
	 *
	 * @return bool
	 */
	public function disableView(){ 
		$this->_auto_render = false;
	}

	/**
	 * Initialize view and return it
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.initview.php
	 *
	 * @param string $keys_dir
	 * @param array $options
	 * @return \Yaf\View_Interface
	 */
	public function initView($templates_dir, array $options = []){ 
		$this->_view =  new Simple($templates_dir, $options);
		return $this->_view;
	}

	/**
	 * This method provides a solution for that if you want use a custom view engine instead of \Yaf\View\Simple
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.setview.php
	 *
	 * @param \Yaf\View_Interface $view A \Yaf\View_Interface instance
	 * @return \Yaf\Dispatcher
	 */
	public function setView(\Yaf\View_Interface $view){ 
		$this->_view = $view;
	}

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
	 *  Set error handler for Yaf. when application.dispatcher.throwException is off, Yaf will trigger catch-able error while unexpected errors occurred.<br/>
	 *  Thus, this error handler will be called while the error raise.
	 *	设置错误处理函数, 一般在appcation.throwException关闭的情况下, Yaf会在出错的时候触发错误, 这个时候, 如果设置了错误处理函数, 则会把控制交给错误处理函数处理.
	 * @link http://www.php.net/manual/en/yaf-dispatcher.seterrorhandler.php
	 *
	 * @param callable $callback 错误处理函数, 这个函数需要最少接受俩个参数: 
	 * 		错误代码($error_code)和错误信息($error_message), 
	 * 		可选的还可以接受三个参数: 错误文件($err_file), 错误行($err_line)和错误上下文($errcontext)
	 * @param int $error_types YAF_ERR_* constants mask
	 * 一般可放在Bootstrap中定义错误处理函数
	 * function myErrorHandler($errno, $errstr, $errfile, $errline)
	   {
			switch ($errno) {
			case YAF_ERR_NOTFOUND_CONTROLLER:
			case YAF_ERR_NOTFOUND_MODULE:
			case YAF_ERR_NOTFOUND_ACTION:
				header("Not Found");
			break;

			default:
				echo "Unknown error type: [$errno] $errstr<br />\n";
				break;
			}

			return true;
		}

		Yaf_Dispatcher::getInstance()->setErrorHandler("myErrorHandler");
	 * @return \Yaf\Dispatcher
	 */
	public function setErrorHandler(callable $callback, $error_types){ 
		if(is_callable($callback)) {
			$this->_error_handler = $callback;
			return $this;
		}
		throw new TypeError("callback is not callable");
	}

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
	 * @deprecated response is Pass by reference, dispacher will always return action result 
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function returnResponse($flag){ 
		ExceptionHandler::instance()->appendDebugMsg("response is Pass by reference, dispacher will always return action result");
	}

	/**
	 *  \Yaf\Dispatcher will render automatically after dispatches an incoming request, you can prevent the rendering by calling this method with $flag TRUE<br/>
	 *  Note: 
	 *  you can simply return FALSE in a action to prevent the auto-rendering of that action
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
	public function flushInstantly($flag){ 
		$this->_instantly_flush = $flag;
		return $this;
	}

	/**
	 * is instant flushing
	 * @return bool
	 */
	public function isFlushInstantly(){ 
		return $this->_instantly_flush;
	}

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
	 *  This method does the heavy work of the \Yaf\Dispatcher. It take a request object.<br/>
	 *  The dispatch process has three distinct events:
	 * <ul>
	 * <li>Routing</li>
	 * <li>Dispatching</li>
	 * <li>Response</li>
	 * </ul>
	 *  
	 * 
	 * Routing takes place exactly once, using the values in the request object when dispatch() is called. 
	 * Dispatching takes place in a loop; a request may either indicate multiple actions to dispatch, 
	 * or the controller or a plugin may reset the request object to force additional actions to dispatch(see \Yaf\Plugin_Abstract. 
	 * When all is done, the \Yaf\Dispatcher returns a response.
	 * route 只执行一次，
	 * 一次请求中，当控制调用了doDispatch或者调用了 forward， doDispatch会执行多次
	 * 全部执行完之后会返回$response
	 * 
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
	public function dispatch(\Yaf\Request_Abstract &$request, \Yaf\Response_Abstract &$response = null){ 

		$forward_limit = Application::app()->getConfig('yaf.forward_limit');
		if($forward_limit <=0) {
			$forward_limit = 5;
		}
		if($response === null) {
			//根据是否CLI，产生对应的response
			$response = $request->isCli()? new Response\Cli() :new Response\Http();
		}
		foreach($this->_plugins as $plugin) {
			$plugin->routerStartup($request, $response);
		}

		$this->_router = new Router();
		$this->_router->route($request );
		$request->setRouted(true);
		foreach($this->_plugins as $plugin) {
			$plugin->routerShutdown($request, $response);
		}

		
		foreach($this->_plugins as $plugin) {
			$plugin->dispatchLoopStartup($request, $response);
		}

		while(!$request->isDispatched() && $this->_dispatcher_times<$forward_limit ) {
			$request->setDispatched(true);
			$this->doDispatch($request, $response);
		}
		
		
		foreach($this->_plugins as $plugin) {
			$plugin->dispatchLoopShutdown($request, $response);
		}

		return $response;
	}

	/**
	 * 
	 * @param Request_Abstract $request 
	 * @param Response_Abstract $response 
	 * @return mixed 
	 * $this->_return_response === true  && $this->_instantly_flush !==true 返回response对象
	 * 否则，返回action return的数据
	 * @throws ReflectionException 
	 */
	public function doDispatch(\Yaf\Request_Abstract &$request, \Yaf\Response_Abstract &$response){ 
		$this->_dispatcher_times ++;

		foreach($this->_plugins as $plugin) {
			$plugin->preDispatch($request, $response);
		}

		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$params = $request->getParams();

		if(empty($controller)){
			$msg = "Controller Name [$controller] is empty string";
			throw new Controller($msg."\n TRACE:".print_r(debug_backtrace(), true));
		}
		// $controller = str_ireplace('index.php', 'index', $controller);
		//支持 Base_TestController 或者 Base\TestController
		$controller_class_name = ucfirst("{$controller}Controller");
		if(!preg_match("/^[a-z][a-z0-9_]+$/i",$controller_class_name )) {
			$msg = " $controller_class_name is not a vaild controller";
			throw new Controller($msg."\n TRACE:".print_r(debug_backtrace(), true));
		}
		try {
			$reflection = new ReflectionClass($controller_class_name);
			$controller_class_object = $reflection->newInstance($request, $response, $this->_view);
			$init_method = $reflection->getMethod('init');
			$init_methd_result = null;
			if($init_method) {
				$init_methd_result = $init_method->invoke($controller_class_object);
			}
			
		}catch(\ReflectionException $reflection_exception) {
			throw new Exception\LoadFailed\Controller("reflection exception:".$reflection_exception->getMessage(), $reflection_exception->getCode(), $reflection_exception );
		}catch(\Throwable $t) {
			throw $t;
		}
		/**
		 * https://github.com/laruence/yaf/issues/121
		 * 对 issues 121 如果init返回 false，不会执行后面的Action的内容，不过会执行hooks
		 */
		if($init_methd_result === false) {
			$action_result = $init_methd_result;
		}else  {
			if(!preg_match("/^[_a-z][a-z0-9_]+$/i",$action )) {
				$msg = "$controller_class_name $action is not a vaild controller action";
				throw new Action($msg."\n TRACE:".print_r(debug_backtrace(), true));
			}
			try{				
				$action_method = $reflection->getMethod($action . 'Action');	
				// $ob_content = ob_get_contents();
				// ob_end_clean();
				// ob_start();
				// if(!empty($ob_content)) {
				// 	\Yaf\ExceptionHandler::instance()->appendDebugMsg('start ob:'. $ob_content);
				// }
				$action_params = $action_method->getParameters();
				$action_args = [];
				if(!empty($action_params)) foreach($action_params as $action_param_key =>  $action_param) {
					$action_param_name = $action_param->getName();
					$default_value = null;
					if($action_param->isDefaultValueAvailable()) {
						$default_value = $action_param->getDefaultValue();
					}
					$action_args[$action_param_key] = isset($params[$action_param_name])?$params[$action_param_name]:$default_value;
				}
				/**
				 * Action 通过return 返回的数据
				 */
				$action_result = $action_method->invokeArgs($controller_class_object, $action_args);
			}catch(View $view_exception) {
				throw new View($view_exception->getMessage() ."\n TRACE:".$view_exception->getTraceAsString(), $view_exception->getCode(), $view_exception );  //. __FILE__.':'.__LINE__ ."\n".$view_exception->getTraceAsString()
			}catch(\ReflectionException $reflection_exception) {
				throw new Exception\LoadFailed\Action("reflection exception:".$reflection_exception->getMessage(), $reflection_exception->getCode(), $reflection_exception );
			}catch(\Throwable $t) {
				throw $t;
			}
			//自动渲染
			if($action_result ===true || $this->_auto_render  && $action_result ===null) {
				try {
					$get_view_method = $reflection->getMethod('getView');	
					$view_object = $get_view_method->invoke($controller_class_object);

					$view_reflection = new ReflectionClass($view_object);
					$view_method = $this->_instantly_flush=== true? 'display':'render';
					$display_method = $view_reflection->getMethod($view_method);
					$tpl_path =  str_replace('_', DIRECTORY_SEPARATOR, $controller).DIRECTORY_SEPARATOR.$action;
					$action_render_result = $display_method->invoke($view_object, $tpl_path, []);
					
					if($this->_instantly_flush !==true) {
						$response->setBody($action_render_result);
					}
					
				}catch(View $view_exception) {
					throw new View('View Error:'.$view_exception->getMessage()."\n TRACE:".$view_exception->getTraceAsString(), $view_exception->getCode(), $view_exception);  //. __FILE__.':'.__LINE__ ."\n".$view_exception->getTraceAsString()
				}catch(\ReflectionException $reflection_exception) {
					throw new Exception\LoadFailed\Action("reflection exception:".$reflection_exception->getMessage(), $reflection_exception->getCode(), $reflection_exception );
				}catch(\Throwable $t) {
					throw $t;
				}
				
				\Yaf\ExceptionHandler::instance()->appendDebugMsg('auto_render:'. $tpl_path);
			}else if(is_string($action_result)){			//string output 	
				if($this->_instantly_flush !==true) {
					$response->setBody($action_result);
				}else {
					echo $action_result;
				}
			}else if(is_array($action_result)){				//array defalut json output
				if($this->_instantly_flush !==true) {
					$response->setBody($action_result);
				}else {
					echo $action_result;
				}
			}else if($action_result instanceof Response_Abstract){				//array defalut json output
				if($this->_instantly_flush !==true) {
					$response = $action_result;
				}else {
					$action_result->response();
				}
			}else {																//default: _instantly_flush				
				$this->_instantly_flush = true;
				\Yaf\ExceptionHandler::instance()->appendDebugMsg('no auto_render:'. var_export($this->_auto_render, true). var_export($action_result, true));
				while (ob_get_level() > 0) {
					ob_end_flush();
				}
			}

		}
		
		foreach($this->_plugins as $plugin) {
			$plugin->postDispatch($request, $response);
		}

		return $action_result;
	}

	/**
	 *  Switch on/off exception throwing while unexpected error occurring. When this is on, Yaf will throwing exceptions instead of triggering catchable errors.<br/>
	 *  You can also use application.dispatcher.throwException to achieve the same purpose.
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.throwexception.php
	 *
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function throwException($flag){ 
		ExceptionHandler::instance()->throwException($flag);
		return $this;
	}

	/**
	 *  While the application.dispatcher.throwException is On(you can also calling to  \Yaf\Dispatcher::throwException(TRUE)  to enable it), Yaf will throw \Exception whe error occurs instead of trigger error.<br/>
	 *  then if you enable  \Yaf\Dispatcher::catchException() (also can enabled by set application.dispatcher.catchException), all uncaught \Exceptions will be caught by ErrorController::error if you have defined one.
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
	 *
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function catchException($flag){ 
		ExceptionHandler::instance()->catchException($flag);
		return $this;
	}

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