<?php
/* SVN FILE: $Id: the_paper_monkies_fixture.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
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
 * @subpackage    cake.tests.fixtures
 * @since         CakePHP(tm) v 1.2.0.4667
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 21:16:01 -0500 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package       cake.tests
 * @subpackage    cake.tests.fixtures
 */
class ThePaperMonkiesFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'ThePaperMonkies'
 * @access public
 */
	var $name = 'ThePaperMonkies';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'apple_id' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'device_id' => array('type' => 'integer', 'length' => 10, 'null' => true)
	);
/**
 * records property
 *
 * @var array
 * @access public
 */
	var $records = array();
}

?>
