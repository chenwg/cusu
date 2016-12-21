<?php

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'article/s'=>['home/article/s',['method'=>'get']],
    //'article'=>['home/article/res',['method'=>'get','cache'=>3600]],
    'article/'=>['home/article/res',['method'=>'get']],
    'login'=>['admin/login/index',['method'=>'get']],
    'in'=>['admin/login/login',['method'=>'post']],
    'bj'=>['admin/edit/index',['method'=>'get']],
    'add'=>['admin/edit/add',['method'=>'post']],
    'delete'=>['admin/edit/delete', ['method' => 'get']],
    '/'=>['home/article/res',['method'=>'get']],
];
