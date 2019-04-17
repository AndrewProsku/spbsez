<?php

use Bitrix\Main\Application;
use Bitrix\Main\Data\ManagedCache;

trait BitrixManagedCache
{
    protected static $cacheTTL = 3600; // 1h

    /**
     * Удаление всего кеша директории
     *
     * @throws \Bitrix\Main\SystemException
     */
    public static function cleanCacheDir()
    {
        Application::getInstance()->getManagedCache()->cleanDir(static::getCacheDirName());
    }

    /**
     * Удаление определнного кеша
     *
     * @param $cacheId
     * @throws \Bitrix\Main\SystemException
     */
    public static function cleanCache($cacheId)
    {
        Application::getInstance()->getManagedCache()->clean($cacheId);
    }

    /**
     * Запрос получения кеша
     *
     * @param string $cacheId
     * @return mixed
     * @throws \Bitrix\Main\SystemException
     */
    protected static function getCache(string $cacheId)
    {
        return static::initManagedCache($cacheId)->get($cacheId);
    }

    /**
     * @param string $cacheId
     * @param $val
     */
    protected static function setCache(string $cacheId, $val)
    {
        $cache = static::initManagedCache($cacheId);
        $cache->set($cacheId, $val);
    }

    /**
     * @param $cacheId
     * @return ManagedCache
     * @throws \Bitrix\Main\SystemException
     */
    protected static function initManagedCache($cacheId): ManagedCache
    {
        $cache = Application::getInstance()->getManagedCache();
        $cache->read(static::$cacheTTL, $cacheId, static::getCacheDirName());

        return $cache;
    }

    /**
     * Определение директории кеша в зависимости от используемного класса
     *
     * @return string
     */
    protected static function getCacheDirName(): string
    {
        return strtolower(str_replace('\\', '_', static::class));
    }

    /**
     * Определение ID кеша по параметрам
     *
     * @param array $arr дополнительный массив параметров кеширования
     * @return string
     */
    protected static function getCacheId(array $arr): string
    {
        $arr = array_merge(
            $arr,
            [
                get_called_class()
            ]
        );

        return md5(implode('|', $arr));
    }
}