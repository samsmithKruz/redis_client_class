<?php
namespace SamsmithKruz;
class RedisClient
{
    private $redis;

    /**
     * Constructor to initialize Redis connection.
     *
     * @param string $host Redis server hostname (default: '127.0.0.1').
     * @param int $port Redis server port (default: 6379).
     * @param float $timeout Redis connection timeout (default: 0.0).
     * @throws Exception if connection to Redis server fails.
     */
    public function __construct($host = '127.0.0.1', $port = 6379, $timeout = 0.0)
    {
        $this->redis = new \Redis();
        if (!$this->redis->connect($host, $port, $timeout)) {
            throw new Exception("Failed to connect to Redis server at $host:$port");
        }
    }

    // 🌟 BASIC CRUD OPERATIONS
    /**
     * Insert a key-value pair into Redis with an optional expiration time.
     * 
     * @param string $key Redis key
     * @param mixed $value Value to be stored
     * @param int $expiration Expiration time in seconds
     * @return bool True if the operation was successful
     */
    public function insert($key, $value, $expiration = 0)
    {
        return $expiration > 0 ? $this->redis->setex($key, $expiration, $value) : $this->redis->set($key, $value);
    }

    /**
     * Retrieve the value associated with a key from Redis.
     * 
     * @param string $key Redis key
     * @return mixed The value stored for the given key
     */
    public function select($key) { return $this->redis->get($key); }

    /**
     * Delete a key from Redis.
     * 
     * @param string $key Redis key
     * @return int The number of keys deleted
     */
    public function delete($key)
    {
        return $this->redis->del($key);
    }

    /**
     * Update the value of an existing key in Redis.
     * 
     * @param string $key Redis key
     * @param mixed $value New value
     * @param int $expiration Expiration time in seconds
     * @return bool True if the operation was successful
     */
    public function update($key, $value, $expiration = 0)
    {
        return $this->insert($key, $value, $expiration);
    }

    /**
     * Increment the value of a key by a given amount.
     * 
     * @param string $key Redis key
     * @param int $amount Increment amount
     * @return int The new value after the increment
     */
    public function increment($key, $amount = 1)
    {
        return $this->redis->incrBy($key, $amount);
    }

    /**
     * Decrement the value of a key by a given amount.
     * 
     * @param string $key Redis key
     * @param int $amount Decrement amount
     * @return int The new value after the decrement
     */
    public function decrement($key, $amount = 1)
    {
        return $this->redis->decrBy($key, $amount);
    }

    /**
     * Retrieve all keys from Redis.
     * 
     * @return array List of all Redis keys
     */
    public function select_all()
    {
        return $this->redis->keys('*');
    }

    /**
     * Flush all data from Redis.
     * 
     * @return bool True if the operation was successful
     */
    public function flush()
    {
        return $this->redis->flushAll();
    }

    // 🌟 HASH OPERATIONS
    /**
     * Insert a field-value pair into a Redis hash.
     * 
     * @param string $hash Redis hash name
     * @param string $field Field name
     * @param mixed $value Value to be stored
     * @return bool True if the operation was successful
     */
    public function hash_insert($hash, $field, $value)
    {
        return $this->redis->hSet($hash, $field, $value);
    }

    /**
     * Retrieve a field value from a Redis hash.
     * 
     * @param string $hash Redis hash name
     * @param string $field Field name
     * @return mixed The field value
     */
    public function hash_select($hash, $field)
    {
        return $this->redis->hGet($hash, $field);
    }

    /**
     * Delete a field from a Redis hash.
     * 
     * @param string $hash Redis hash name
     * @param string $field Field name
     * @return int The number of fields deleted
     */
    public function hash_delete($hash, $field)
    {
        return $this->redis->hDel($hash, $field);
    }

    /**
     * Retrieve all fields and values from a Redis hash.
     * 
     * @param string $hash Redis hash name
     * @return array The field-value pairs
     */
    public function hash_select_all($hash)
    {
        return $this->redis->hGetAll($hash);
    }

    // 🌟 LIST OPERATIONS
    /**
     * Push a value to the left of a Redis list.
     * 
     * @param string $list Redis list name
     * @param mixed $value Value to be pushed
     * @return int The new length of the list
     */
    public function push($list, $value)
    {
        return $this->redis->lPush($list, $value);
    }

    /**
     * Pop a value from the right of a Redis list.
     * 
     * @param string $list Redis list name
     * @return mixed The value popped from the list
     */
    public function pop($list)
    {
        return $this->redis->rPop($list);
    }

    /**
     * Retrieve a range of values from a Redis list.
     * 
     * @param string $list Redis list name
     * @param int $start Start index
     * @param int $stop Stop index
     * @return array The list range
     */
    public function list_range($list, $start, $stop)
    {
        return $this->redis->lRange($list, $start, $stop);
    }

    // 🌟 SET OPERATIONS
    /**
     * Add a member to a Redis set.
     * 
     * @param string $set Redis set name
     * @param mixed $value Value to be added
     * @return int The number of elements added to the set
     */
    public function set_add($set, $value)
    {
        return $this->redis->sAdd($set, $value);
    }

    /**
     * Remove a member from a Redis set.
     * 
     * @param string $set Redis set name
     * @param mixed $value Value to be removed
     * @return int The number of elements removed from the set
     */
    public function set_remove($set, $value)
    {
        return $this->redis->sRem($set, $value);
    }

    /**
     * Retrieve all members of a Redis set.
     * 
     * @param string $set Redis set name
     * @return array The set members
     */
    public function set_members($set)
    {
        return $this->redis->sMembers($set);
    }

