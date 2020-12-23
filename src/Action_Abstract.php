<?php
/**
  *----------------------------------------------------------------------------------------------------------
  * YAF PHP version
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
  */
namespace Yaf ;

/**
 *  A action can be defined in a separate file in Yaf(see \Yaf\Controller_Abstract). that is a action method can also be a  \Yaf\Action_Abstract  class.
 
 *  Since there should be a entry point which can be called by Yaf (as of PHP 5.3, there is a new magic method __invoke, but Yaf is not only works with PHP 5.3+, Yaf choose another magic method execute), you must implement the abstract method \Yaf\Action_Abstract::execute() in your custom action class.
 *
 * @link http://www.php.net/manual/en/class.yaf-action-abstract.php
 *
 */
abstract class Action_Abstract extends \Yaf\Controller_Abstract {

	/**
	 * @var \Yaf\Controller_Abstract
	 */
	protected $_controller;

	/**
	 *  user should always define this method for a action, this is the entry point of an action.  \Yaf\Action_Abstract::execute()  may have arguments.
	 
	 *  Note: 
	 *  The value retrieved from the request is not safe. you should do some filtering work before you use it.
	 * @link http://www.php.net/manual/en/yaf-action-abstract.execute.php
	 *
	 * @param mixed ... unlimited number of arguments
	 * @return mixed
	 */
	abstract public function execute();

	/**
	 * retrieve current controller object.
	 *
	 * @link http://www.php.net/manual/en/yaf-action-abstract.getcontroller.php
	 *
	 * @return \Yaf\Controller_Abstract
	 */
	public function getController(){ }
}