<?php

namespace src\Integration;

class DataProvider
{
    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        //TODO данные с доступами я бы хранил в отдельном конфиге, к примеру configs/externalService.php
        // в нем [ 'host'=>'localhost', 'user'=> 'mainUser', 'password'=>'123' ] и получал бы доступ к ним через методы - хелперы
        // к примеру use Support\Config Config::get('externalService.password'), таким образом мы бы избавились
        // от объявления лишних свойств в объекте DataProvider и переброску аргументов через дочерний класс DecoratorManager
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request)
    {
        // returns a response from external service
    }
}