    /**
     * Intersect two Redis sets.
     * 
     * @param string $set1 First set
     * @param string $set2 Second set
     * @return array The intersection of the two sets
     */
    public function set_intersect($set1, $set2)
    {
        return $this->redis->sInter($set1, $set2);
    }

    // 🌟 SORTED SET (ZSET) OPERATIONS
    /**
     * Add a member with a score to a Redis sorted set.
     * 
     * @param string $zset Redis sorted set name
     * @param int $score Member's score
     * @param mixed $member Member to be added
     * @return bool True if the operation was successful
     */
    public function zadd($zset, $score, $member)
    {
        return $this->redis->zAdd($zset, $score, $member);
    }

    /**
     * Remove a member from a Redis sorted set.
     * 
     * @param string $zset Redis sorted set name
     * @param mixed $member Member to be removed
     * @return int The number of members removed
     */
    public function zrem($zset, $member)
    {
        return $this->redis->zRem($zset, $member);
    }

    /**
     * Retrieve a range of members from a Redis sorted set by score.
     * 
     * @param string $zset Redis sorted set name
     * @param int $start Start index
     * @param int $stop Stop index
     * @param bool $withScores Whether to include the scores with the members
     * @return array The sorted set members in the specified range
     */
    public function zrange($zset, $start, $stop, $withScores = false)
    {
        return $this->redis->zRange($zset, $start, $stop, $withScores);
    }

    /**
     * Retrieve a reversed range of members from a Redis sorted set by score.
     * 
     * @param string $zset Redis sorted set name
     * @param int $start Start index
     * @param int $stop Stop index
     * @param bool $withScores Whether to include the scores with the members
     * @return array The reversed sorted set members in the specified range
     */
    public function zrevrange($zset, $start, $stop, $withScores = false)
    {
        return $this->redis->zRevRange($zset, $start, $stop, $withScores);
    }

    /**
     * Retrieve the score of a member in a Redis sorted set.
     * 
     * @param string $zset Redis sorted set name
     * @param mixed $member Member whose score is to be retrieved
     * @return float The score of the member
     */
    public function zscore($zset, $member)
    {
        return $this->redis->zScore($zset, $member);
    }

    /**
     * Retrieve the rank of a member in a Redis sorted set.
     * 
     * @param string $zset Redis sorted set name
     * @param mixed $member Member whose rank is to be retrieved
     * @return int The rank of the member
     */
    public function zrank($zset, $member)
    {
        return $this->redis->zRank($zset, $member);
    }

    // 🌟 STREAM OPERATIONS
    /**
     * Add a message to a Redis stream.
     * 
     * @param string $stream Redis stream name
     * @param array $message The message to be added
     * @param string $id Stream ID (default: '*')
     * @return string The message ID
     */
    public function xadd($stream, $message, $id = '*')
    {
        return $this->redis->xAdd($stream, $id, $message);
    }

    /**
     * Retrieve a range of messages from a Redis stream.
     * 
     * @param string $stream Redis stream name
     * @param string $start Start ID
     * @param string $end End ID
     * @return array The stream messages in the specified range
     */
    public function xrange($stream, $start = '-', $end = '+')
    {
        return $this->redis->xRange($stream, $start, $end);
    }

    /**
     * Read messages from Redis streams.
     * 
     * @param array $streams List of streams to read from
     * @return array The messages from the streams
     */
    public function xread($streams)
    {
        return $this->redis->xRead($streams, null, 0);
    }

    // 🌟 TRANSACTIONS & PIPELINES
    /**
     * Execute a Redis transaction.
     * 
     * @param callable $callback A callback function to execute commands within the transaction
     * @return array The results of the transaction
     */
    public function transaction($callback)
    {
        $this->redis->multi();
        $callback($this->redis);
        return $this->redis->exec();
    }

    /**
     * Execute a Redis pipeline.
     * 
     * @param callable $callback A callback function to execute commands in pipeline
     * @return array The results of the pipeline
     */
    public function pipeline($callback)
    {
        $this->redis->pipeline();
        $callback($this->redis);
        return $this->redis->exec();
    }

    // 🌟 LUA SCRIPTING
    /**
     * Execute a Lua script in Redis.
     * 
     * @param string $script Lua script to execute
     * @param array $keys Keys to pass to the script
     * @param array $args Arguments to pass to the script
     * @return mixed The result of the script execution
     */
    public function eval($script, $keys = [], $args = [])
    {
        return $this->redis->eval($script, array_merge($keys, $args), count($keys));
    }

    // 🌟 PUB/SUB
    /**
     * Publish a message to a Redis channel.
     * 
     * @param string $channel Channel name
     * @param string $message The message to be published
     * @return int The number of clients that received the message
     */
    public function publish($channel, $message)
    {
        return $this->redis->publish($channel, $message);
    }

    /**
     * Subscribe to a Redis channel and listen for messages.
     * 
     * @param string $channel Channel name
     * @param callable $callback Callback to handle received messages
     */
    public function subscribe($channel, $callback)
    {
        $this->redis->subscribe([$channel], $callback);
    }

    // 🌟 EXPIRATION HANDLING
    /**
     * Set the expiration time for a Redis key.
     * 
     * @param string $key Redis key
     * @param int $seconds Expiration time in seconds
     * @return bool True if the operation was successful
     */
    public function set_expiration($key, $seconds)
    {
        return $this->redis->expire($key, $seconds);
    }

    /**
     * Retrieve the time-to-live (TTL) for a Redis key.
     * 
     * @param string $key Redis key
     * @return int The TTL in seconds
     */
    public function get_ttl($key)
    {
        return $this->redis->ttl($key);
    }

    /**
     * Close the Redis connection.
     */
    public function close()
    {
        $this->redis->close();
    }
}
?>
