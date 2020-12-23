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
 * @link http://www.php.net/manual/en/class.yaf-config-abstract.php
 */
abstract class Config_Abstract {

	/**
	 * @var array
	 */
	protected $_config = null;
	/**
	 * @var bool
	 */
	protected $_readonly = true;

	/**
	 * @link http://www.php.net/manual/en/yaf-config-abstract.get.php
	 *
	 * @param string $name
	 * @return mixed
	 */
	abstract public function get($name = null);

	/**
	 * @link http://www.php.net/manual/en/yaf-config-abstract.set.php
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return \Yaf\Config_Abstract
	 */
	abstract public function set($name, $value);

	/**
	 * @link http://www.php.net/manual/en/yaf-config-abstract.readonly.php
	 *
	 * @return bool
	 */
	abstract public function readonly();

	/**
	 * @link http://www.php.net/manual/en/yaf-config-abstract.toarray.php
	 *
	 * @return array
	 */
	abstract public function toArray();
}