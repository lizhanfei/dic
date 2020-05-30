<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Sentence\SentenceService;
use App\Util\Response;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

class SentenceController extends AbstractController
{
    /**
     * @var SentenceService
     */
    private $sentenceService;

    public function __construct(SentenceService $sentenceService, ContainerInterface $container, RequestInterface $request, ResponseInterface $response)
    {
        $this->sentenceService = $sentenceService;
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

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