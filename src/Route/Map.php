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
 *   \Yaf\Route\Map  is a built-in route, it simply convert a URI endpoint (that part of the URI which comes after the base URI: see \Yaf\Request_Abstract::setBaseUri()) to a controller name or action name(depends on the parameter passed to \Yaf\Route\Map::__construct()) in following rule: A => controller A. A/B/C => controller A_B_C. A/B/C/D/E => controller A_B_C_D_E.
 
 *  If the second parameter of \Yaf\Route\Map::__construct() is specified, then only the part before delimiter of URI will used to routing, the part after it is used to routing request parameters (see the example section of \Yaf\Route\Map::__construct()).
 *
 * @link http://www.php.net/manual/en/class.yaf-route-map.php
 */
final class Map implements \Yaf\Route_Interface {

	/**
	 * @var string
	 */
	protected $_ctl_router = '';
	/**
	 * @var string
	 */
	protected $_delimiter;

	/**
	 * @link http://www.php.net/manual/en/yaf-route-map.construct.php
	 *
	 * @param bool $controller_prefer Whether the result should considering as controller or action
	 * @param string $delimiter
	 */
	public function __construct($controller_prefer = false, $delimiter = ''){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-route-map.route.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 *
	 * @return bool
	 */
	public function route(\Yaf\Request_Abstract &$request){ }

	/**
	 *   \Yaf\Route\Map::assemble()  - Assemble a url
	 *
	 * @link http://www.php.net/manual/en/yaf-route-map.assemble.php
	 *
	 * @param array $info
	 * @param array $query
	 * @return bool
	 */
	public function assemble(array $info, array $query = null){ }
}