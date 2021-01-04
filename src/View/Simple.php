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
namespace Yaf\View ;

use Yaf\Application;
use Yaf\Exception\LoadFailed\View;

/**
 *  \Yaf\View\Simple  is the built-in template engine in Yaf, it is a simple but fast template engine, and only support PHP script template.
 * @link http://www.php.net/manual/en/class.yaf-view-simple.php
 *
 * @method void|bool eval(string $tpl_str, array $vars = null)  Render a string template and return the result.
 *
 * @link http://www.php.net/manual/en/yaf-view-simple.eval.php
 *
 * @param string $tpl_str string template
 * @param array $vars
 *
 * @return void|bool return FALSE on failure
 */
class Simple implements \Yaf\View_Interface {

	/**
	 * @var string
	 */
	protected $_tpl_dir = '';
	/**
	 * @var array
	 */
	protected $_tpl_vars = [];
	/**
	 * @var array
	 */
	protected $_options = [];

	/**
	 * @link http://www.php.net/manual/en/yaf-view-simple.construct.php
	 *
	 * @param string $template_dir The base directory of the templates, by default, it is APPLICATION . "/views" for Yaf.
	 * @param array $options  Options for the engine, as of Yaf 2.1.13, you can use short tag
	 * "<?=$var?>" in your template(regardless of "short_open_tag"),
	 * so comes a option named "short_tag",  you can switch this off
	 * to prevent use short_tag in template.
	 *
	 * @throws \Yaf\Exception\TypeError
	 */
	final public function __construct($template_dir, array $options = []){ 
		$this->_tpl_dir = $template_dir;
		$this->_options = $options;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-view-simple.isset.php
	 *
	 * @param string $name
	 */
	public function __isset($name){ }

	/**
	 * assign variable to view engine
	 *
	 * @link http://www.php.net/manual/en/yaf-view-simple.assign.php
	 *
	 * @param string|array $name A string or an array.<br/>if is string, then the next argument $value is required.
	 * @param mixed $value mixed value
	 * @return \Yaf\View\Simple
	 */
	public function assign($name, $value = null){ 
		if($value === null  && is_array($name)) {
			$this->_tpl_vars = array_replace_recursive($this->_tpl_vars, $name);
		}else {
			$this->_tpl_vars[$name] = $value;
		}
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-view-simple.render.php
	 *
	 * @param string $tpl
	 * @param array $tpl_vars
	 *
	 * @throws \Yaf\Exception\LoadFailed\View
	 *
	 * @return string|void
	 */
	public function render($tpl, array $tpl_vars = []){ 
		$tpl_ob = ob_get_contents();
		\Yaf\ExceptionHandler::instance()->appendDebugMsg($tpl_ob);
		// var_dump($tpl_ob);
		unset($tpl_ob);
		while (ob_get_level() > 0) {
			ob_end_flush();
		}

		ob_start();
		$this->display($tpl, $tpl_vars);
		$tpl_ob = ob_get_contents();
		ob_end_clean();

		ob_start();
		return $tpl_ob;
	}

	/**
	 *  Render a template and display the result instantly.
	 *
	 * @link http://www.php.net/manual/en/yaf-view-simple.display.php
	 *
	 * @param string $tpl
	 * @param array $tpl_vars
	 *
	 * @throws \Yaf\Exception\LoadFailed\View
	 *
	 * @return bool
	 */
	public function display($tpl, array $tpl_vars = []){
		$view_ext = Application::app()->getConfig('application.view.ext');
		$view_ext = !empty($view_ext)?".$view_ext":".phtml";
		$tpl = strtolower(str_replace($view_ext, '', $tpl));
		$tpl_path = $this->_tpl_dir . DIRECTORY_SEPARATOR . $tpl . $view_ext;
		if(!empty($tpl_vars)){
			$this->_tpl_vars = array_replace_recursive($this->_tpl_vars, $tpl_vars);
		}
		extract($this->_tpl_vars);
		if(file_exists($tpl_path)){
			require($tpl_path);
		}else {
			if(file_exists($tpl_path)){
				require($tpl_path);
			}else {
				throw new View("$tpl_path is not exist");
			}
		}
	}

	/**
	 *  unlike \Yaf\View\Simple::assign(), this method assign a ref value to engine.
	 * @link http://www.php.net/manual/en/yaf-view-simple.assignref.php
	 *
	 * @param string $name A string name which will be used to access the value in the template.
	 * @param mixed $value mixed value
	 *
	 * @return \Yaf\View\Simple
	 */
	public function assignRef($name, &$value){ 
		return $this->assign($name, $value);
	}

	/**
	 * clear assigned variable
	 * @link http://www.php.net/manual/en/yaf-view-simple.clear.php
	 *
	 * @param string $name assigned variable name. <br/>if empty, will clear all assigned variables.
	 *
	 * @return \Yaf\View\Simple
	 */
	public function clear($name = null){ 
		if($name ===null){			
			$this->_tpl_vars = [];
		}
		if(isset($this->_tpl_vars[$name])){
			unset($this->_tpl_vars[$name]);
		}
		return $this;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-view-simple.setscriptpath.php
	 *
	 * @param string $template_dir
	 *
	 * @return \Yaf\View\Simple
	 */
	public function setScriptPath($template_dir){ 
		$this->_tpl_dir = $template_dir;
		return $this;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-view-simple.getscriptpath.php
	 *
	 * @return string
	 */
	public function getScriptPath(){ 
		return $this->_tpl_dir;
	}

	/**
	 *  Retrieve assigned variable
	 
	 *  Note: 
	 *  $name parameter can be empty since 2.1.11
	 * @link http://www.php.net/manual/en/yaf-view-simple.get.php
	 *
	 * @param null $name  the assigned variable name
	 
	 *  if this is empty, all assigned variables will be returned
	 *
	 * @return mixed
	 */
	public function __get($name = null){ 
		if($name ===null) {
			return $this->_tpl_vars;
		}
		if(isset($this->_tpl_vars[$name])){
			return $this->_tpl_vars[$name];
		}
		return '';
	}

	/**
	 *  This is a alternative and easier way to \Yaf\View\Simple::assign().
	 *
	 * @link http://www.php.net/manual/en/yaf-view-simple.set.php
	 *
	 * @param string $name A string value name.
	 * @param mixed $value mixed value
	 */
	public function __set($name, $value = null){ 
		$this->assign($name,$value);
	}
}