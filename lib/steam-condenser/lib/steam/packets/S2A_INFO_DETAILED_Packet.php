<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Steam Condenser (PHP)
 * @subpackage Packets
 * @version $Id: S2A_INFO_DETAILED_Packet.php 154 2008-12-11 07:49:47Z koraktor $
 */

require_once "steam/packets/S2A_INFO_BasePacket.php";

/**
 * @package Steam Condenser (PHP)
 * @subpackage Packets
 */
class S2A_INFO_DETAILED_Packet extends S2A_INFO_BasePacket
{
	/**
	 *
	 */
	public function __construct($data)
	{
		parent::__construct(SteamPacket::S2A_INFO_DETAILED_HEADER, $data);

		$this->serverIp = $this->contentData->getString();
		$this->serverName = $this->contentData->getString();
		$this->mapName = $this->contentData->getString();
		$this->gameDir = $this->contentData->getString();
		$this->gameDescription = $this->contentData->getString();
		$this->numberOfPlayers = $this->contentData->getByte();
		$this->maxPlayers = $this->contentData->getByte();
		$this->networkVersion = $this->contentData->getByte();
		$this->dedicated = $this->contentData->getByte();
		$this->operatingSystem = $this->contentData->getByte();
		$this->passwordProtected = $this->contentData->getByte() == 1;
		$this->isMod = $this->contentData->getByte() == 1;

		if($this->isMod)
		{
			$this->modInfo["urlInfo"] = $this->contentData->getString();
			$this->modInfo["urlDl"] = $this->contentData->getString();
			$this->contentData->getByte();
			$this->modInfo["modVersion"] = $this->contentData->getLong();
			$this->modInfo["modSize"] = $this->contentData->getLong();
			$this->modInfo["svOnly"] = ($this->contentData->getByte() == 1);
			$this->modInfo["clDll"] = ($this->contentData->getByte() == 1);
		}

		$this->secure = $this->contentData->getByte() == 1;
		$this->numberOfBots = $this->contentData->getByte();
	}
}
?>
