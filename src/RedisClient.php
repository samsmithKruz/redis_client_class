<?php

class RedisClient
{
    private $redis;

    public function __construct($host = '127.0.0.1', $port = 6379, $timeout = 0.0)
    {
        $this->redis = new Redis();
        if (!$this->redis->connect($host, $port, $timeout)) {
            throw new Exception("Failed to connect to Redis server at $host:$port");
        }
    }

    // 🌟 BASIC CRUD OPERATIONS
    public function insert($key, $value, $expiration = 0)
    {
        return $expiration > 0 ? $this->redis->setex($key, $expiration, $value) : $this->redis->set($key, $value);
    }

    public function select($key) { return $this->redis->get($key); }

    public function delete($key) { return $this->redis->del($key); }

    public function update($key, $value, $expiration = 0) { return $this->insert($key, $value, $expiration); }

    public function increment($key, $amount = 1) { return $this->redis->incrBy($key, $amount); }

    public function decrement($key, $amount = 1) { return $this->redis->decrBy($key, $amount); }

    public function select_all() { return $this->redis->keys('*'); }

    public function flush() { return $this->redis->flushAll(); }

    // 🌟 HASH OPERATIONS
    public function hash_insert($hash, $field, $value) { return $this->redis->hSet($hash, $field, $value); }

    public function hash_select($hash, $field) { return $this->redis->hGet($hash, $field); }

    public function hash_delete($hash, $field) { return $this->redis->hDel($hash, $field); }

    public function hash_select_all($hash) { return $this->redis->hGetAll($hash); }

    // 🌟 LIST OPERATIONS
    public function push($list, $value) { return $this->redis->lPush($list, $value); }

    public function pop($list) { return $this->redis->rPop($list); }

    public function list_range($list, $start, $stop) { return $this->redis->lRange($list, $start, $stop); }

    // 🌟 SET OPERATIONS
    public function set_add($set, $value) { return $this->redis->sAdd($set, $value); }

    public function set_remove($set, $value) { return $this->redis->sRem($set, $value); }

    public function set_members($set) { return $this->redis->sMembers($set); }

    public function set_intersect($set1, $set2) { return $this->redis->sInter($set1, $set2); }

    // 🌟 SORTED SET (ZSET) OPERATIONS
    public function zadd($zset, $score, $member) { return $this->redis->zAdd($zset, $score, $member); }

    public function zrem($zset, $member) { return $this->redis->zRem($zset, $member); }

    public function zrange($zset, $start, $stop, $withScores = false) { return $this->redis->zRange($zset, $start, $stop, $withScores); }

    public function zrevrange($zset, $start, $stop, $withScores = false) { return $this->redis->zRevRange($zset, $start, $stop, $withScores); }

    public function zscore($zset, $member) { return $this->redis->zScore($zset, $member); }

    public function zrank($zset, $member) { return $this->redis->zRank($zset, $member); }

    // 🌟 STREAM OPERATIONS
    public function xadd($stream, $message, $id = '*') { return $this->redis->xAdd($stream, $id, $message); }

    public function xrange($stream, $start = '-', $end = '+') { return $this->redis->xRange($stream, $start, $end); }

    public function xread($streams) { return $this->redis->xRead($streams, null, 0); }

    // 🌟 TRANSACTIONS & PIPELINES
    public function transaction($callback)
    {
        $this->redis->multi();
        $callback($this->redis);
        return $this->redis->exec();
    }

    public function pipeline($callback)
    {
        $this->redis->pipeline();
        $callback($this->redis);
        return $this->redis->exec();
    }

    // 🌟 LUA SCRIPTING
    public function eval($script, $keys = [], $args = []) { return $this->redis->eval($script, array_merge($keys, $args), count($keys)); }

    // 🌟 PUB/SUB
    public function publish($channel, $message) { return $this->redis->publish($channel, $message); }

    public function subscribe($channel, $callback) { return $this->redis->subscribe([$channel], $callback); }

    // 🌟 EXPIRATION HANDLING
    public function set_expiration($key, $seconds) { return $this->redis->expire($key, $seconds); }

    public function get_ttl($key) { return $this->redis->ttl($key); }

    public function close() { $this->redis->close(); }
}
?>
