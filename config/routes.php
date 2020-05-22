<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::addRoute(['POST'], '/dic/dic/add', 'App\Controller\DicController@addWord');
Router::addRoute(['DELETE'], '/dic/dic/delete', 'App\Controller\DicController@removeWord');
Router::addRoute(['get'], '/dic/sentence/match', 'App\Controller\SentenceController@match');
Router::addRoute(['get'], '/dic/word/find', 'App\Controller\WordController@findWord');


