<?php

namespace Wedding\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;

class FilesController extends BaseController {
  public function dispatch(Request $request, Response $response) {
    try {
      if ($request->isOptions()) {
        return $response->withStatus(204);
      }

      $api_static = date('Ym') . DS;
      $folder = ROOT . DS . 'content' . DS . $api_static;

      if (!file_exists($folder)) {
        mkdir($folder, 0755, true);
      }

      $uploaded_files = [];
      foreach ($request->getUploadedFiles() as $file) {
        $new_filename = sprintf('%s.%s',
          Uuid::uuid5(Uuid::NAMESPACE_DNS, microtime(true)),
          strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION))
        );

        $full_path = $folder . $new_filename;
        $file->moveTo($full_path);

        if (file_exists($full_path)) {
          array_push($uploaded_files, [
            'filename' => $new_filename,
            'url' => $api_static . $new_filename
          ]);
        }
      }

      if (count($uploaded_files) === 0) {
        return $response->withJson([
          'status' => 'err',
          'messages' => 'File upload failed!'
        ], 405);
      } else {
        return $response->withJson([
          'status' => 'ok',
          'messages' => 'Succeeded!',
          'files' => $uploaded_files
        ], 200);
      }
    } catch (\Throwable $t) {
      $this->logger->addInfo($t);
    }

    return $response->withJson([
      'status' => 'err',
      'messages' => 'File upload failed!'
    ], 405);
  }
}
