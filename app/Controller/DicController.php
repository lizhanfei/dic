<?php

declare(strict_types=1);

namespace App\Controller;

use App\Util\Response;
use App\Service\Dic\PersistentService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

/**
 * 词典维护
 * Class DicController
 * @package App\Controller
 */
class DicController extends AbstractController
{
    /**
     * @var PersistentService
     */
    private $persistentService;

    public function __construct(PersistentService $persistentService, ContainerInterface $container, RequestInterface $request, ResponseInterface $response)
    {
        $this->persistentService = $persistentService;
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * 添加词语
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function addWord()
    {
        $fromSystem = $this->request->input('from_system', null);
        $type = $this->request->input('type', null);
        $word = $this->request->input('word', null);

        if (empty($fromSystem) || empty($type) || empty($word)) {
            return $this->response->json(Response::arr(500, '参数错误'));
        }

        if ($this->persistentService->add($word, $fromSystem, $type)) {
            return $this->response->json(Response::arr(200, '保存成功'));
        } else {
            return $this->response->json(Response::arr(500, '保存词语失败'));
        }
    }

    /**
     * 移除词语
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function removeWord()
    {
        $fromSystem = $this->request->input('from_system', null);
        $type = $this->request->input('type', null);
        $word = $this->request->input('word', null);

        if (empty($fromSystem) || empty($type) || empty($word)) {
            return $this->response->json(Response::arr(500, '参数错误'));
        }

        if ($this->persistentService->del($word, $fromSystem, $type)) {
            return $this->response->json(Response::arr(200, '移除成功'));
        } else {
            return $this->response->json(Response::arr(500, '移除词语失败'));
        }
    }
}