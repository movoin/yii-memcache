# 基于命名空间的 MemCache 组件 #

这是一个 Yii 框架的缓存组件，基于命名空间的实现，可以轻松的完成删除一组缓存数据的操作。


## 介绍 ##

使用简单的时间戳方式实现的命名空间，每一个缓存键名对应一个时间戳，由各个时间戳组合成为最终的缓存键名存储数据。组件只关注命名空间，其它的事情则交由 Memcache 自己去处理。

**示例：**

    Yii::app()->cache->set('root.child', $cache);

    -> set namespace.root 13728276388
    -> set 13728276388.child $cache

    Yii::app()->cache->get('root.child');

    -> get namespace.root
    -> get 13728276388.child


## 使用 ##

### 安装 ###

"movoin/yii-memcache" => "stable"


### 如何配置 ###

    return array(
        'components' => array(
            'cache' => array(
                'class' => '\\Movoin\\Components\\Memcache',
                'namespacePrefix' => '', // 默认为 namespace，多个应用时区分命名空间，相当于顶级命名空间
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

    Yii::app()->cache->set('root.child', $cache);
    Yii::app()->cache->set('root.child.name', $cache);
    Yii::app()->cache->set('root.child.*', time()); // 等同于 flushNS('root.child.*')


### 清除缓存 ###

    Yii::app()->cache->flushNS('root'); // Flush `root.*` and `root`
    Yii::app()->cache->flushNS('root.*'); // Flush `root.*` only
    Yii::app()->cache->flushNS('root.child'); // Flush `root.child.*` and `root.child`
    Yii::app()->cache->flushNS('root.child.*'); // Flush `root.child.*` only
