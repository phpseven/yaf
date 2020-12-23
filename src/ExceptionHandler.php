<?php
namespace Yaf ;

use Yaf\Request\Http;
use Yaf\Response\Http as ResponseHttp;

/**
 * @link http://www.php.net/manual/en/class.yaf-exception.php
 */
class ExceptionHandler {


    protected $_debug_msg = [];
        
    protected string $message ='';
    protected int $code = 0;
    protected string $file ='';
    protected int $line =0;
    
    public function getMessage(){
        return $this->message;
    }
    
    public function getCode( ) {
        return $this->code;
    }
    public function getFile( ) {
        return $this->file;
    }
    public function getLine( ) {
        return $this->line;
    }
	/**
     * 返回所有的debug信息
     * @return string 
     */
	public function getDebugMsg(){ 
		return implode("\n", $this->_debug_msg);
    }
    
    /**
     * 增加一条debug信息，返回刚增加的信息
     * @param string $msg 
     * @return string 
     */
	public function appendDebugMsg(string $msg){ 
		$this->_debug_msg[] = $msg;
		return $msg;
	}

	/**
	 *  清空handler的错误信息
	 * @since 2.1.2
	 * @link http://www.php.net/manual/en/yaf-application.clearlasterror.php
	 */
	public function clearLastError(){ 
        $this->code = 0;
		$this->message = '';
    }
    
	/**
	 * 
	 * @var callable
	 */
    private $__error_handler;

    /**
     * 
     * @var ExceptionHandler
     */
    private static $__handler_instance;
    

    /**
	 * 切换在Yaf出错的时候抛出异常, 还是触发错误.
	 * 当然,也可以在配置文件中使用ap.dispatcher.thorwException=$switch达到同样的效果, 默认的是开启状态.
	 * @var bool
	 */
	protected $_throw_exception = true;
	/**
	 * 在ap.dispatcher.throwException开启的状态下, 是否启用默认捕获异常机制
	 * 当然,也可以在配置文件中使用ap.dispatcher.catchException=$switch达到同样的效果, 默认的是开启状态.
	 * 如果为TRUE, 则在有未捕获异常的时候, Yaf会交给Error Controller的Error Action处理.
	 * @var true
	 */
    protected $_catch_exception = false;

    protected $_exception_object;
    
    /**
     * 触发错误
     * @param int $type 错误类型
     * @param string $error_message  
     * @return void 
     */
	public function triggerError( string $error_message, int $type){
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $this->code = $type;
        $this->message = $error_message;
        $this->line = $trace[0]['line'];
        $this->file = $trace[0]['file'];
        
        
        return Application::app()->callFunction($this->__error_handler, [$this->code, $this->message, $this->file, $this->line ]);
    }
    
    public static function instance() {
        if(!isset(self::$__handler_instance)) {
            $instance = new self();
            $instance->__initConst();
            $instance->__error_handler = array($instance, 'errorHanlerDefault');
            set_exception_handler([$instance, '__initExceptionHandler'] );
            set_error_handler([$instance, '__initErrorHandler'] );
            self::$__handler_instance = $instance; 
        }
        return self::$__handler_instance;
    }
    
    public function __initExceptionHandler(\Throwable $e) {
        $errstr = $e->getMessage();
        $errfile = $e->getFile();
        $errno = $e->getCode();
        $errline = $e->getLine();
        
        $result = Application::app()->callFunction($this->__error_handler, [$errno, $errstr, $errfile, $errline]);      
        if($result === true){
            return $result;
        }
        if($this->_catch_exception) {
            $dispacher = Dispatcher::getInstance();
            $requst = new Http('', '');
            $requst->setControllerName('Error');
            $requst->setActionName('error');
            $requst->setParam('exception', $e);
            $response = new ResponseHttp();
            $dispacher->doDispatch($requst, $response);
            return true;
        }
        throw $e;
    }
    
