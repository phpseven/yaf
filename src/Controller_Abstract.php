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

/**
 *   \Yaf\Controller_Abstract  is the heart of Yaf's system. MVC stands for Model-View-Controller and is a design pattern targeted at separating application logic from display logic.
 
 *  Every custom controller shall inherit  \Yaf\Controller_Abstract .
 
 *  You will find that you can not define __construct function for your custom controller, thus,  \Yaf\Controller_Abstract  provides a magic method: \Yaf\Controller_Abstract::init().
 
 *  If you have defined a init() method in your custom controller, it will be called as long as the controller was instantiated.
 
 *  Action may have arguments, when a request coming, if there are the same name variable in the request parameters(see \Yaf\Request_Abstract::getParam()) after routed, Yaf will pass them to the action method (see \Yaf\Action_Abstract::execute()).
 
 *  Note: 
 *  These arguments are directly fetched without filtering, it should be carefully processed before use them.
 *
 * @link http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
abstract class Controller_Abstract {

	/**
	 * @see \Yaf\Action_Abstract
	 * @var array You can also define a action method in a separate PHP script by using this property and \Yaf\Action_Abstract.
	 */
	public $actions;
	/**
	 * @var string module name
	 */
	protected $_module;
	/**
	 * @var string controller name
	 */
	protected $_name;
	/**
	 * @var \Yaf\Request\Http current request object
	 */
	protected $_request;
	/**
	 * @var \Yaf\Response_Abstract current response object
	 */
	protected $_response;
	/**
	 * @var array
	 */
	protected $_invoke_args;
	/**
	 * @var \Yaf\View_Interface view engine object
	 */
	protected $_view;

	/**
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.render.php
	 *
	 * @param string $tpl
	 * @param array $parameters
	 *
	 * @return string
	 */
	protected function render($tpl, array $parameters = []){ 
		return $this->_view->render($tpl, $parameters);
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.display.php
	 *
	 * @param string $tpl
	 * @param array $parameters
	 *
	 * @return bool
	 */
	protected function display($tpl, array $parameters = []){
		$controller_name = $this->_request->getControllerName();
		$tpl_dir = str_replace("_", DIRECTORY_SEPARATOR, strtolower($controller_name));
		$tpl = $tpl_dir . DIRECTORY_SEPARATOR.$tpl;
		return $this->_view->display($tpl, $parameters);
	}

	/**
	 * retrieve current request object
	 *
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.getrequest.php
	 *
	 * @return \Yaf\Request_Abstract
	 */
	public function getRequest(){ 
		return $this->_request;
	}

	/**
	 * retrieve current response object
	 *
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.getresponse.php
	 *
	 * @return \Yaf\Response_Abstract
	 */
	public function getResponse(){ 
		return $this->_response;
	}

	/**
	 * get the controller's module name
	 *
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.getmodulename.php
	 *
	 * @return string
	 */
	public function getModuleName(){ 
		return $this->_request->getModuleName();
	}

	/**
	 * retrieve view engine
	 *
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.getview.php
	 *
	 * @return \Yaf\View_Interface
	 */
	public function getView(){ 
		if(empty($this->_view)) {
			$module = $this->_request->getModuleName();
			$directory_path =	Application::app()->getAppDirectory();
			$default_module = Dispatcher::getInstance()->getDefaultModule();
			if(empty($module) ||$module === $default_module) {
				$view_dir = $directory_path.DIRECTORY_SEPARATOR.'views';
			}else {				
				$view_dir = $directory_path. DIRECTORY_SEPARATOR.'modules'. DIRECTORY_SEPARATOR.ucfirst($module). DIRECTORY_SEPARATOR.'views';
			}
			$this->_view = Dispatcher::getInstance()->initView($view_dir);
		}
		return $this->_view;
	}


	/**
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.setviewpath.php
	 *
	 * @param string $view_directory
	 *
	 * @return bool
	 */
	public function setViewpath($view_directory){
		$this->_view->setScriptPath($view_directory);
		return true;
	 }

	/**
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.getviewpath.php
	 *
	 * @return string
	 */
	public function getViewpath(){ 
		return $this->_view->getScriptPath();
	}

	/**
	 *  forward current execution process to other action.
	 
	 *  Note: 
	 *  this method doesn't switch to the destination action immediately, it will take place after current flow finish.
	 
	 *  Notice, there are 3 available method signatures: 
	 *  \Yaf\Controller_Abstract::forward ( string $module , string $controller , string $action [, array $parameters ] )
	 *  \Yaf\Controller_Abstract::forward ( string $controller , string $action [, array $parameters ] )
	 *  \Yaf\Controller_Abstract::forward ( string $action [, array $parameters ] )
	 *
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.forward.php
	 *
	 * @param string $module destination module name, if NULL was given, then default module name is assumed
	 * @param string $controller destination controller name
	 * @param string $action destination action name
	 * @param array $parameters calling arguments
	 *
	 * @return bool return FALSE on failure
	 */
	public function forward($module, $controller = null, $action = null, array $parameters = null){
		$this->_request->setModuleName($module);
		$this->_request->setControllerName($controller);
		$this->_request->setActionName($action);
		$this->_request->setParam($parameters);		
		$this->_request->setDispatched(false);
		return false;
	}

	/**
	 * redirect to a URL by sending a 302 header
	 * 重定向到一个url，发送302的header
	 * 
	 *
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.redirect.php
	 *
	 * @param string $url a location URL
	 *
	 * @return bool
	 */
	public function redirect($url){ 
		$this->_response->setRedirect($url);
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.getinvokeargs.php
	 *	TODO: 这个在c代码没找到相关调用，可用于保存cli的 argv
	 * @return array
	 */
	public function getInvokeArgs(){ 
		return $this->_invoke_args;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.getinvokearg.php
	 *	TODO: 这个在c代码没找到相关调用，可用于保存cli的 argv
	 * @param string $name
	 *
	 * @return null|mixed
	 */
	public function getInvokeArg($name){ 
		if(isset($this->_invoke_args[$name])){
			return $name;
		}
		return null;
	}

	/**
     * 此方法在c扩展中不存在，只用用于初始化，不要在控制器调用
	 *  \Yaf\Controller_Abstract::__construct() is final, which means users can not override it. but users can define  \Yaf\Controller_Abstract::init() , which will be called after controller object is instantiated.
	 *
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.init.php
	 *
	 */
	public function init(){ 
		
	}

	/**
	 *  \Yaf\Controller_Abstract ::__construct() is final, which means it can not be overridden. You may want to see \Yaf\Controller_Abstract::init() instead.
	 *
	 * @see \Yaf\Controller_Abstract::init()
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.construct.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 * @param \Yaf\Response_Abstract $response
	 * @param \Yaf\View_Interface $view
	 * @param array $invokeArgs  TODO: NOT USED dispatch 未传递此数据
	 */
	public function __construct(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response, \Yaf\View_Interface $view =null, array $invokeArgs = null){ 
		$this->_request = $request;
		$this->_response = $response;
		if(!empty($view)) {
			$this->_view = $view;
		}else {
			$this->_view = $this->getView();
		}
		$this->_invoke_args = $invokeArgs;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-controller-abstract.clone.php
	 */
	private function __clone(){ }
}