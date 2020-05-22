<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Sentence\SentenceService;
use App\Util\Response;
use Hyperf\Di\Annotation\Inject;

class SentenceController extends AbstractController
{
    /**
     * @Inject
     * @var SentenceService
     */
    private $sentenceService;

    public function match()
    {
        $fromSystem = $this->request->input('from_system', null);
        $type = $this->request->input('type', null);
        $sentence = $this->request->input('sentence', null);
        if (empty($fromSystem) || empty($type) || empty($sentence)) {
            return $this->response->json(Response::arr(500, '参数错误'));
        }
        $resultData = $this->sentenceService->match($sentence, $fromSystem, $type);
        return $this->response->json(Response::arr(200, '成功', $resultData));
    }
}