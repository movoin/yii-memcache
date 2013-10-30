<?php
namespace Movoin\Components;

use \CMemCache;

/**
 * Memcache 组件
 *
 * @copyright  Copyright (c) 2006 - 2013 Movoin Studio. <http://www.movoin.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @author     Allen <movoin@gmail.com>
 * @package    movoin.compoents
 * @since      0.1
 */
class Memcache extends CMemCache
{
    /**
     * @var string 顶级命名空间，多应用环境下可设置此参数使命名空间不受干扰。
     *             (!)注意：请不要在顶级命名空间中设置层级或加 `.`
     *                       如：namespace.name1 是不被支持的
     */
    public $keyPrefix = 'namespace';

    /**
     * @var string 顶级命名空间值，初始化时为其赋值
     */
    private static $_rootNamespace;

    /**
     * 初始化组件
     */
    public function init()
    {
        parent::init();
        self::$_rootNamespace = $this->getNamespaceValue($this->keyPrefix);
    }

    /**
     * 根据命名空间过期缓存
     *
     * @param string $namespace 命名空间
     * @return boolean
     */
    public function flushNS($namespace)
    {
        $this->setValue($this->generateNamespaceKey($namespace), time(), 0);
    }

    /**
     * @param string $key (含)命名空间的缓存键名，如：blog-id.related
     * @return string 真实的存储键名
     */
    protected function generateUniqueKey($key)
    {
        $namespaceKey = array();
        if(strrpos($key, '.') !== false)
        {
            $ns = explode('.', $key);
            $namespace = array_shift($ns);
            array_push($namespaceKey, $this->getNamespaceValue($namespace));
            $namespaceKey = array_merge($namespaceKey, $ns);
        } else {
            $namespaceKey = array($key);
        }
        return $this->generateNamespaceKey(implode('.', $namespaceKey));
    }

    /**
     * 生成命名空间存储键名
     *
     * @param string $key 缓存键名
     * @return string 键名对应的命名空间存储键名
     */
    protected function generateNamespaceKey($key)
    {
        $namespaceKey = "{self::$_rootNamespace}.{$key}";
        return $this->hashKey ? md5($namespaceKey) : $namespaceKey;
    }

    /**
     * 获得指定键名的命名空间
     *
     * @param string $key 命名空间的缓存键名
     * @return string 键名对应的命名空间
     */
    protected function getNamespaceValue($key)
    {
        if (($value = $this->getValue($this->generateNamespaceKey($key))) === false)
        {
            $this->flushNS($key);
        }

        return $value;
    }
}
