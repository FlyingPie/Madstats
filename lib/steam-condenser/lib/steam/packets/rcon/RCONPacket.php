<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Steam Condenser (PHP)
 * @subpackage RCONPacket
 * @version $Id: RCONPacket.php 154 2008-12-11 07:49:47Z koraktor $
 */

require_once "steam/packets/SteamPacket.php";

abstract class RCONPacket extends SteamPacket
{
	const SERVERDATA_AUTH = 3;
	const SERVERDATA_AUTH_RESPONSE = 2;
	const SERVERDATA_EXECCOMMAND = 2;
	const SERVERDATA_RESPONSE_VALUE = 0;

	/**
	 * @var long
	 */
	private $requestId;

	public function __construct($requestId, $rconHeader, $rconData = null)
	{
		parent::__construct($rconHeader, "$rconData\0\0");

		$this->requestId = $requestId;
	}

	public function getBytes()
	{
		$contentData = $this->contentData->_array();
		return pack("V3a*", strlen($contentData) + 8, $this->requestId, $this->headerData, $contentData);
	}

	public function getRequestId()
	{
		return $this->requestId;
	}
}
?>
