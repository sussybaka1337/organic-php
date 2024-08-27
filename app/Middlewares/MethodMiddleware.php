<?php
namespace App\Middlewares;
class MethodMiddleware implements BaseMiddleware
{
    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET')
        {
            redirect('https://www.google.com');
        }
    }
}