<?php
namespace Yaf ;

/**
 * <p><b>\Yaf\Loader</b> introduces a comprehensive autoloading solution for Yaf.</p>
 * <br/>
 * <p>The first time an instance of \Yaf\Application is retrieved, <b>\Yaf\Loader</b> will instance a singleton, and registers itself with spl_autoload. You retrieve an instance using the \Yaf\Loader::getInstance()</p>
 * <br/>
 * <p><b>\Yaf\Loader</b> attempt to load a class only one shot, if failed, depend on yaf.use_spl_autoload, if this config is On \Yaf\Loader::autoload() will return FALSE, thus give the chance to other autoload function. if it is Off (by default), \Yaf\Loader::autoload() will return TRUE, and more important is that a very useful warning will be triggered (very useful to find out why a class could not be loaded).</p>
 * <br/>
 * <b>Note:</b>
 * <p>Please keep yaf.use_spl_autoload Off unless there is some library have their own autoload mechanism and impossible to rewrite it.</p>
 * <br/>
 * <p>If you want <b>\Yaf\Loader</b> search some classes(libraries) in the local class directory(which is defined in application.ini, and by default, it is application.directory . "/library"), you should register the class prefix using the \Yaf\Loader::registerLocalNameSpace()</p>
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
	private $ext;
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

		$app = Application::app();
		$directory_path =	$app->getAppDirectory();
		$default_module = strtolower($app->getDispatcher()->getDefaultModule());
		$request = $app->getDispatcher()->getRequest();		
		$module = $request ? $request->getModuleName() : $default_module;
        $class_name = str_replace('_', '\\', $class_name);
        $class = str_replace('\\\\', '\\', $class_name);
		
        //获取类信息
		$exploded = explode('\\', $class);
        // foreach ($exploded as $key => $_explode_value) {
        //     $exploded[$key] = $_explode_value;
		// }
		$last_key = array_key_last($exploded);
		$type = $this->__identify_category($class);
		if($type === self::YAF_CLASS_NAME_NORMAL) {
			$lib_dir = $this->_library;
		}else  {
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
		}
		$class_file_name = implode(DIRECTORY_SEPARATOR, $exploded) . $this->ext;


		$load_file = $lib_dir .DIRECTORY_SEPARATOR. $class_file_name;
        if (!file_exists($load_file)) {   //文件不存在
			Application::app()->appendErrorMsg('auto load: file is not exists: '.$load_file);
			if(isset($lib_dir_base)) {
				$load_file = $lib_dir_base .DIRECTORY_SEPARATOR. $class_file_name;
				if (!file_exists($load_file)) {   //文件不存在
					Application::app()->appendErrorMsg('auto load: file is not exists2: '.$load_file);
					return false;
				}
			}
        }
        require_once($load_file);
		if (!class_exists($class_name)) {    //类不存在
			Application::app()->appendErrorMsg('auto load: class is not exists: '.$class_name);
            return false;
		}
		Application::app()->appendErrorMsg('class loaded:  '.$class_name);
        return true;
	}

	/**
	 * 
#define YAF_LOADER_CONTROLLER		"Controller"
#define YAF_LOADER_MODEL			"Model"
#define YAF_LOADER_PLUGIN			"Plugin"
#define YAF_LOADER_RESERVERD		"Yaf_"

#define YAF_CLASS_NAME_NORMAL       0
#define YAF_CLASS_NAME_MODEL        1
#define YAF_CLASS_NAME_PLUGIN       2
#define YAF_CLASS_NAME_CONTROLLER   3
	 */

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




	/**
	 * @link http://www.php.net/manual/en/yaf-loader.getinstance.php
	 *
	 * @param string $local_library_path	默认library路径
	 * @param string $global_library_path	全局library？未用到
	 *
	 * @return \Yaf\Loader
	 */
	public static function getInstance($local_library_path = null, $global_library_path = null){ 
		if(empty(self::$_instance)) {
			$app = Application::app();
			$instance = new self();
			$instance->_library = $local_library_path;
			$instance->_global_library = $global_library_path;
			$application_config = $app->getConfig()->get('application');	
			$instance->ext = '.'.$application_config['ext'];
			spl_autoload_register([$instance, 'autoload']);
			self::$_instance = $instance;
		}
		return self::$_instance;
	}

	/**
	 * <p>Register local class prefix name, \Yaf\Loader search classes in two library directories, the one is configured via application.library.directory(in application.ini) which is called local library directory; the other is configured via yaf.library (in php.ini) which is called global library directory, since it can be shared by many applications in the same server.</p>
	 * <br/>
	 * <p>When an autoloading is triggered, \Yaf\Loader will determine which library directory should be searched in by examining the prefix name of the missed classname. If the prefix name is registered as a local namespace then look for it in local library directory, otherwise look for it in global library directory.</p>
	 * <br/>
	 * <b>Note:</b>
	 * <p>If yaf.library is not configured, then the global library directory is assumed to be the local library directory. in that case, all autoloading will look for local library directory. But if you want your Yaf application be strong, then always register your own classes as local classes.</p>
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
	public static function import($file){ }

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