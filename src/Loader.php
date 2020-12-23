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

use Yaf\Exception\LoadFailed\Controller;
use Yaf\Exception\TypeError;

/**
 *   \Yaf\Loader  introduces a comprehensive autoloading solution for Yaf.
 
 *  The first time an instance of \Yaf\Application is retrieved,  \Yaf\Loader  will instance a singleton, and registers itself with spl_autoload. You retrieve an instance using the \Yaf\Loader::getInstance()
 
 *   \Yaf\Loader  attempt to load a class only one shot, if failed, depend on yaf.use_spl_autoload, if this config is On \Yaf\Loader::autoload() will return FALSE, thus give the chance to other autoload function. if it is Off (by default), \Yaf\Loader::autoload() will return TRUE, and more important is that a very useful warning will be triggered (very useful to find out why a class could not be loaded).
 
 *  Note: 
 *  Please keep yaf.use_spl_autoload Off unless there is some library have their own autoload mechanism and impossible to rewrite it.
 
 *  If you want  \Yaf\Loader  search some classes(libraries) in the local class directory(which is defined in application.ini, and by default, it is application.directory . "/library"), you should register the class prefix using the \Yaf\Loader::registerLocalNameSpace()
 * @link http://www.php.net/manual/en/class.yaf-loader.php
 *
 */
class Loader {

	/**
	 * @var string
	 */
	protected $_local_ns;
	/**
	 * By default, this value is application.directory . "/library", you can change this either in the application.ini(application.library) or call to \Yaf\Loader::setLibraryPath()
	 * @var string
	 */
	protected $_library;
	/**
	 * @var string
	 */
	protected $_global_library;
	/**
	 * @var \Yaf\Loader
	 */
	protected static $_instance;

