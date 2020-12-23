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
 *  \Yaf\Request\Simple  is particularly used for test purpose. ie. simulate a spacial request under CLI mode.
 * @link http://www.php.net/manual/en/class.yaf-request-simple.php
 */
class Simple extends \Yaf\Request_Abstract {

	/**
	 * Retrieve $_GET variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-simple.getquery.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param string $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getQuery($name = null, $default = null){ }

	/**
	 * Retrieve $_REQUEST variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-simple.getrequest.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param string $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getRequest($name = null, $default = null){ }

	/**
	 * Retrieve $_POST variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-simple.getpost.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param string $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getPost($name = null, $default = null){ }

	/**
	 * Retrieve $_Cookie variable
	 *
	 * @link http://www.php.net/manual/en/yaf-request-simple.getcookie.php
	 *
	 * @param string $name the variable name, if not provided returns all
	 * @param string $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function getCookie($name = null, $default = null){ }

	/**
	 * @param mixed $name
	 * @param null $default
	 *
	 * @return array
	 */
	public function getFiles($name = null, $default = null){ }

	/**
	 * Retrieve variable from client, this method will search the name in $_REQUEST params, if the name is not found, then will search in $_POST, $_GET, $_COOKIE, $_SERVER
	 *
	 * @link http://www.php.net/manual/en/yaf-request-simple.get.php
	 *
	 * @param string $name the variable name
	 * @param string $default if this parameter is provide, this will be returned if the variable can not be found
	 *
	 * @return mixed
	 */
	public function get($name, $default = null){ }

	/**
	 * Check the request whether it is a Ajax Request
	 *
	 
	 *  Note: 
	 *  
	 * This method depends on the request header: HTTP_X_REQUESTED_WITH, some Javascript library doesn't set this header while doing Ajax request
	 * 
	 * @link http://www.php.net/manual/en/yaf-request-simple.isxmlhttprequest.php
	 *
	 * @return bool
	 */
	public function isXmlHttpRequest(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-request-simple.construct.php
	 *
	 * @param string $method
	 * @param string $controller
	 * @param string $action
	 * @param string $params
	 *
	 * @throws \Yaf\Exception\TypeError
	 */
	public function __construct($method = null, $module = null, $controller = null, $action = null, $params = null){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-request-simple.clone.php
	 */
	private function __clone(){ }
}
