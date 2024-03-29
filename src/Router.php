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
 *   \Yaf\Router  is the standard framework router. Routing is the process of taking a URI endpoint (that part of the URI which comes after the base URI: see \Yaf\Request_Abstract::setBaseUri()) and decomposing it into parameters to determine which module, controller, and action of that controller should receive the request. This values of the module, controller, action and other parameters are packaged into a \Yaf\Request_Abstract object which is then processed by \Yaf\Dispatcher. Routing occurs only once: when the request is initially received and before the first controller is dispatched. \Yaf\Router is designed to allow for mod_rewrite-like functionality using pure PHP structures. It is very loosely based on Ruby on Rails routing and does not require any prior knowledge of webserver URL rewriting
 
 *  Default Route 
 
 *   \Yaf\Router  comes pre-configured with a default route \Yaf\Route_Static, which will match URIs in the shape of controller/action. Additionally, a module name may be specified as the first path element, allowing URIs of the form module/controller/action. Finally, it will also match any additional parameters appended to the URI by default - controller/action/var1/value1/var2/value2.
 
 *  Note: 
 *  Module name must be defined in config, considering application.module="Index,Foo,Bar", in this case, only index, foo and bar can be considered as a module name. if doesn't config, there is only one module named "Index".
 
 *  ** See examples by opening the external documentation
 * @link http://www.php.net/manual/en/class.yaf-router.php
 */
class Router {

	/**
	 * @var \Yaf\Route_Interface[] registered routes stack
	 */
	protected $_routes;
	/**
	 * @var string after routing phase, this indicated the name of which route is used to route current request. you can get this name by \Yaf\Router::getCurrentRoute()
	 */
	protected $_current;

	/**
	 * @link http://www.php.net/manual/en/yaf-router.construct.php
	 */
	public function __construct(){ }

	/**
	 *  by default, \Yaf\Router using a \Yaf\Route_Static as its default route. you can add new routes into router's route stack by calling this method.
	 
	 *  the newer route will be called before the older(route stack), and if the newer router return TRUE, the router process will be end. otherwise, the older one will be called.
	 *
	 * @link http://www.php.net/manual/en/yaf-router.addroute.php
	 *
	 * @param string $name
	 * @param \Yaf\Route_Interface $route
	 *
	 * @return bool|\Yaf\Router return FALSE on failure
	 */
	public function addRoute($name, \Yaf\Route_Interface $route){
		$this->_routes[$name]	= $route;
	 }

	/**
	 *  Add routes defined by configs into \Yaf\Router's route stack
	 *
	 * @link http://www.php.net/manual/en/yaf-router.addconfig.php
	 *
	 * @param \Yaf\Config_Abstract $config
	 *
	 * @return bool|\Yaf\Router return FALSE on failure
	 */
	public function addConfig(\Yaf\Config_Abstract $config){ }

	/**
	 * TODO:暂时只支持static
	 * @link http://www.php.net/manual/en/yaf-router.route.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 *
	 * @return bool|\Yaf\Router return FALSE on failure
	 */
	public function route(\Yaf\Request_Abstract &$request){ 
		if(!empty($this->_routes)) {
			foreach($this->_routes as $_route){
				if($_route->route($request) === true) {
					$this->current = $_route;
					return $this;
				}
			}
		}

		$route_static = new Route_Static();
		$route_static->route($request);
		$this->current = $route_static;
		return $this;
	}

	/**
	 *  Retrieve a route by name, see also \Yaf\Router::getCurrentRoute()
	 *
	 * @link http://www.php.net/manual/en/yaf-router.getroute.php
	 *
	 * @param string $name
	 *
	 * @return \Yaf\Route_Interface
	 */
	public function getRoute($name){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-router.getroutes.php
	 *
	 * @return \Yaf\Route_Interface[]
	 */
	public function getRoutes(){ 

		return $this->_routes;
	}

	/**
	 *  Get the name of the route which is effective in the route process.
	 
	 *  Note: 
	 *  You should call this method after the route process finished, since before that, this method will always return NULL.
	 *
	 * @link http://www.php.net/manual/en/yaf-router.getcurrentroute.php
	 *
	 * @return string the name of the effective route.
	 */
	public function assemble(array $info, array $query = null){ 
		if(empty($this->current)) {
			return null;
		}
		return $this->current->assemble($info, $query);
	}
}