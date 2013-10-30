# 基于命名空间的 MemCache 组件 #

这是一个 Yii 框架的缓存组件，基于命名空间的实现，可以轻松的完成删除一组缓存数据的操作。


## 介绍 ##

使用简单的时间戳方式实现的命名空间，每一个缓存键名对应一个时间戳，由各个时间戳组合成为最终的缓存键名存储数据。组件只关注命名空间，其它的事情则交由 Memcache 自己去处理。

**示例：**

    Yii::app()->cache->set('root.key', $cache);

    -> set namespace.root 13728276388
    -> set 13728276388.key $cache

    Yii::app()->cache->get('root.key');

    -> get namespace.root
    -> get 13728276388.key


## 使用 ##

### 安装 ###

"movoin/yii-memcache" => "stable"


### 如何配置 ###

    return array(
        'components' => array(
            'cache' => array(
                'class' => '\\Movoin\\Components\\Memcache',
                'keyPrefix' => 'your-namespace', // 默认为 namespace，多个应用时区分命名空间，相当于顶级命名空间
                'servers' => array(
                    array(
                        'host' => 'server1',
                        'port' => 11211,
                        'weight' => 60
                    ),
                    array(
                        'host' => 'server2',
                        'port' => 11211,
                        'weight' => 40
                    )
                )
            )
        )
    );


### 写入缓存 ###

- 缓存只取第一个键名作为命名空间，之所以这样处理是考虑到绝大多数情况都不需要使用层级的命名空间（带约束关系的树状命名空间），实现树状命名空间会带来额外的开销，得不偿失。

    Yii::app()->cache->set('namespace.child', $cache);
    Yii::app()->cache->set('namespace.child.name', $cache);


### 清除缓存 ###

    Yii::app()->cache->flushNS('namespace');
