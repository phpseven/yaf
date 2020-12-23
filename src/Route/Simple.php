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
namespace Yaf\Route ;

/**
 *   \Yaf\Route\Simple  will match the query string, and find the route info.
 
 *  all you need to do is tell  \Yaf\Route\Simple  what key in the $_GET is module, what key is controller, and what key is action.
 
 *  \Yaf\Route\Simple::route() will always return TRUE, so it is important put  \Yaf\Route\Simple  in the front of the Route stack, otherwise all the other routes will not be called
 *
 * @link http://www.php.net/manual/en/class.yaf-route-simple.php
 */
final class Simple implements \Yaf\Route_Interface {

	/**
	 * @var string
	 */
	protected $controller;
	/**
	 * @var string
	 */
	protected $module;
	/**
	 * @var string
	 */
	protected $action;

	/**
	 *  \Yaf\Route\Simple will get route info from query string. and the parameters of this constructor will used as keys while searching for the route info in $_GET.
	 *
	 * @link http://www.php.net/manual/en/yaf-route-simple.construct.php
	 *
	 * @param string $module_name
	 * @param string $controller_name
	 * @param string $action_name
	 *
	 * @throws \Yaf\Exception\TypeError
	 */
	public function __construct($module_name, $controller_name, $action_name){ }

	/**
	 *  see \Yaf\Route\Simple::__construct()
	 *
	 * @link http://www.php.net/manual/en/yaf-route-simple.route.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 *
	 * @return bool always TRUE
	 */
	public function route(\Yaf\Request_Abstract &$request){ }

	/**
	 *   \Yaf\Route\Simple::assemble()  - Assemble a url
	 *
	 * @link http://www.php.net/manual/en/yaf-route-simple.assemble.php
	 *
	 * @param array $info
	 * @param array $query
	 * @return bool
	 */
	public function assemble(array $info, array $query = null){ }
}