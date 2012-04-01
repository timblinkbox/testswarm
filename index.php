<?php
/**
 * This is the main front-end entry point for TestSwarm.
 *
 * All HTML-based views served to web browsers start here.
 * The recommended configuration will have web requests
 * rewritten from a path to a query string calling index.php
 *
 * @author Timo Tijhof, 2012
 * @since 0.1.0
 * @package TestSwarm
 */

require_once "inc/init.php";

session_start();

$action = preg_replace( "/[^a-z]/", "", $swarmContext->getRequest()->getVal( "action", "home" ) );
$actionFile = "inc/actions/$action.php";
$pageFile = "inc/pages/$action.php";

$pageObj = $swarmContext->getRequest()->getPageInstance();

// old style action (to be deleted)
if ( file_exists( $actionFile ) ) {
	require $actionFile;
}

if ( $pageObj instanceof Page ) {
	try {
		$pageObj->output();
	} catch ( Exception $e ) {
		$pageObj = Error500Page::newFromContext( $swarmContext );
		$pageObj->setExceptionObj( $e );
		$pageObj->output();
	}

// old style page (to be deleted)
} elseif  ( file_exists( $pageFile ) ) {
	require $pageFile;

} else {
	$pageObj = Error404Page::newFromContext( $swarmContext );
	$pageObj->output();
}

exit;
