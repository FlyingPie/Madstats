<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Steam Condenser (PHP)
 * @subpackage RCONGoldSrcRequest
 * @version $Id: RCONGoldSrcRequest.php 154 2008-12-11 07:49:47Z koraktor $
 */

require_once "steam/packets/SteamPacket.php";

class RCONGoldSrcRequest extends SteamPacket
{
	public function __construct($request)
	{
		parent::__construct(0x00, $request);
	}

	public function __toString()
	{
		return pack("Va*", 0xFFFFFFFF, $this->contentData->_array());
	}
}
?>
