<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider
{
    public $cache;
    public $logger;


    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
    {
        parent::__construct($host, $user, $password);
        //TODO комментарии по поводу данных доступа $host, $user, $password в классе DataProvider

        $this->cache = $cache;
        //TODO кеширование и логирование наверняка используются не только в данном объекте
        // и передавать их как аргументы не очень удобно, было бы лучше сделать как-то так
        // use Support/Cache
        // use Support/Logger
        //  $this->cache = new Cache;
        //  $this->logger = new Logger;
        // либо работать через статичные методы соответствующих классов
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        //TODO $logger лучше перенести в __construct, чтобы не вызывать метод вручную
    }

    /**
     * {@inheritdoc}
     */
    //TODO непонятный метод {@inheritdoc} не нашел его в коде
    public function getResponse(array $input)
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
            //TODO здесь бы я заменил вывод собщения 'error' на более понятное сообщение
            // $this->logger->critical($e->getMessage());
            // или $this->logger->critical('ошибка подключения к external service');
        }

        return [];
    }

    public function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}
