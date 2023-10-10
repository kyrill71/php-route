<?php

namespace Kyrill\PhpRoute\Controller;

class TestController
{
    public function index($id): void
    {
        var_dump($id, 'het werkt');
    }

    public function show(): void
    {
        echo 'show' . $_GET['id'];
    }
}