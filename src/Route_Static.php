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
 *  by default, \Yaf\Router only have a  \Yaf\Route_Static  as its default route.
 
 *   \Yaf\Route_Static  is designed to handle 80% of normal requirements.
 
 *  Note: 
 *   it is unnecessary to instance a  \Yaf\Route_Static , also unnecessary to add it into \Yaf\Router's routes stack, since there is always be one in \Yaf\Router's routes stack, and always be called at the last time.
 *
 * @link http://www.php.net/manual/en/class.yaf-route-static.php
 *
 */
class Route_Static implements \Yaf\Route_Interface {

	/**
	 * @link http://www.php.net/manual/en/yaf-route-static.match.php
	 *
	 * @param string $uri
	 *
	 * @return bool
	 */
	public function match($uri){ 
		return true;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-route-static.route.php
	 *
	 * @param \Yaf\Request_Abstract $request
	 *
	 * @return bool always TRUE
	 */
	public function route(\Yaf\Request_Abstract $request){ 
		$uri = $request->getRequestUri();
		if(empty($uri)){
			return true;
		}
		$application = Application::app();
		$uri = str_replace('//', '/', $uri);
		$uri = trim($uri, '/');
		
		$dispatcher = $application->getDispatcher();
		$module  = $dispatcher->getDefaultModule(); 	
		$controller = $dispatcher->getDefaultController();
		$action = $dispatcher->getDefaultAction();
		$params = [];
		if(!empty($uri)) {
			$uri_explode = explode('/', $uri);
			array_walk($uri_explode,function (&$var)
			{
				$var =  (trim($var));
			});
			$explode_count = count($uri_explode);
			$action_prefer = $application->getConfig('yaf.action_prefer');
			$module_limit = $application->getModules();
			array_walk($module_limit,function (&$var)
			{
				$var =  (trim($var));
			});
			$param_start =	false;
			if ($explode_count === 1 ) {			//1个参数: 赋值给controller
				$controller = $uri_explode[0];
			} else  {									//2个参数:
				if(in_array(strtolower($uri_explode[0]), $module_limit)) {	//如果参数0是module，则，参数1为controller，参数2为action
					$module = $uri_explode[0];
					$controller = $uri_explode[1];
					isset($uri_explode[2]) && $action = $uri_explode[2];
					$param_start = 3;
				}else {											//否则，参数1为控制器，参数2为action
					$controller = $uri_explode[0];
					$action = $uri_explode[1];
					$param_start = 2;
				}
			}
			if($param_start !== false && isset($uri_explode[$param_start])) {
				$param_index = 0;
				$param_key = 0;
				for($i=$param_start; $i<$explode_count; $i++){
					if($param_index%2==0) {
						$param_key = $uri_explode[$i];
					}else {
						$params[$param_key] = $uri_explode[$i];
					}
					$param_index ++;
				}
			}
		}

		$request->setModuleName($module);
		$request->setControllerName($controller);
		$request->setActionName($action);
		$request->setParam($params);

		$dispatcher->setRequest($request);
		return true;
	}

	/**
	 *   \Yaf\Route_Static::assemble()  - Assemble a url
	 *
	 * @link http://www.php.net/manual/en/yaf-route-static.assemble.php
	 *
	 * @param array $info
	 * @param array $query
	 * @return bool
	 */
	public function assemble(array $info, array $query = null){		
		$controller = Dispatcher::getInstance()->getDefaultController();
		$module = Dispatcher::getInstance()->getDefaultModule();
		$module_default = Dispatcher::getInstance()->getDefaultModule();
		$action = Dispatcher::getInstance()->getDefaultAction();
		$params = [];
		foreach($info as $key=> $value) {
			switch ($key) {
				case ':m':
					$module = $value; 
				break;
				case ':a':
					$action = $value; 
				break;
				case ':c':
					$controller = $value; 
				break;
				
				default:
					$params[] = urlencode($key)."=".urlencode($value);
				break;
			}
		}

		$uri = "/$controller/$action";
		if($module !== $module_default) {
			$uri = "/$module" . $uri;
		}
		if(!empty($params)) {
			$uri .= implode('/', $params);
		}
		if(!empty($query)) {			
			$uri .= http_build_query($query);
		}
		return $uri;
	}
}