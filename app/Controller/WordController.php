<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Word\WordService;
use App\Util\Response;
use Hyperf\Di\Annotation\Inject;

class WordController extends AbstractController
{
    /**
     * @Inject
     * @var WordService
     */
    private $wordService;

    public function findWord()
    {
        $fromSystem = $this->request->input('from_system', null);
        $type = $this->request->input('type', null);
        $word = $this->request->input('word', null);
        if (empty($fromSystem) || empty($type) || empty($word)) {
            return $this->response->json(Response::arr(500, '参数错误'));
        }
        $resultData = $this->wordService->find($word, $fromSystem, $type);
        return $this->response->json(Response::arr(200, '成功', $resultData));
    }
}