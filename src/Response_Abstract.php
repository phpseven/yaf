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
 * @link http://www.php.net/manual/en/class.yaf-response-abstract.php
 */
abstract class Response_Abstract {

	const DEFAULT_BODY = "default";
	/**
	 * @var array
	 */
	protected $_header = [];
	/**
	 * @var array
	 */
	protected $_body = [];
	/**
	 * @var bool
	 */
	protected $_sendheader = false;


	/**
	 * 
	 * @var string
	 */
	protected $_redirect_url = '';


	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.construct.php
	 */
	public function __construct(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.destruct.php
	 */
	public function __destruct(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.clone.php
	 */
	private function __clone(){ }

	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.tostring.php
	 */
	public function __toString(){ 
		return get_class($this);
	}

	/**
	 * Set content to response
	 *
	 * @link http://www.php.net/manual/en/yaf-response-abstract.setbody.php
	 *
	 * @param string $content content string
	 * @param string $key  the content key, you can set a content with a key, if you don't specific, then \Yaf\Response_Abstract::DEFAULT_BODY will be used
	 
	 *  Note: 
	 *  this parameter is introduced as of 2.2.0
	 *
	 * @return bool
	 */
	public function setBody($content, $key = self::DEFAULT_BODY){ 
		$this->_body[$key] = $content;
		return true;
	}

	/**
	 * append a content to a exists content block
	 *
	 * @link http://www.php.net/manual/en/yaf-response-abstract.appendbody.php
	 *
	 * @param string $content content string
	 * @param string $key  the content key, you can set a content with a key, if you don't specific, then \Yaf\Response_Abstract::DEFAULT_BODY will be used
	 
	 *  Note: 
	 *  this parameter is introduced as of 2.2.0
	 *
	 * @return bool
	 */
	public function appendBody($content, $key = self::DEFAULT_BODY){
		if(!isset($this->_body[$key])) {
			$this->_body[$key] = '';
		}
		$this->_body[$key] .= $content;
		return true;
	}

	/**
	 * prepend a content to a exists content block
	 *
	 * @link http://www.php.net/manual/en/yaf-response-abstract.prependbody.php
	 *
	 * @param string $content content string
	 * @param string $key  the content key, you can set a content with a key, if you don't specific, then \Yaf\Response_Abstract::DEFAULT_BODY will be used
	 
	 *  Note: 
	 *  this parameter is introduced as of 2.2.0
	 *
	 * @return bool
	 */
	public function prependBody($content, $key = self::DEFAULT_BODY){ 
		if(!isset($this->_body[$key])) {
			$this->_body = '';
		}
		$this->_body[$key] = $content . $this->_body[$key];
		return true;
	}

	/**
	 * Clear existing content
	 *
	 * @link http://www.php.net/manual/en/yaf-response-abstract.clearbody.php
	 *
	 * @param string $key  the content key, you can set a content with a key, if you don't specific, then \Yaf\Response_Abstract::DEFAULT_BODY will be used
	 
	 *  Note: 
	 *  this parameter is introduced as of 2.2.0
	 *
	 * @return bool
	 */
	public function clearBody($key = self::DEFAULT_BODY){ 
		$this->_body[$key] = '';
		return true;
	}

	/**
	 * Retrieve an existing content
	 *
	 * @link http://www.php.net/manual/en/yaf-response-abstract.getbody.php
	 *
	 * @param null|string $key  the content key, if you don't specific, then \Yaf\Response_Abstract::DEFAULT_BODY will be used. if you pass in a NULL, then all contents will be returned as a array
	 
	 *  Note: 
	 *  this parameter is introduced as of 2.2.0
	 *
	 * @return mixed
	 */
	public function getBody($key = self::DEFAULT_BODY){ 
		return $this->_body[$key];
	}
	
	/**
	 * @link http://www.php.net/manual/en/yaf-response-abstract.setredirect.php
	 *
	 * @param string $url
	 *
	 * @return \Yaf\Response_Abstract
	 */
	public function setRedirect($url){ 
		$this->_redirect_url = $url;
		return $this;
	}


	
	abstract public function response($key = self::DEFAULT_BODY);
}