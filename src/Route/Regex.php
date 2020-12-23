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
 *   \Yaf\Route\Regex  is the most flexible route among the Yaf built-in routes.
 *
 * @link http://www.php.net/manual/en/class.yaf-route-regex.php
 */
final class Regex extends \Yaf\Router implements \Yaf\Route_Interface {

	/**
	 * @var string
	 */
	protected $_route;
	/**
	 * @var array
	 */
	protected $_default;
	/**
	 * @var array
	 */
	protected $_maps;
	/**
	 * @var array
	 */
	protected $_verify;
	/**
	 * @var string
	 */
	protected $_reverse;

	/**
	 * @link http://www.php.net/manual/en/yaf-route-regex.construct.php
	 *
	 * @param string $match A complete Regex pattern, will be used to match a request uri, if doesn't matched, \Yaf\Route\Regex will return FALSE.
	 * @param array $route  When the match pattern matches the request uri, \Yaf\Route\Regex will use this to decide which m/c/a to routed.
	 
	 *  either of m/c/a in this array is optional, if you don't assign a specific value, it will be routed to default.
	 * @param array $map A array to assign name to the captures in the match result.
	 * @param array $verify
	 * @param string $reverse
	 *
	 * @throws \Yaf\Exception\TypeError
	 */
	public function __construct($match, array $route, array $map = null, array $verify = null, $reverse = null){ }

	/**
	 * Route a incoming request.
	 *
	 * @link http://www.php.net/manual/en/yaf-route-regex.route.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 *
	 * @return bool If the pattern given by the first parameter of \Yaf\Route\Regex::_construct() matches the request uri, return TRUE, otherwise return FALSE.
	 */
	public function route(\Yaf\Request_Abstract &$request){ }

	/**
	 *   \Yaf\Route\Regex::assemble()  - Assemble a url
	 *
	 * @link http://www.php.net/manual/en/yaf-route-regex.assemble.php
	 *
	 * @param array $info
	 * @param array $query
	 * @return bool
	 */
	public function assemble(array $info, array $query = null){ }
}