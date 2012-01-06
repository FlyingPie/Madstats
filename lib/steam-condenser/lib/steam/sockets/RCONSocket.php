<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * @author     Sebastian Staudt
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package    Steam Condenser (PHP)
 * @subpackage Sockets
 * @version    $Id: RCONSocket.php 154 2008-12-11 07:49:47Z koraktor $
 */

require_once "InetAddress.php";
require_once "SocketChannel.php";
require_once "exceptions/PacketFormatException.php";
require_once "steam/packets/rcon/RCONPacket.php";
require_once "steam/packets/rcon/RCONPacketFactory.php";
require_once "steam/sockets/SteamSocket.php";

/**
 * @package    Steam Condenser (PHP)
 * @subpackage Sockets
 */
class RCONSocket extends SteamSocket
{
	public function __construct(InetAddress $ipAddress, $portNumber)
	{
		parent::__construct($ipAddress, $portNumber);

		$this->buffer = ByteBuffer::allocate(1400);
		$this->channel = SocketChannel::open();
		$this->remoteSocket = array($ipAddress, $portNumber);
	}

	public function send(RCONPacket $dataPacket)
	{
		if(!$this->channel->isConnected())
		{
			$this->channel->connect($this->remoteSocket[0], $this->remoteSocket[1]);
		}

		$this->buffer = ByteBuffer::wrap($dataPacket->getBytes());
		$this->channel->write($this->buffer);
	}

	public function getReply()
	{
		$this->receivePacket(1440);
		$packetData = substr($this->buffer->_array(), 0, $this->buffer->limit());
		$packetSize = $this->buffer->getLong() + 4;

		if($packetSize > 1440)
		{
			$remainingBytes = $packetSize - 1440;
			do
			{
				if($remainingBytes < 1440)
				{
					$this->receivePacket($remainingBytes);
				}
				else
				{
					$this->receivePacket(1440);
				}
				$packetData .= substr($this->buffer->_array(), 0, $this->buffer->limit());
				$remainingBytes -= $this->buffer->limit();
			}
			while($remainingBytes > 0);
		}

		return RCONPacketFactory::getPacketFromData($packetData);
	}
}
?>
