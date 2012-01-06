<?php
/**
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Steam Condenser (PHP)
 * @subpackage functions
 * @version $Id: functions.php 154 2008-12-11 07:49:47Z koraktor $
 */

/**
 * @param Exception $exception
 */
function exceptionHandler(Exception $exception)
{
	echo "Uncaught exception (" . get_class($exception) . ") occured in " . $exception->getFile() . ":" . $exception->getLine() . "\n";
	echo "Stack trace:\n";
	$stackTrace = $exception->getTrace();
	//var_dump($stackTrace);

	foreach($stackTrace as $traceNumber => $tracePoint)
	{
		echo "#$traceNumber: {$tracePoint["file"]}({$tracePoint["line"]}): {$tracePoint["class"]}{$tracePoint["type"]}{$tracePoint["function"]}()\n";
	}
}

/**
 *
 */
function debug($debugMessage)
{
	if($GLOBALS["debug"])
	{
		echo "DEBUG: $debugMessage\n";
	}
}
?>