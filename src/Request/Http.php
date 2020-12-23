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
namespace Yaf\Request ;

/**
 * @link http://www.php.net/manual/en/class.yaf-request-http.php
 */
class Http extends \Yaf\Request_Abstract {

	/**
	 * Retrieve $_GET variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-http.getquery.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param mixed $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getQuery($name = null, $default = null){ 
		if(isset($name)){
			return isset($_GET[$name])?$_GET[$name]:$default;
		}
		return $_GET;
	}

	/**
	 * Retrieve $_REQUEST variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-http.getrequest.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param mixed $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getRequest($name = null, $default = null){ 
		
		if(isset($name)){
			return isset($_REQUEST[$name])?$_REQUEST[$name]:$default;
		}
		return $_REQUEST;
	}

	/**
	 * Retrieve $_POST variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-http.getpost.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param mixed $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getPost($name = null, $default = null){ 
		
		if(isset($name)){
			return isset($_POST[$name])?$_POST[$name]:$default;
		}
		return $_POST;
	}

	/**
	 * Retrieve $_COOKIE variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-http.getcookie.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param mixed $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getCookie($name = null, $default = null){ 
		
		if(isset($name)){
			return isset($_COOKIE[$name])?$_COOKIE[$name]:$default;
		}
		return $_COOKIE;
	}

	/**
	 * Retrieve $_FILES variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-http.getfiles.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param mixed $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getFiles($name = null, $default = null){ 
		
		if(isset($name)){
			return isset($_FILES[$name])?$_FILES[$name]:$default;
		}
		return $_FILES;
	}

	/**
	 * Retrieve variable from client, this method will search the name in $_REQUEST params, if the name is not found, then will search in $_POST, $_GET, $_COOKIE, $_SERVER
	 *
	 * @link http://www.php.net/manual/en/yaf-request-http.get.php
	 *
	 * @param string $name the variable name
	 * @param string $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function get($name, $default = null){ 
		$value = $this->getParam($name);
		if($value !==null){
			return $value;
		}
		$value = $this->getPost($name);
		if($value !==null){
			return $value;
		}
		$value = $this->getQuery($name);
		if($value !==null){
			return $value;
		}
		$value = $this->getCookie($name);
		if($value !==null){
			return $value;
		}
		$value = $this->getServer($name);
		if($value !==null){
			return $value;
		}
		return $default;
	}

	/**
	 * Check the request whether it is a Ajax Request
	 *
	 
	 *  Note: 
	 *  
	 * This method depends on the request header: HTTP_X_REQUESTED_WITH, some Javascript library doesn't set this header while doing Ajax request
	 * 
	 * @link http://www.php.net/manual/en/yaf-request-http.isxmlhttprequest.php
	 *
	 * @return bool
	 */
	public function isXmlHttpRequest(){ 
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			strcmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest') ===0) {
			return true;
		}
		return false;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-http.construct.php
	 *
	 * @param string $request_uri
	 * @param string $base_uri
	 *
	 */
	public function __construct($request_uri, $base_uri){ 
		$this->_base_uri = $base_uri;
		if(!empty($request_uri)){
			return;
		}
		
		/**
		 * 从$_SERVER获取 request_uri
		 * [WINDOWS: HTTP_X_REWRITE_URL =>  (如果 IIS_WasUrlRewritten=true) UNENCODED_URL ]
		 * PATH_INFO => REQUEST_URI.path => ORIG_PATH_INFO
		 */
		do {
			if(strtoupper(substr(PHP_OS,0,3))==='WIN') {
				if(isset($_SERVER['HTTP_X_REWRITE_URL'])  && is_string($_SERVER['HTTP_X_REWRITE_URL'])) {
					$request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
					break;
				}
				if(isset($_SERVER['IIS_WasUrlRewritten'])  && $_SERVER['IIS_WasUrlRewritten'] === true && 
					isset($_SERVER['UNENCODED_URL'])  && is_string($_SERVER['UNENCODED_URL'])) {
					$request_uri = $_SERVER['UNENCODED_URL'];
					break;
				}
			}
			if(isset($_SERVER['PATH_INFO'])  && is_string($_SERVER['PATH_INFO'])) {
				$request_uri = $_SERVER['PATH_INFO'];
				break;
			}
			if(isset($_SERVER['REQUEST_URI'])  && is_string($_SERVER['REQUEST_URI'])) {
				$url = $_SERVER['REQUEST_URI'];
				if(strpos($url, 'http') === 0) {
					$url_info = parse_url($url);
					if(!isset($url_info['path'])) {
						break;
					}
				}else {
					$request_uri = strpos($url, '?')!==false ? substr($url, 0, strpos($url, '?')) : $url;
					break;
				}
			}
			if(isset($_SERVER['ORIG_PATH_INFO'])  && is_string($_SERVER['ORIG_PATH_INFO'])) {
				$request_uri = $_SERVER['ORIG_PATH_INFO'];
				break;
			}
			
		}while(0);

		// if(empty($request_uri) || $request_uri =='/index.php'){
		// 	$request_uri = '/';
		// }
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
		// echo "action_result $request_uri \n";
		// exit;
		$this->uri = $request_uri;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-request-http.clone.php
	 */
	private function __clone(){ }
}