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

return [
    App\Util\Dic\WordStorage::class => App\Util\Dic\WordMemory::class,
    App\Service\Dic\DicService::class => App\Service\Dic\DicServiceImplV1::class,
    App\Service\Sentence\SentenceService::class => App\Service\Sentence\SentenceServiceImplV1::class,
    App\Service\Word\WordService::class => App\Service\Word\WordServiceImplV1::class,
    App\Dao\Word\WordDao::class => App\Dao\Word\WordDaoImplV1::class
];
