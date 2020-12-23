<?php
namespace Yaf ;

/**
 * @link http://www.php.net/manual/en/class.yaf-session.php
 * @version 2.2.9
 */
final class Session implements \Iterator, \Traversable, \ArrayAccess, \Countable {

	/**
	 * @var \Yaf\Session
	 */
	protected static $_instance;
	/**
	 * @var array
	 */
	protected $_session;
	/**
	 * @var bool
	 */
	protected $_started = true;

	/**
	 * @link http://www.php.net/manual/en/yaf-session.construct.php
	 */
	private function __construct(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-session.clone.php
	 */
	private function __clone(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-session.sleep.php
	 */
	public function __sleep(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-session.wakeup.php
	 */
	public function __wakeup(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-session.getinstance.php
	 *
	 * @return \Yaf\Session
	 */
	public static function getInstance(){ 
		if(empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-session.start.php
	 *
	 * @return \Yaf\Session
	 */
	public function start(){
		$ob = ob_get_contents();
        if($ob !== false) {
            ExceptionHandler::instance()->appendDebugMsg($ob);
            ob_end_clean();
		}
		ob_start();
		session_start();
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-session.get.php
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get($name){ 
		return $_SESSION[$name]?$_SESSION[$name]:'';
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-session.has.php
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function has($name){ 
		return isset($_SESSION[$name])?true:false;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-session.set.php
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return bool|\Yaf\Session return FALSE on failure
	 */
	public function set($name, $value){
		$_SESSION[$name] = $value;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-session.del.php
	 *
	 * @param string $name
	 *
	 * @return bool|\Yaf\Session return FALSE on failure
	 */
	public function del($name){ 
		unset($_SESSION[$name]);
	}

	/**
	 * @see \Countable::count
	 */
	public function count(){ 
		count($_SESSION);
	}

	/**
	 * @see \Iterator::rewind
	 */
	public function rewind(){ }

	/**
	 * @see \Iterator::current
	 */
	public function current(){ }

	/**
	 * @see \Iterator::next
	 */
	public function next(){ }

	/**
	 * @see \Iterator::valid
	 */
	public function valid(){ }

	/**
	 * @see \Iterator::key
	 */
	public function key(){ }

	/**
	 * @see \ArrayAccess::offsetUnset
	 */
	public function offsetUnset($name){ 
		return $this->get($name);
	}

	/**
	 * @see \ArrayAccess::offsetGet
	 */
	public function offsetGet($name){ 
		return $this->get($name);

	}

	/**
	 * @see \ArrayAccess::offsetExists
	 */
	public function offsetExists($name){ 
		$this->has($name);
	}

	/**
	 * @see \ArrayAccess::offsetSet
	 */
	public function offsetSet($name, $value){ 
		return $this->set($name,$value);
	}

	/**
	 * @see \Yaf\Session::get()
	 */
	public function __get($name){ }

	/**
	 * @see \Yaf\Session::has()
	 */
	public function __isset($name){ }

	/**
	 * @see \Yaf\Session::set()
	 */
	public function __set($name, $value){ }

	/**
	 * @see \Yaf\Session::del()
	 */
	public function __unset($name){ }
}