<?php

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'article/s'=>['home/article.article/s',['method'=>'get']],
    //'article'=>['home/article/res',['method'=>'get','cache'=>3600]],
    'article/'=>['home/article.article/res',['method'=>'get']],
    'img'=>['api/img/res',['method'=>'get']],
    'cate'=>['admin/cate.cate/index',['method'=>'get']],
    'login'=>['admin/login.login/index',['method'=>'get']],
    'in'=>['admin/login.login/login',['method'=>'post']],
    'bj'=>['admin/article.edit/index',['method'=>'get']],
    'add'=>['admin/article.edit/add',['method'=>'post']],
    'fileupload'=>['admin/article.edit/img_upload',['method'=>'post']],
    'delete'=>['admin/article.edit/delete', ['method' => 'get']],
    '/'=>['home/article.article/res',['method'=>'get']],
];
