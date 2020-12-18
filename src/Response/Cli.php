<?php
namespace Yaf\Response ;

/**
 *
 */
class Cli extends \Yaf\Response_Abstract {

	/**
	 *
	 */
	private function __clone(){ }

	/**
	 * @return string
	 */
	private function __toString(){ 
	}
	
	/**
	 * send response
	 *
	 * @link http://www.php.net/manual/en/yaf-response-abstract.response.php
	 *
	 * @return \Yaf\Response\Cli
	 */
	public function response($key = self::DEFAULT_BODY){
		
		if(!empty($this->_redirect_url)) {			
			echo 'header(HTTP/1.1 301 Moved Permanently)';
			echo 'Location: '.$this->_redirect_url ;
			return $this;
		}

		if($this->_sendheader){
			echo "<!-- header($this->header, true, $this->_response_code) -->";
		}
		if(isset($this->_body[$key])) {
			echo $this->_body[$key];
		}
		return $this;
	}
}