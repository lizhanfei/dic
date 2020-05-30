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
    //todo 这里生产部署的时候要去掉注释，因为ci执行单元测试的原因，这里暂时注释
    App\Listener\AfterWorkStartListener::class
];
