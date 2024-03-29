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
 * @link http://www.php.net/manual/en/class.yaf-request-abstract.php
 */
abstract class Request_Abstract {

	const SCHEME_HTTP  = 'http';
	const SCHEME_HTTPS = 'https';
	/**
	 * @var string
	 */
	public $module;
	/**
	 * @var string
	 */
	public $controller;
	/**
	 * @var string
	 */
	public $action;
	/**
	 * @var string
	 */
	public $method;
	/**
	 * @var array
	 */
	protected $params = [];
	/**
	 * @var string
	 */
	protected $language;
	/**
	 * @var \Yaf\Exception
	 */
	protected $_exception;
	/**
	 * @var string
	 */
	protected $_base_uri = "";
	/**
	 * @var string
	 */
	protected $uri = "";
	/**
	 * @var bool
	 */
	protected $dispatched = false;
	/**
	 * @var bool
	 */
	protected $routed = false;

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.isget.php
	 *
	 * @return bool
	 */
	public function isGet(){ 
		return strtolower($this->method) =='get';
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.ispost.php
	 *
	 * @return bool
	 */
	public function isPost(){
		return strtolower($this->method) =='post';
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.isput.php
	 *
	 * @return bool
	 */
	public function isPut(){ 
		return strtolower($this->method) =='put';
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.ishead.php
	 *
	 * @return bool
	 */
	public function isHead(){ 
		return strtolower($this->method) =='head';
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.isoptions.php
	 *
	 * @return bool
	 */
	public function isOptions(){ 
		return strtolower($this->method) =='options';
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.iscli.php
	 *
	 * @return bool
	 */
	public function isCli(){ 
		return self::staticIsCli();
	}

	public static function staticIsCli(){
        return PHP_SAPI === 'cli';
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.isdispached.php
	 *
	 * @return bool
	 */
	public function isDispatched(){ 
		return $this->dispatched;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.isrouted.php
	 *
	 * @return bool
	 */
	public function isRouted(){ 
		return $this->routed;
	}

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.isxmlhttprequest.php
	 *
	 * @return bool false
	 */
	public function isXmlHttpRequest(){ }

    /**
     * (Yaf >= 2.2.9)
     * 获取全局变量中的值（扫描顺序为$_POST，$_GET，$_COOKIE，$_SERVER）
     *
     * @param string $name 变量名
     * @param mixed $default 默认值
     *
     * @return mixed
     */
    public function get($name, $default = null){}

	/**
	 * Retrieve $_SERVER variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getserver.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param mixed $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getServer($name = null, $default = null){ }

	/**
	 * Retrieve $_ENV variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getenv.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param mixed $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getEnv($name = null, $default = null){ }

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getparam.php
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function getParam($name, $default = null){ 
		$value = isset($this->params[$name])?$this->params[$name]:$default;
		return $value;
	}

	/**
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getparams.php
	 *
	 * @return array
	 */
	public function getParams(){ 
		return $this->params;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getexception.php
	 *
	 * @return \Yaf\Exception
	 */
	public function getException(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getmoudlename.php
	 *
	 * @return string
	 */
	public function getModuleName(){ 
		return $this->module;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getcontrollername.php
	 *
	 * @return string
	 */
	public function getControllerName(){ 
		return $this->controller;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getactionname.php
	 *
	 * @return string
	 */
	public function getActionName(){ 
		return $this->action;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setparam.php
	 *
	 * @param string|array $name the variable name, or an array of key=>value pairs
	 * @param string $value
	 *
	 * @return \Yaf\Request_Abstract|bool
	 */
	public function setParam($name, $value = null){ 
		if(is_array($name) && !empty($name)) {
			$this->params = array_replace_recursive($this->params, $name);
		} 
		if(!empty($name) && $value !==null) {
			$this->params[$name] = $value;
		}
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setmodulename.php
	 *
	 * @param string $module
	 *
	 * @return \Yaf\Request_Abstract|bool
	 */
	public function setModuleName($module){ 
		$this->module = $module;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setcontrollername.php
	 *
	 * @param string $controller
	 *
	 * @return \Yaf\Request_Abstract|bool
	 */
	public function setControllerName($controller){ 
		$this->controller = $controller;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setactionname.php
	 *
	 * @param string $action
	 *
	 * @return \Yaf\Request_Abstract|bool
	 */
	public function setActionName($action){ 
		$this->action = $action;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getmethod.php
	 *
	 * @return string
	 */
	public function getMethod(){ 
		return $this->method;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getlanguage.php
	 *
	 * @return string
	 */
	public function getLanguage(){ }


    /**
     * (Yaf >= 2.2.9)
     * 获取$_REQUEST中名为$name的参数值
     *
     * @param string $name 变量名
     *
     * @return mixed
     */
    public function getRequest($name = null){}

    /**
     * (Yaf >= 2.2.9)
     * 获取$_POST中名为$name的参数值
     *
     * @param string $name 变量名
     *
     * @return mixed
     */
    public function getPost($name = null){}

    /**
     * (Yaf >= 2.2.9)
     * 获取$_COOKIE中名为$name的参数值
     *
     * @param string $name 变量名
     *
     * @return mixed
     */
    public function getCookie($name = null){}

    /**
     * (Yaf >= 2.2.9)
     * 获取$_FILES中名为$name的参数值
     *
     * @param string $name 变量名
     *
     * @return mixed
     */
    public function getFiles($name = null){}

    /**
	 *  Set base URI, base URI is used when doing routing, in routing phase request URI is used to route a request, while base URI is used to skip the leading part(base URI) of request URI. That is, if comes a request with request URI a/b/c, then if you set base URI to "a/b", only "/c" will be used in routing phase.
	 
	 *  Note: 
	 *  generally, you don't need to set this, Yaf will determine it automatically.
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setbaseuri.php
	 *
	 * @param string $uri base URI
	 *
	 * @return bool
	 */
	public function setBaseUri($uri){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getbaseuri.php
	 *
	 * @return string
	 */
	public function getBaseUri(){ 
		return $this->_base_uri;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-abstract.getrequesturi.php
	 *
	 * @return string
	 */
	public function getRequestUri(){ 
		return $this->uri;
	}

	/**
	 * @since 2.1.0
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setrequesturi.php
	 *
	 * @param string $uri request URI
	 */
	public function setRequestUri($uri){ 
		
	}

	/**
	 * Set request as dispatched
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setdispatched.php
	 *
	 * @return bool
	 */
	public function setDispatched(bool $flag){ 
		$this->dispatched = $flag;
		return $this;
	}

	/**
	 * Set request as routed
	 *
	 * @link http://www.php.net/manual/en/yaf-request-abstract.setrouted.php
	 *
	 * @return \Yaf\Request_Abstract
	 */
	public function setRouted(bool $flag){ 
		$this->routed = $flag;
		return $this;
	}
}