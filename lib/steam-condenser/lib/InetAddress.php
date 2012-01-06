<?php
/**
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Source Condenser (PHP)
 * @subpackage InetAddress
 * @version $Id: InetAddress.php 154 2008-12-11 07:49:47Z koraktor $
 */

/**
 * @package Source Condenser (PHP)
 * @subpackage InetAddress
 */
class InetAddress
{
	/**
	 * This array saves the octets of the IP address
	 * @var int[4]
	 */
	private $inetAddress = "127.0.0.1";

	/**
	 * @param String $inetAddress
	 * @since v0.1
	 */
	public function __construct($inetAddress = null)
	{
		if(!is_string($inetAddress))
		{
			throw new Exception("Parameter has to be a String.");
		}

		$this->inetAddress = gethostbyname($inetAddress);
	}

	/**
	 * @return String
	 */
	public function __toString()
	{
		return $this->inetAddress;
	}
}
?>