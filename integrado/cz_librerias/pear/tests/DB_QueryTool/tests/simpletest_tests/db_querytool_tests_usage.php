<?php
// $Id: db_querytool_tests_usage.php,v 1.1 2005/02/25 14:25:08 quipo Exp $

require_once 'simple_include.php';
require_once 'db_querytool_include.php';

class DB_QueryToolTests_Usage extends GroupTest {
    function DB_QueryToolTests_Usage() {
        $this->GroupTest('DB_QueryTool Usage Tests');
        $this->addTestFile('db_querytool_testDbInstance.php');
        $this->addTestFile('db_querytool_testLimit.php');
        $this->addTestFile('db_querytool_testWhere.php');
        $this->addTestFile('db_querytool_testHaving.php');

    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new DB_QueryToolTests_Usage();
    $test->run(new HtmlReporter());
}
?>