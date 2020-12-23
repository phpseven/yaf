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
namespace Yaf\Config ;

/**
 * @link http://www.php.net/manual/en/class.yaf-config-simple.php
 */
class Simple extends \Yaf\Config_Abstract implements \Iterator, \Traversable, \ArrayAccess, \Countable {

	/**
	 * @see \Yaf\Config_Abstract::get
	 */
	public function __get($name = null){ }

	/**
	 * @see \Yaf\Config_Abstract::set
	 */
	public function __set($name, $value){ }

	/**
	 * @see \Yaf\Config_Abstract::get
	 */
	public function get($name = null){ }

	/**
	 * @see \Yaf\Config_Abstract::set
	 */
	public function set($name, $value){ }

	/**
	 * @see \Yaf\Config_Abstract::toArray
	 */
	public function toArray(){ }

	/**
	 * @see \Yaf\Config_Abstract::readonly
	 */
	public function readonly(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-config-simple.construct.php
	 *
	 * @param array $array
	 * @param string $readonly
	 *
	 */
	public function __construct(array $array, $readonly = null){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-config-simple.isset.php
	 * @param string $name
	 */
	public function __isset($name){ }

	/**
	 * @see \Countable::count
	 */
	public function count(){ }

	/**
	 * @see \Iterator::rewind
	 */
	public function rewind(){ }

	/**
	 * @see \Iterator::current
	 */
	public function current(){ }

	/**
	 * @see \Iterator::next
	 */
	public function next(){ }

	/**
	 * @see \Iterator::valid
	 */
	public function valid(){ }

	/**
	 * @see \Iterator::key
	 */
	public function key(){ }

	/**
	 * @see \ArrayAccess::offsetUnset
	 */
	public function offsetUnset($name){ }

	/**
	 * @see \ArrayAccess::offsetGet
	 */
	public function offsetGet($name){ }

	/**
	 * @see \ArrayAccess::offsetExists
	 */
	public function offsetExists($name){ }

	/**
	 * @see \ArrayAccess::offsetSet
	 */
	public function offsetSet($name, $value){ }
}