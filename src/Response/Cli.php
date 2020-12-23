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