	public  function __initErrorHandler($errno, $errstr, $errfile, $errline) {
        $result = Application::app()->callFunction($this->__error_handler, [$errno, $errstr, $errfile, $errline]);      
        if($result === true){
            return $result;
        }
        if($this->_throw_exception) {
            $error_class = $this->getExceptionName($errno);
            $exception_object = new $error_class();
            return $this->__initExceptionHandler($exception_object);
        }
        return  $result;
    }


    
	/**
	 * <p>Switch on/off exception throwing while unexpected error occurring. When this is on, Yaf will throwing exceptions instead of triggering catchable errors.</p><br/>
	 * <p>You can also use application.dispatcher.throwException to achieve the same purpose.</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.throwexception.php
	 *
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function throwException($flag){ 
		$this->_throw_exception = $flag;
	}

	/**
	 * <p>While the application.dispatcher.throwException is On(you can also calling to <b>\Yaf\Dispatcher::throwException(TRUE)</b> to enable it), Yaf will throw \Exception whe error occurs instead of trigger error.</p><br/>
	 * <p>then if you enable <b>\Yaf\Dispatcher::catchException()</b>(also can enabled by set application.dispatcher.catchException), all uncaught \Exceptions will be caught by ErrorController::error if you have defined one.</p>
	 *
	 * @link http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
	 *
	 * @param bool $flag
	 * @return \Yaf\Dispatcher
	 */
	public function catchException($flag){ 
		$this->_catch_exception = $flag;
	}


        
    public function __initConst()
    {
        
        define('YAF_ERR_BASE', E_ALL+1*2);
        define('YAF_ERR_STARTUP_FAILED', YAF_ERR_BASE+0);
        define('YAF\ERR\STARTUP\FAILED', YAF_ERR_STARTUP_FAILED);
        define('YAF_ERR_ROUTE_FAILED', YAF_ERR_BASE+1);
        define('YAF\ERR\ROUTE\FAILED', YAF_ERR_ROUTE_FAILED);
        define('YAF_ERR_DISPATCH_FAILED', YAF_ERR_BASE+2);
        define('YAF\ERR\DISPATCH\FAILED', YAF_ERR_DISPATCH_FAILED);
        define("YAF_ERR_NOTFOUND_MODULE", YAF_ERR_BASE+3);
        define("YAF\ERR\NOTFOUND\MODULE", YAF_ERR_NOTFOUND_MODULE);
        define("YAF_ERR_NOTFOUND_CONTROLLER", YAF_ERR_BASE+4);
        define("YAF\ERR\NOTFOUND\CONTROLLER", YAF_ERR_NOTFOUND_CONTROLLER);		
        define("YAF_ERR_NOTFOUND_ACTION", YAF_ERR_BASE+5);
        define("YAF\ERR\NOTFOUND\ACTION", YAF_ERR_NOTFOUND_ACTION);				
        define("YAF_ERR_NOTFOUND_VIEW", YAF_ERR_BASE+6);
        define("YAF\ERR\NOTFOUND\VIEW", YAF_ERR_NOTFOUND_VIEW);

        define("YAF_ERR_CALL_FAILED", YAF_ERR_BASE+7);
        define("YAF\ERR\CALL\FAILED", YAF_ERR_CALL_FAILED);
        define("YAF_ERR_AUTOLOAD_FAILED", YAF_ERR_BASE+8);
        define("YAF\ERR\AUTOLOAD\FAILED",YAF_ERR_AUTOLOAD_FAILED);
        define("YAF_ERR_TYPE_ERROR", YAF_ERR_BASE+9);
        define("YAF\ERR\TYPE\ERROR", YAF_ERR_TYPE_ERROR);   
    }


    /**
     * 
     * @param int $errno 
     * @return string[]|void 
     */
	protected function getErrorMap($errno = 0) {
		$error_map = [
            0 => '\Yaf\Exception',
			YAF_ERR_STARTUP_FAILED => '\Yaf\Exception\StartupError',
			YAF_ERR_ROUTE_FAILED => '\Yaf\Exception\RouterFailed',
			YAF_ERR_DISPATCH_FAILED => '\Yaf\Exception\DispatchFailed',
			YAF_ERR_NOTFOUND_MODULE => '\Yaf\Exception\LoadFailed\Module',
			YAF_ERR_NOTFOUND_CONTROLLER => '\Yaf\Exception\LoadFailed\Controller',
			YAF_ERR_NOTFOUND_ACTION => '\Yaf\Exception\LoadFailed\Action',
			YAF_ERR_NOTFOUND_VIEW => '\Yaf\Exception\LoadFailed\View',

			YAF_ERR_CALL_FAILED => '\Yaf\Exception',
			YAF_ERR_AUTOLOAD_FAILED => '\Yaf\Exception\LoadFailed',
			YAF_ERR_TYPE_ERROR => '\Yaf\Exception\TypeError',

			
			E_ERROR => '\Error',
			E_PARSE => '\ParseError',
			E_CORE_ERROR => '\Error',
			E_COMPILE_ERROR => '\CompileError',		
			E_USER_ERROR => '\Error'    ,
        ];
        if($errno ===0){
            return $error_map;
        }
        if(isset($error_map[$errno])) {
            return $error_map[$errno];
        }
        return '\Error';
	}


