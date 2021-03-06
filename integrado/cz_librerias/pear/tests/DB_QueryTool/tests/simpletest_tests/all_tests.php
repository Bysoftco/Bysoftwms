<?php
// $Id: all_tests.php,v 1.1 2005/02/25 14:25:08 quipo Exp $

require_once 'simple_include.php';
require_once 'db_querytool_include.php';

define('TEST_RUNNING', true);

require_once './db_querytool_tests_get.php';
require_once './db_querytool_tests_usage.php';

class AllTests extends GroupTest {
    function AllTests() {
        $this->GroupTest('All PEAR::DB_QueryTool Tests - '.DB_TYPE);
        $this->AddTestCase(new DB_QueryToolTests_Get());
        $this->AddTestCase(new DB_QueryToolTests_Usage());
    }
}

$test = &new AllTests();
$test->run(new HtmlReporter());
?>