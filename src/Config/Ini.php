<?php
namespace Yaf\Config ;

use stdClass;
use TypeError;

/**
 * <p>\Yaf\Config\Ini enables developers to store configuration data in a familiar INI format and read them in the application by using nested object property syntax. The INI format is specialized to provide both the ability to have a hierarchy of configuration data keys and inheritance between configuration data sections. Configuration data hierarchies are supported by separating the keys with the dot or period character ("."). A section may extend or inherit from another section by following the section name with a colon character (":") and the name of the section from which data are to be inherited.</p><br/>
 * <b>Note:</b>
 * <p>\Yaf\Config\Ini utilizes the » parse_ini_file() PHP function. Please review this documentation to be aware of its specific behaviors, which propagate to \Yaf\Config\Ini, such as how the special values of "TRUE", "FALSE", "yes", "no", and "NULL" are handled.</p>
 * @link http://www.php.net/manual/en/class.yaf-config-ini.php
 */
class Ini extends \Yaf\Config_Abstract implements \Iterator, \Traversable, \ArrayAccess, \Countable {

	protected $_data = [];
	protected $_object = null;
	/**
	 * @see \Yaf\Config_Abstract::get
	 */
	public function __get($name = null){ 
		return $this->get($name);
	}

	/**
	 * @see \Yaf\Config_Abstract::set
	 */
	public function __set($name, $value){ 
		throw new \Yaf\Exception\TypeError('TODO');
	}

	/**
	 * @see \Yaf\Config_Abstract::get
	 */
	public function get($name = null){ 
		if($name ===null) {
			return $this->_data;
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
			return $v;
		}
	}


	/**
	 * @see \Yaf\Config_Abstract::set
	 * @deprecated not_implemented
	 */
	public function set($name, $value){ }

	/**
	 * @see \Yaf\Config_Abstract::toArray
	 */
	public function toArray(){ 
		return $this->_data;
	}

	/**
	 * @see \Yaf\Config_Abstract::readonly
	 */
	public function readonly(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-config-ini.construct.php
	 *
	 * @param string $config_file path to an INI configure file
	 * @param string $section which section in that INI file you want to be parsed
	 *
	 * @throws \Yaf\Exception\TypeError
	 */
	public function __construct(string $config_file, string $section = null){ 
		if(!file_exists($config_file)){
			throw new TypeError("config file '$config_file' is not exists");
		}
		if(!is_readable($config_file)){
			throw new TypeError("config file '$config_file' can not READ");
		}
		$data = parse_ini_file($config_file, $section);  /* ,INI_SCANNER_NORMAL */
		if(!empty($data)) foreach($data as $key=>$value){
			$_key_arr = explode('.', $key);
			$_key_count = count($_key_arr);
			$v = [$_key_arr[$_key_count-1] => $value];
			for($i = $_key_count-2; $i>=0; $i--) {
				$v = [$_key_arr[$i] => $v];
			}
			$this->_data = array_merge_recursive($this->_data, $v);
		}
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-config-ini.isset.php
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
	 * @deprecated not_implemented
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