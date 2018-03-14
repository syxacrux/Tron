<?php
//目录我放在thinkphp5.0/extend/redis
namespace redis;

class RedisPackage{
    protected static $handler = null;
    protected $options = [
        'host'      => '127.0.0.1',
        'port'      => 6379,
        'password'  => '',
        'select'    => 0,
        'timeout'   => 0,     //关闭时间 0:代表不关闭
        'expire'    => 0,
        'persistent'=>false,
        'prefix'    => '',
    ];

    public function __construct($options = []){
        //判断是否有扩展(如果apache没reids扩展就会抛出这个异常)
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $func = $this->options['persistent'] ? 'pconnect' : 'connect';     //判断是否长连接
        self::$handler = new \Redis;
        self::$handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);

        if ('' != $this->options['password']) {
            self::$handler->auth($this->options['password']);
        }

        if (0 != $this->options['select']) {
            self::$handler->select($this->options['select']);
        }
    }

    /**
     * 写入缓存
     * @param $key  string $key 键名
     * @param $value    string $value 键值
     * @param int $exprie   int $exprie 过期时间 0:永不过期
     * @return bool
     * @author zjs 2018/3/13
     */
    public static function set($key, $value, $exprie = 0){
        if ($exprie == 0) {
            $set = self::$handler->set($key, $value);
        } else {
            $set = self::$handler->setex($key, $exprie, $value);
        }
        return $set;
    }

    /**
     * 读取缓存
     * @param $key  string $key 键值
     * @return mixed
     * @author zjs 2018/3/13
     */
    public static function get($key){
        $fun = is_array($key) ? 'Mget' : 'get';
        return self::$handler->{$fun}($key);
    }

    /**
     * 获取值长度
     * @param $key  string $key
     * @return int
     * @author zjs 2018/3/13
     */
    public static function lLen($key){
        return self::$handler->lLen($key);
    }

    /**
     * 将一个或多个值插入到列表头部
     * @param $key
     * @param $value
     * @return int
     * @author zjs 2018/3/13
     */
    public static function LPush($key, $value, $value2 = null, $valueN = null){
        return self::$handler->lPush($key, $value, $value2, $valueN);
    }

    /**
     * 移出并获取列表的第一个元素
     * @param string $key
     * @return string
     * @author zjs 2018/3/13
     */
    public static function lPop($key){
        return self::$handler->lPop($key);
    }
}