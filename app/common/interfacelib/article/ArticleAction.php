<?php
declare(strict_types=1);
namespace app\common\interfacelib\article;

interface ArticleAction{
    public static function deleteArticle(int $id,string $en);
}