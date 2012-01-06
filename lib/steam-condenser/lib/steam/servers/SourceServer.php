<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Steam Condenser (PHP)
 * @subpackage SourceServer
 * @version $Id: SourceServer.php 154 2008-12-11 07:49:47Z koraktor $
 */

require_once "InetAddress.php";
require_once "exceptions/RCONNoAuthException.php";
require_once "steam/packets/rcon/RCONAuthRequest.php";
require_once "steam/packets/rcon/RCONAuthResponse.php";
require_once "steam/packets/rcon/RCONExecRequest.php";
require_once "steam/servers/GameServer.php";
require_once "steam/sockets/RCONSocket.php";
require_once "steam/sockets/SourceSocket.php";

/**
 * @package Steam Condenser (PHP)
 * @subpackage SourceServer
 * @todo Server has to recognize incoming packets
 */
class SourceServer extends GameServer
{
	/**
	 * @var long
	 */
	private $rconRequestId;

	/**
	 * @param InetAddress $serverIP
	 * @param int $portNumber The listening port of the server, defaults to 27015
	 * @since v0.1
	 */
	public function __construct(InetAddress $ipAddress, $portNumber = 27015)
	{
		parent::__construct($portNumber);

		$this->rconSocket = new RCONSocket($ipAddress, $portNumber);
		$this->socket = new SourceSocket($ipAddress, $portNumber);
	}

	public function rconAuth($password)
	{
		$this->rconRequestId = rand(0, pow(2, 16));
		 
		$this->rconSocket->send(new RCONAuthRequest($this->rconRequestId, $password));
		$this->rconSocket->getReply();
		$reply = $this->rconSocket->getReply();
		 
		return $reply->getRequestId() == $this->rconRequestId;
	}

	public function rconExec($command)
	{
		$this->rconSocket->send(new RCONExecRequest($this->rconRequestId, $command));

		try
		{
			while(true)
			{
				$responsePacket = $this->rconSocket->getReply();
				 
				if($responsePacket instanceof RCONAuthResponse)
				{
					throw new RCONNoAuthException();
				}

				$responsePackets[] = $responsePacket;
			}
		}
		catch(TimeoutException $e)
		{
			if(empty($responsePackets))
			{
				throw $e;
			}
		}

		$response = "";
		foreach($responsePackets as $packet)
		{
			$response .= $packet->getResponse();
		}
		 
		return $response;
	}
}
?>
