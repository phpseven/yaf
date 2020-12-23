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
 * TODO: 未实现，需要借助 yac/redis等系统
 *  All methods of  \Yaf\Registry  declared as static, making it universally accessible. This provides the ability to get or set any custom data from anyway in your code as necessary.
 * @link http://www.php.net/manual/en/class.yaf-registry.php
 */
final class Registry {

	/**
	 * @var \Yaf\Registry
	 */
	protected static $_instance;
	/**
	 * @var array
	 */
	protected $_entries;

	/**
	 * @link http://www.php.net/manual/en/yaf-registry.construct.php
	 */
	private function __construct(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-registry.clone.php
	 */
	private function __clone(){ }

	/**
	 * Retrieve an item from registry
	 *
	 * @link http://www.php.net/manual/en/yaf-registry.get.php
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public static function get($name){ }

	/**
	 * Check whether an item exists
	 *
	 * @link http://www.php.net/manual/en/yaf-registry.has.php
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public static function has($name){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-registry.set.php
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public static function set($name, $value){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-registry.del.php
	 *
	 * @param string $name
	 *
	 * @return void|bool
	 */
	public static function del($name){ }
}