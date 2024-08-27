<?php
namespace App\Middlewares;
class QueryMiddleware implements BaseMiddleware
{
    public function handle()
    {
        if ($_GET['query'] !== 'test') {
            redirect('https://www.google.com');
        }
    }
}