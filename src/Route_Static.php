<?php
namespace Yaf ;

/**
 * <p>by default, \Yaf\Router only have a <b>\Yaf\Route_Static</b> as its default route.</p>
 * <br/>
 * <p><b>\Yaf\Route_Static</b> is designed to handle 80% of normal requirements.</p>
 * <br/>
 * <b>Note:</b>
 * <p> it is unnecessary to instance a <b>\Yaf\Route_Static</b>, also unnecessary to add it into \Yaf\Router's routes stack, since there is always be one in \Yaf\Router's routes stack, and always be called at the last time.</p>
 *
 * @link http://www.php.net/manual/en/class.yaf-route-static.php
 *
 */
class Route_Static implements \Yaf\Route_Interface {

	/**
	 * @deprecated not_implemented
	 * @link http://www.php.net/manual/en/yaf-route-static.match.php
	 *
	 * @param string $uri
	 *
	 * @return bool
	 */
	public function match($uri){ }

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
			$uri_explode = explode('/', $uri);		//TODO: FILLTER
			array_walk($uri_explode,function (&$var)
			{
				$var =  (trim($var));
			});
			$explode_count = count($uri_explode);
			$action_prefer = $application->getConfig()->get('yaf.action_prefer');
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
	 * <p><b>\Yaf\Route_Static::assemble()</b> - Assemble a url
	 *
	 * @link http://www.php.net/manual/en/yaf-route-static.assemble.php
	 *
	 * @param array $info
	 * @param array $query
	 * @return bool
	 */
	public function assemble(array $info, array $query = null){ }
}