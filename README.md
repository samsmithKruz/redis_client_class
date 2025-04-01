
# RedisClient - PHP Redis Wrapper  
[![GitHub issues](https://img.shields.io/github/issues/samsmithKruz/redis_client_class)](https://github.com/samsmithKruz/redis_client_class/issues)
[![GitHub forks](https://img.shields.io/github/forks/samsmithKruz/redis_client_class)](https://github.com/samsmithKruz/redis_client_class/network)
[![GitHub stars](https://img.shields.io/github/stars/samsmithKruz/redis_client_class)](https://github.com/samsmithKruz/redis_client_class/stargazers)


RedisClient is a **developer-friendly PHP class** that provides a **clean and intuitive API** for working with Redis. It covers **all major Redis operations**, including:  

- **Basic Key-Value Operations**: Insert, update, delete, select  
- **Hashes**: Hash insert, update, delete, select  
- **Lists**: Push, pop, range selection  
- **Sets & Sorted Sets**: Add, remove, get members, score-based retrieval  
- **Transactions**: Multi-exec transactional execution  
- **Pub/Sub**: Publish and subscribe to channels  
- **Expirations & TTL**: Set timeouts for keys  
- **Advanced Features**: Pipelines, scripting, streams, and more  

## 🚀 Installation  

Clone the repository:  

```sh
git clone https://github.com/samsmithKruz/redis_client_class.git
cd redis-client
```

Ensure you have the **Redis PHP extension** installed. If not, install it:  

```sh
sudo apt install php-redis  # For Linux
brew install redis          # For Mac
```

## ⚡ Usage  

### 1️⃣ Initialize the RedisClient  

```php
require 'RedisClient.php';

$redis = new RedisClient(); // Default: 127.0.0.1:6379
```

### 2️⃣ Basic Key Operations  

```php
$redis->insert('username', 'john_doe', 3600); // Store for 1 hour
echo $redis->select('username'); // Output: john_doe
$redis->update('username', 'jane_doe');
$redis->delete('username');
```

### 3️⃣ Hash Operations  

```php
$redis->hash_insert('user:1', 'name', 'John Doe');
echo $redis->hash_select('user:1', 'name'); // Output: John Doe
```

### 4️⃣ List Operations  

```php
$redis->push('queue', 'task_1');
$redis->push('queue', 'task_2');
echo $redis->pop('queue'); // Output: task_2
```

### 5️⃣ Pub/Sub  

```php
$redis->publish('news', 'Breaking News: PHP is awesome!');
$redis->subscribe('news', function ($redis, $channel, $message) {
    echo "Received on $channel: $message";
});
```

### 6️⃣ Transactions  

```php
$redis->transaction(function ($tx) {
    $tx->set('balance', 1000);
    $tx->incrBy('balance', 500);
});
```

## 📜 Full Feature List  

- **Basic Commands:** `insert()`, `update()`, `delete()`, `select()`, `select_all()`  
- **Hashes:** `hash_insert()`, `hash_select()`, `hash_delete()`, `hash_select_all()`  
- **Lists:** `push()`, `pop()`  
- **Sets:** `set_add()`, `set_remove()`, `set_members()`  
- **Sorted Sets:** `zadd()`, `zrem()`, `zrange()`, `zscore()`, etc.  
- **Pub/Sub:** `publish()`, `subscribe()`  
- **Expirations:** `set_expiration()`, `get_ttl()`  
- **Transactions:** `transaction()`  
- **Pipelines:** `pipeline()`  
- **Scripting:** `eval()`, `evalsha()`  
- **Streams:** `xadd()`, `xread()`, `xdel()`  

## 🛠️ Contributing  

If you want to **improve the library**, feel free to:  

- Fork the repo  
- Create a feature branch  
- Submit a pull request  

## 📜 License  

This project is **open-source** and released under the **MIT License**.
