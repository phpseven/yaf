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
 * @link http://www.php.net/manual/en/class.yaf-route-supervar.php
 */
final class Supervar implements \Yaf\Route_Interface {

	/**
	 * @var string
	 */
	protected $_var_name;

	/**
	 *  \Yaf\Route\Supervar is similar to \Yaf\Route_Static, the difference is that \Yaf\Route\Supervar will look for path info in query string, and the parameter supervar_name is the key.
	 *
	 * @link http://www.php.net/manual/en/yaf-route-supervar.construct.php
	 *
	 * @param string $supervar_name The name of key.
	 *
	 * @throws \Yaf\Exception\TypeError
	 */
	public function __construct($supervar_name){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-route-supervar.route.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 *
	 * @return bool If there is a key(which was defined in \Yaf\Route\Supervar::__construct()) in $_GET, return TRUE. otherwise return FALSE.
	 */
	public function route(\Yaf\Request_Abstract &$request){ }

	/**
	 *   \Yaf\Route\Supervar::assemble()  - Assemble a url
	 *
	 * @link http://www.php.net/manual/en/yaf-route-supervar.assemble.php
	 *
	 * @param array $info
	 * @param array $query
	 * @return bool
	 */
	public function assemble(array $info, array $query = null){ }
}