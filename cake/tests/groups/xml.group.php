<?php
/* SVN FILE: $Id: xml.group.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Xml Group test.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake.tests
 * @subpackage    cake.tests.groups
 * @since         CakePHP(tm) v 1.2.0.4206
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 21:16:01 -0500 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/** XmlGroupTest
 *
 * This test group will run view class tests (view, theme).
 *
 * @package       cake.tests
 * @subpackage    cake.tests.groups
 */
/**
 * XmlGroupTest class
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class XmlGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'All core views'
 * @access public
 */
	var $label = 'All Xml based classes';
/**
 * AllCoreViewsGroupTest method
 *
 * @access public
 * @return void
 */
	function XmlGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'xml');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'view' . DS . 'helpers' . DS .'rss');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'view' . DS . 'helpers' . DS .'xml');
	}
}
?>
