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
 * Yaf provides a ability for developers to use custom view engine instead of build-in engine which is \Yaf\View\Simple. There is a example to explain how to do this, please see \Yaf\Dispatcher::setView()
 *
 * @link http://www.php.net/manual/en/class.yaf-view-interface.php
 */
interface View_Interface {

	/**
	 * Assign values to View engine, then the value can access directly by name in template.
	 *
	 * @link http://www.php.net/manual/en/yaf-view-interface.assign.php
	 *
	 * @param string|array $name
	 * @param mixed $value
	 * @return bool
	 */
	function assign($name, $value = '');

	/**
	 * Render a template and output the result immediately.
	 *
	 * @link http://www.php.net/manual/en/yaf-view-interface.display.php
	 *
	 * @param string $tpl
	 * @param array $tpl_vars
	 * @return bool
	 */
	function display($tpl, array $tpl_vars = []);

	/**
	 * @link http://www.php.net/manual/en/yaf-view-interface.getscriptpath.php
	 *
	 * @return string
	 */
	function getScriptPath();

	/**
	 * Render a template and return the result.
	 *
	 * @link http://www.php.net/manual/en/yaf-view-interface.render.php
	 *
	 * @param string $tpl
	 * @param array $tpl_vars
	 * @return string
	 */
	function render($tpl, array $tpl_vars = []);

	/**
	 * Set the templates base directory, this is usually called by \Yaf\Dispatcher
	 *
	 * @link http://www.php.net/manual/en/yaf-view-interface.setscriptpath.php
	 *
	 * @param string $template_dir An absolute path to the template directory, by default, \Yaf\Dispatcher use application.directory . "/views" as this parameter.
	 */
	function setScriptPath($template_dir);
}