	/**
	 * php脚本的扩展名
	 * @var string
	 */
	private $ext = '.php';
	/**
	 * @link http://www.php.net/manual/en/yaf-loader.construct.php
	 */
	private function __construct(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.clone.php
	 */
	private function __clone(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.sleep.php
	 */
	public function __sleep(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.wakeup.php
	 */
	public function __wakeup(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.autoload.php
	 *
	 * @param string $class
	 *
	 * @return bool 返回true 已加载，不再继续执行后续的autoload
	 * 				返回false未找到，继续执行后续的autoload（composer）
	 */
	public function autoload($class_name){ 

		\Yaf\ExceptionHandler::instance()->appendDebugMsg('auto load: start: '.$class_name);

		if(!preg_match("/^[a-z\\][a-z0-9_\\\\]+$/i",$class_name )) {
			$msg = "$class_name 不是一个合法的类名1";
			\Yaf\ExceptionHandler::instance()->appendDebugMsg('auto load: '.$msg);
			return false;
		}
		
		// Yaf加载
		if(strpos(strtolower($class_name), 'yaf\\' ) ===0) {			
			$class = str_replace('\\\\', '\\', $class_name);
			$exploded = explode('\\', $class);
			unset($exploded[0]);
			$lib_dir = __DIR__;
			$class_file_name = implode(DIRECTORY_SEPARATOR, $exploded) . $this->ext;	
			$file_find[] =$lib_dir .DIRECTORY_SEPARATOR. $class_file_name;
			$type = self::YAF_CLASS_NAME_NORMAL;
		} else {				
			
			$app = Application::app();
			$directory_path =	$app->getAppDirectory();
			$default_module = strtolower($app->getDispatcher()->getDefaultModule());
			$request = $app->getDispatcher()->getRequest();		
			$module = $request ? $request->getModuleName() : $default_module;
			
			$class = str_replace('_', '\\', $class_name);
			$class = str_replace('\\\\', '\\', $class);
			$exploded = explode('\\', $class);

			
			$last_key = array_key_last($exploded);
			$type = $this->__identify_category($class);
			if($type === self::YAF_CLASS_NAME_NORMAL) {	//library
				$lib_dir = $this->_library;
				$class_file_name = implode(DIRECTORY_SEPARATOR, $exploded) . $this->ext;
				$file_find[] =$lib_dir .DIRECTORY_SEPARATOR. $class_file_name;
			}else  {	//controller model plugin
				$type_name = $this->__class_name_map[$type];
				$lib_dir_base = $directory_path. DIRECTORY_SEPARATOR .$type_name.'s';
				$lib_dir = $directory_path. DIRECTORY_SEPARATOR .'modules'. DIRECTORY_SEPARATOR.ucfirst($module). DIRECTORY_SEPARATOR.$type_name.'s';
				if($type == self::YAF_CLASS_NAME_CONTROLLER) {
					array_walk($exploded, function(&$var){
						$var = ucfirst(strtolower(trim($var)));
					});
				}else { 	
					array_walk($exploded, function(&$var){
						$var = (strtolower(trim($var)));
					});
					$exploded[$last_key] = ucfirst($exploded[$last_key]);
				}
				$exploded[$last_key] = str_ireplace($type_name, '', $exploded[$last_key] );
				$class_file_name = implode(DIRECTORY_SEPARATOR, $exploded) . $this->ext;
				
				$file_find[] =$lib_dir .DIRECTORY_SEPARATOR. $class_file_name;
				if(isset($lib_dir_base)) {
					$file_find[] =$lib_dir_base .DIRECTORY_SEPARATOR. $class_file_name;
				}
			}				
		}
		foreach($file_find as $load_file) {			
			if (!file_exists($load_file)  || !is_readable($load_file)) {   //文件不存在
				\Yaf\ExceptionHandler::instance()->appendDebugMsg('yaf auto load: file is not exists: '.$load_file);
			}else {
				\Yaf\ExceptionHandler::instance()->appendDebugMsg('yaf auto load: file exists: '.$load_file);
				require_once($load_file);
				break;
			}
		}
		
		if (!class_exists($class_name)) {    //类不存在
			\Yaf\ExceptionHandler::instance()->appendDebugMsg('yaf auto load: class is not exists: '.$class_name .'====' .$load_file);
			if($type === self::YAF_CLASS_NAME_MODEL) {
				throw new Exception\LoadFailed\Model("Model $class_name is not exists");
			}
			if($type === self::YAF_CLASS_NAME_CONTROLLER) {
				throw new Exception\LoadFailed\Controller("Controller $class_name is not exists");
			}
			if($type === self::YAF_CLASS_NAME_PLUGIN) {
				throw new Exception\LoadFailed\Plugin("Plugin $class_name is not exists");
			}
            return false;
		}
		\Yaf\ExceptionHandler::instance()->appendDebugMsg('yaf auto loaded ok :  '.$class_name);
        return true;
	}


	

	const YAF_CLASS_NAME_NORMAL = 0;
	const YAF_CLASS_NAME_MODEL = 1;
	const YAF_CLASS_NAME_PLUGIN = 2;
	const YAF_CLASS_NAME_CONTROLLER = 3;

	private $__class_name_map = [
		self::YAF_CLASS_NAME_MODEL => 'model',
		self::YAF_CLASS_NAME_PLUGIN => 'plugin',
		self::YAF_CLASS_NAME_CONTROLLER => 'controller',
	];

	private function __identify_category($class_name){
		$class_name = strtolower($class_name);
		foreach($this->__class_name_map as $type=>$postfix){
			$postfix = strtolower($postfix);
			if(strlen($class_name)> strlen( $postfix ) && strpos($class_name, $postfix, -1*strlen( $postfix ) ) !== false  ) {
				return $type;
			}
		}
		return self::YAF_CLASS_NAME_NORMAL;

	}


	public function initLibrary($local_library_path = null, $global_library_path = null) {
		$instance = self::$_instance;
		$instance->_library = $local_library_path;
		$instance->_global_library = $global_library_path;
		$app = Application::app();
		$application_config = $app->getConfig()->get('application');	
		$instance->ext = '.'.$application_config['ext'];
	}


	/**
	 * @link http://www.php.net/manual/en/yaf-loader.getinstance.php
	 *
	 * @param string $local_library_path	默认library路径
	 * @param string $global_library_path	全局library？未用到
	 *
	 * @return \Yaf\Loader
	 */
	public static function getInstance(){ 
		if(empty(self::$_instance)) {
			self::$_instance = new self();
			spl_autoload_register([self::$_instance, 'autoload']);
		}
		return self::$_instance;
	}

	/**
	 *  Register local class prefix name, \Yaf\Loader search classes in two library directories, the one is configured via application.library.directory(in application.ini) which is called local library directory; the other is configured via yaf.library (in php.ini) which is called global library directory, since it can be shared by many applications in the same server.
	 
	 *  When an autoloading is triggered, \Yaf\Loader will determine which library directory should be searched in by examining the prefix name of the missed classname. If the prefix name is registered as a local namespace then look for it in local library directory, otherwise look for it in global library directory.
	 
	 *  Note: 
	 *  If yaf.library is not configured, then the global library directory is assumed to be the local library directory. in that case, all autoloading will look for local library directory. But if you want your Yaf application be strong, then always register your own classes as local classes.
	 * @link http://www.php.net/manual/en/yaf-loader.registerlocalnamespace.php
	 *
	 * @param string|string[] $name_prefix a string or a array of class name prefix. all class prefix with these prefix will be loaded in local library path.
	 *
	 * @return bool
	 */
	public function registerLocalNamespace($name_prefix){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.getlocalnamespace.php
	 *
	 * @return string
	 */
	public function getLocalNamespace(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.clearlocalnamespace.php
	 */
	public function clearLocalNamespace(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.islocalname.php
	 *
	 * @param string $class_name
	 *
	 * @return bool
	 */
	public function isLocalName($class_name){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-loader.import.php
	 *
	 * @param string $file
	 *
	 * @return bool
	 */
	public static function import($file){ 
		if(file_exists($file) && is_readable($file)) {
			require_once($file);
			return true;
		}
		return false;
	}

	/**
	 * @since 2.1.4
	 * @link http://www.php.net/manual/en/yaf-loader.setlibrarypath.php
	 *
	 * @param string $directory
	 * @param bool $global
	 *
	 * @return \Yaf\Loader
	 */
	public function setLibraryPath($directory, $global = false){ 
		if($global){
			$this->_global_library = $directory;
		}else {
			$this->_library = $directory;
		}
		return $this;
	}

	/**
	 * @since 2.1.4
	 * @link http://www.php.net/manual/en/yaf-loader.getlibrarypath.php
	 *
	 * @param bool $is_global
	 *
	 * @return string
	 */
	public function getLibraryPath($is_global = false){ 
		if($is_global){
			return $this->_global_library;
		}else {
			return $this->_library;
		}
	}
}