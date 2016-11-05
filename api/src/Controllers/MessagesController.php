<?php

namespace Wedding\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Ramsey\Uuid\Uuid;

class MessagesController extends BaseController {

  public function query(Request $request, Response $response) {
    try {
      if ($request->isOptions()) {
        return $response->withStatus(204);
      }
      $page = $request->getQueryParams()['page'];
      if (empty($page)) {
        $page = 1;
      }

      $table = $this->db->table('messages');
      $total_items = $table->where('status', '=', 'published')
        ->count();
      $total_pages = ceil($total_items / 15);

      $messages = $table->select([
          'id',
          'fb_app_id',
          'name',
          'content',
          'file',
          'url',
          'created_at'
        ])
        ->where('status', '=', 'published')
        ->orderBy('created_at', 'desc')
        ->forPage((int) $page, 15)
        ->get();

        if (count($messages) > 0) {
          $messages = $messages->toArray();
          array_walk($messages, function(&$item) {
            $item = (array) $item;
            $item = array_combine(
              array_map(function($key) {
                  return lcfirst(
                      str_replace(
                          ' ',
                          '',
                          ucwords(str_replace('_', ' ', $key))
                      )
                  );
              }, array_keys($item)), $item);

            if (!empty($item['file'])) {
              $item['file'] = '/content/'. $item['file'];
            }
            $item['content'] = nl2br(strip_tags($item['content']));
          });

          return $response->withJson([
            'status' => 'ok',
            'messages' => 'Succeeded!',
            'results' => [
              'current' => (int) $page,
              'next' => ((int) $page + 1) >= $total_pages ? (int) $page + 1 : $total_pages,
              'before' => ((int) $page - 1) <= 0 ? 1 : (int) $page - 1,
              'first' => 1,
              'last' => $total_pages,
              'items' => $messages,
              'totalItems' => $total_items,
              'totalPages' => $total_pages
            ]
          ]);
        } else {
          return $response->withJson([
            'status' => 'err',
            'messages' => 'Page not found.',
            'results' => []
          ], 404);
        }
    } catch (\Throwable $t) {
      $this->logger->addInfo($t);
    }

    return $response->withJson([
      'status' => 'err',
      'results' => []
    ], 404);
  }

  public function add(Request $request, Response $response) {
    try {
      if ($request->isOptions()) {
        return $response->withStatus(204);
      }

      $data = $request->getParsedBody();

      if (!isset($data['id']) ||
        !isset($data['messages']) ||
        empty($data['messages'])
      ) {
        return $response->withJson([
          'status' => 'err',
          'results' => []
        ], 405);
      }
      $today = date('Y-m-d H:i:s');
      $table = $this->db->table('messages');
      $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, microtime(true));

      $messages = trim($data['messages']);
      $raw_data = [
        'id' => $uuid,
        'fb_app_id' => $data['id'],
        'name' => $data['name'],
        'content' => nl2br($messages),
        'url' => trim($data['url']),
        'file' => $data['file'],
        'created_at' => $today,
        'updated_at' => $today
      ];
      if (true === $table->insert($raw_data)) {
        return $response->withJson([
          'status' => 'ok',
          'results' => array_merge($data, [
            'id' => $uuid,
            'file' => '/content/' . $data['file'],
            'fbAppId' => $data['id'],
            'createdAt' => $today
          ])
        ]);
      }
    } catch (\Throwable $t) {
      $this->logger->addInfo($t);
    }

    return $response->withJson([
      'status' => 'err',
      'results' => []
    ], 405);
  }

  public function dispatch(Request $request, Response $response) {
    try {
      if ($request->isOptions()) {
        return $response->withStatus(204);
      }

      if ($request->isGet()) {
        return $this->query($request, $response);
      } elseif ($request->isPost()) {
        return $this->add($request, $response);
      }
      return $response->withJson([
        'status' => 'err',
        'results' => []
      ], 405);
    } catch (\Throwable $t) {
      $this->logger->addInfo($t);
    }

    return $response->withJson([
      'status' => 'err',
      'results' => []
    ], 404);
  }
}