	protected function getExceptionName($errno) {
		$error_map = [
            0 => '\Yaf\Exception',
			YAF_ERR_STARTUP_FAILED => 'YAF_ERR_STARTUP_FAILED',
			YAF_ERR_ROUTE_FAILED => 'YAF_ERR_ROUTE_FAILED',
			YAF_ERR_DISPATCH_FAILED => 'YAF_ERR_DISPATCH_FAILED',
			YAF_ERR_NOTFOUND_MODULE => 'YAF_ERR_NOTFOUND_MODULE',
			YAF_ERR_NOTFOUND_CONTROLLER => 'YAF_ERR_NOTFOUND_CONTROLLER',
			YAF_ERR_NOTFOUND_ACTION => 'YAF_ERR_NOTFOUND_ACTION',
			YAF_ERR_NOTFOUND_VIEW => 'YAF_ERR_NOTFOUND_VIEW',

			YAF_ERR_CALL_FAILED => 'YAF_ERR_CALL_FAILED',
			YAF_ERR_AUTOLOAD_FAILED => 'YAF_ERR_AUTOLOAD_FAILED',
			YAF_ERR_TYPE_ERROR => 'YAF_ERR_TYPE_ERROR',

			
			E_ERROR => 'E_ERROR',
			E_PARSE => 'E_PARSE',
			E_CORE_ERROR => 'E_CORE_ERROR',
			E_COMPILE_ERROR => 'E_COMPILE_ERROR',		
			E_USER_ERROR => 'E_USER_ERROR',
		];
		return $error_map;

	}
    
    /**
     * 默认错误处理方式： 
     * 非致命错误，记录debug信息到DebugMsg，
     * 致命错误，触发处理给下一步进行处理
     * @param mixed $errno 
     * @param mixed $errstr 
     * @param mixed $errfile 
     * @param mixed $errline 
     * @return bool 
     */
    public function errorHanlerDefault($errno, $errstr, $errfile, $errline){
        $error_map = $this->getErrorMap();
        $error_number_string = $error_map[$errno]??$errno;
        $error_string =  "($error_number_string)$errstr \n #$errfile:$errline \n";
        //非致命错误，记录内容到debugMsg
        if(!isset($error_map[$errno])) {
            $this->appendDebugMsg($error_string);
            return true;
        }
        // $ob = ob_get_contents();
        // if($ob !== false) {
        //     $this->appendDebugMsg($ob);
        //     ob_end_clean();
        //     ob_start();
        // }
        // header('HTTP/1.1 500 Internal Server Error');
        // $trace = $this->__toString();
        // echo '<pre>'.$error_string .$trace .'</pre>';
        return false;
        //继续使用PHP标准错误处理程序
    }

    private function __exception_hanler($e){
        
        $error_message = $e->getMessage()."#". $e->getFile() . ':'.$e->getLine();
        
    }

    
    /**
     * 从trace获取堆栈信息
     * @param array $traces
     * @return string
     */
    public  function __toString()
    {

        $traces = debug_backtrace();
        $trace_string = self::traceToString($traces);
        return $trace_string;
    }


    /**
     * @param $traces
     * @return string
     */
    public static function traceToString($traces) {

        $trace_string = '';
        for ($i = count($traces)-1; $i >= 0; $i--) {
            $t = $traces[$i];
            if (isset($t['class']) && $t['class'] == __CLASS__ && in_array($t['function'], array(__FUNCTION__ ))) {
                continue;
            }
            if (isset($t['file'])) {
                $trace_string .= "{$t['file']}:{$t['line']} \r\n";
            }
            if (isset($t['class'])) {
                $trace_string .= "\t{$t['class']}{$t['type']}";
            }
            $trace_string .= "{$t['function']}";
            if (!empty($t['args'])) {
                $args = array();
                foreach ($t['args'] as $arg) {
                    if (is_numeric($arg)) {
                        $arg_tmp = $arg;
                    } else if (is_string($arg)) {
                        $arg_tmp = "'$arg'";
                    } else {
                        $arg_tmp = json_encode($arg, JSON_UNESCAPED_UNICODE |JSON_UNESCAPED_SLASHES);
                    }
                    if (strlen($arg_tmp) > 1000) {
                        $arg_tmp =  mb_substr($arg_tmp,0, 1000 ) . ' <And More...>}';
                    }
                    $args[] = $arg_tmp;

                }
                $trace_string .= '(' . implode(',', $args) . ')';
            } else {
                $trace_string .= '()';
            }
            $trace_string = "$trace_string \r\n";
        }
        return $trace_string;
    }
}