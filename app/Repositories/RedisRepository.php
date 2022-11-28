<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class RedisRepository
{
	private $readClient;
	private $writeClient;

	function __construct($connection = 'default')
	{
		$this->writeClient = Redis::connection($connection);
		$this->readClient = Redis::connection($connection.'-slave');
	}

	//writes
	public function set($keyName, $keyValue)
	{
        $set = $this->writeClient->set($keyName, $keyValue);
		return $set;
	}

	public function setNxPx($keyName, $keyValue, $expiryTime)
	{
		$setKey = Redis::set($keyName, $keyValue, 'PX', $expiryTime, 'NX');

		return $setKey;
	}

	public function rpush($keyName, $keyValue)
	{
		$rpush = $this->writeClient->rpush($keyName, $keyValue);
		return $rpush;
	}

	public function lset($keyName, $index, $element)
	{
		$lindex = $this->writeClient->lset($keyName, $index, $element);
		return $lindex;
	}

	public function publish($keyName, $keyValue)
	{
		$publish = $this->writeClient->publish($keyName, $keyValue);
		return $publish;
	}

	public function del($keyName)
	{
		$exist = $this->writeClient->del($keyName);
		return $exist;
	}

	//read
	public function exists($keyName)
	{
		$exist = $this->readClient->exists($keyName);
		return $exist;
	}

	public function get($keyName)
	{
		$exist = $this->readClient->get($keyName);
		return $exist;
	}

	public function keys($keyName)
	{
		$exist = $this->readClient->keys($keyName);
		return $exist;
	}

	public function lrange($keyName, $start, $stop)
	{
		$lrange = $this->readClient->lrange($keyName, $start, $stop);
		return $lrange;
	}

	public function lindex($keyName, $index)
	{
		$lindex = $this->readClient->lindex($keyName, $index);
		return $lindex;
	}

	
}