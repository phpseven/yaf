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

use Yaf\Config\Simple;

/**
 * @link http://www.php.net/manual/en/class.yaf-config-abstract.php
 */
abstract class Config_Abstract  implements \Iterator, \Traversable, \ArrayAccess, \Countable {

	protected $_data = [];

	/**
	 * @var bool
	 */
	protected $_readonly = true;

	

	public function __get($name = null){ 
		return $this->get($name);
	}

	public function __set($name, $value){ 
		return $this->set($name, $value);
	}


	public function get($name = null){ 
		if($name ===null) {
			return $this;
		}
		if(is_string($name)){
			$_key_arr = explode('.', $name);
			$_key_count = count($_key_arr);
			if(empty($_key_arr[0])) {
				throw new \Yaf\Exception\TypeError('ini key is empty, please check');
			}
			if(!isset($this->_data[$_key_arr[0]])){
				return null;
			}
			$v = $this->_data[$_key_arr[0]];
			for($i = 1; $i<$_key_count; $i++) {
				if(!isset($v[$_key_arr[$i]])){
					return null;
				}
				$v = $v[$_key_arr[$i]];
			}
			if(is_array($v)) {
				$class = get_class($this);
				return new Simple($v);
			}else {
				return $v;
			}
		}
	}


	public function set($name, $value){ 
		$this->_data[$name] = $value;
		return $this;
	}

	public function toArray(){ 
		return $this->_data;
	}

	public function __toString()
	{
		return json_encode($this->_data);
	}

	public function readonly(){ }


	/**
	 * @param string $name
	 */
	public function __isset($name){ 
		return isset($this->_data[$name]);
	}

	/**
	 * @see \Countable::count
	 */
	public function count(){ 
		return count($this->_data);
	}

	/**
	 * @see \Iterator::rewind
	 */
	public function rewind(){ 
		return reset($this->_data);
	}

	/**
	 * @see \Iterator::current
	 */
	public function current(){ 
		return current($this->_data);
	}

	/**
	 * @see \Iterator::next
	 */
	public function next(){ 
		return next($this->_data);
	}

	/**
	 * @see \Iterator::valid
	 */
	public function valid(){ 
		return key($this->_data) !== null;
	}

	/**
	 * @see \Iterator::key
	 */
	public function key(){ 
		return key($this->_data);
	}

	/**
	 * @see \ArrayAccess::offsetUnset
	 */
	public function offsetUnset($name){ 
		if($this->offsetExists($name)){
			unset($this->_data[$name]);
		}
	}

	/**
	 * @see \ArrayAccess::offsetGet
	 */
	public function offsetGet($name){ 
		return $this->get($name);
	}

	/**
	 * @see \ArrayAccess::offsetExists
	 */
	public function offsetExists($name){ 
		return isset($this->_data[$name]);
	}

	/**
	 * @see \ArrayAccess::offsetSet
	 */
	public function offsetSet($name, $value){ 
		$this->_data[$name] = $value;
	}

}