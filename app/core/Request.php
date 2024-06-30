<?php

namespace core;

abstract class Request
{
    abstract public static function getPreparedData(array $data, $key);
}