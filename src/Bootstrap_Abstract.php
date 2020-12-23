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

use Composer\Autoload\ClassLoader;

/**
 *  Bootstrap is a mechanism used to do some initial config before a Application run.<br/><br/>
 *  User may define their own Bootstrap class by inheriting  \Yaf\Bootstrap_Abstract <br/><br/>
 *  Any method declared in Bootstrap class with leading "_init", will be called by \Yaf\Application::bootstrap() one by one according to their defined order<br/><br/>
 *
 * @link http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 */
 class Bootstrap_Abstract {

}