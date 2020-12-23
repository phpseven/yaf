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
 *  \Yaf\Route_Interface  used for developer defined their custom route.
 *
 * @link http://www.php.net/manual/en/class.yaf-route-interface.php
 */
interface Route_Interface {

	/**
	 *   \Yaf\Route_Interface::route()  is the only method that a custom route should implement.<br/>
	 *  if this method return TRUE, then the route process will be end. otherwise, \Yaf\Router will call next route in the route stack to route request.<br/>
	 *  This method would set the route result to the parameter request, by calling \Yaf\Request_Abstract::setControllerName(), \Yaf\Request_Abstract::setActionName() and \Yaf\Request_Abstract::setModuleName().<br/>
	 *  This method should also call \Yaf\Request_Abstract::setRouted() to make the request routed at last.
	 *
	 * @link http://www.php.net/manual/en/yaf-route-interface.route.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 * @return bool
	 */
	function route(\Yaf\Request_Abstract $request);

	/**
	 *   \Yaf\Route_Interface::assemble()  - assemble a request<br/>
	 *  this method returns a url according to the argument info, and append query strings to the url according to the argument query.
	 *  a route should implement this method according to its own route rules, and do a reverse progress.
	 *
	 * @link http://www.php.net/manual/en/yaf-route-interface.assemble.php
	 *
	 * @param array $info
	 * @param array $query
	 * @return bool
	 */
	function assemble(array $info, array $query = null);
}