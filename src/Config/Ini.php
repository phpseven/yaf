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

use TypeError;

/**
 *  \Yaf\Config\Ini enables developers to store configuration data in a familiar INI format and read them in the application by using nested object property syntax. The INI format is specialized to provide both the ability to have a hierarchy of configuration data keys and inheritance between configuration data sections. Configuration data hierarchies are supported by separating the keys with the dot or period character ("."). A section may extend or inherit from another section by following the section name with a colon character (":") and the name of the section from which data are to be inherited.<br/>
 *  Note: 
 *  \Yaf\Config\Ini utilizes the » parse_ini_file() PHP function. Please review this documentation to be aware of its specific behaviors, which propagate to \Yaf\Config\Ini, such as how the special values of "TRUE", "FALSE", "yes", "no", and "NULL" are handled.
 * @link http://www.php.net/manual/en/class.yaf-config-ini.php
 */
class Ini extends \Yaf\Config_Abstract implements \Iterator, \Traversable, \ArrayAccess, \Countable {

	/**
	 * @link http://www.php.net/manual/en/yaf-config-ini.construct.php
	 *
	 * @param string $config_file path to an INI configure file
	 * @param string $section which section in that INI file you want to be parsed
	 *
	 * @throws \Yaf\Exception\TypeError
	 */
	public function __construct($config_file = '', string $section = ''){
		if(!is_string($config_file)){
			throw new TypeError("config file  typeError" . var_export($config_file, true) );
		}
		$section = trim($section);
		if(!file_exists($config_file)){
			throw new TypeError("config file '$config_file' is not exists");
		}
		if(!is_readable($config_file)){
			throw new TypeError("config file '$config_file' can not READ");
		}
		$data_with_section = parse_ini_file($config_file, true);  /* ,INI_SCANNER_NORMAL */

		if(empty($data_with_section))  {
			throw new TypeError("config file data '$config_file' is EMPTY ");
		}
		
		
		foreach($data_with_section as $section_string=>$data){
			if(!is_string($section_string)  || empty($section_string)){
				continue;
			}
			$section_arr = explode(':', $section_string);
			if(empty($section_arr[0]) ){
				continue;
			}
			$section_arr[0] = trim($section_arr[0]);
			if(!in_array($section_arr[0], ['common', $section ]) ) {
				continue;
			}
			foreach($data as $key=>$value){
				$_key_arr = explode('.', $key);
				$_key_count = count($_key_arr);
				$v = [$_key_arr[$_key_count-1] => $value];
				for($i = $_key_count-2; $i>=0; $i--) {
					$v = [$_key_arr[$i] => $v];
				}
				$this->_data = array_replace_recursive($this->_data, $v);
			}
		}
	}
	
}