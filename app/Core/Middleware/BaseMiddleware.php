<?php

abstract class BaseMiddleware
{
    // Phương thức này sẽ được Router gọi trước khi cho phép Controller chạy
    abstract public function handle();
}