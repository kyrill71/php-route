<?php

namespace Kyrill\PhpRoute\Controller;

class TestController
{
    public function index($id, $second_id): void
    {
        var_dump($id, 'het werkt');
        var_dump($second_id, 'het werkt');
    }

    public function show(): void
    {
        echo 'show' . $_GET['id'];
    }
}
