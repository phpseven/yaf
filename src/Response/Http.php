<?php
namespace Yaf\Response ;

/**
 *
 */
class Http extends \Yaf\Response_Abstract {

	/**
	 * @var int
	 */
	protected $_response_code = 200;


	/**
	 *
	 */
	private function __clone(){ }

	/**
	 * @return string
	 */
	public function __toString(){ 
		return get_class($this);
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.setheader.php
	 *
	 * @param string $name
	 * @param string $value
	 * @param bool $replace
	 * @param int $response_code
	 *
	 * @return \Yaf\Response\Http
	 */
	public function setHeader($name,$value,$replace = false,$response_code = 0){ 
		if($response_code !==0) {
			$this->_response_code = $response_code;
		}
		if(!isset($this->header[$name]) && $replace !== false) {
			$this->header[$name] .= $value;
		}
		$this->header[$name] = $value;
		return true;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.setallheaders.php
	 *
	 * @param array $headers
	 *
	 * @return \Yaf\Response\Http
	 */
	protected function setAllHeaders(array $headers){ 
		$this->header = $headers;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.getheader.php
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function getHeader($name = null){ 
		if($name === null){
			return $this->header;
		}
		return isset($this->header[$name])?$this->header[$name]:null;
	}

	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.clearheaders.php
	 *
	 * @param string $name
	 *
	 * @return \Yaf\Response\Http
	 */
	public function clearHeaders($name = null){ 
		if($name ===null) {
			$this->header = [];
		}
		if(isset($this->header[$name])) {
			unset($this->header[$name]);
		}
		return $this;

	}


	/**
	 * send response
	 *
	 * @link http://www.php.net/manual/en/yaf-response-abstract.response.php
	 *
	 * @return \Yaf\Response\Http
	 */
	public function response($key = self::DEFAULT_BODY){
		//TODO: OB
		$response_ob = ob_get_contents();
		ob_end_clean();

		if(!empty($this->_redirect_url)) {			
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$this->_redirect_url );
			return $this;
		}
		if($this->_sendheader){
			header($this->header, true, $this->_response_code);
		}
		if(isset($this->_body[$key])) {
			echo $this->_body[$key];
		}
		return $this;
	}
}