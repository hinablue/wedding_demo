<?php

namespace Wedding\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \Flow\Config as FlowConfig;
use \Flow\Request as FlowRequest;
use \Flow\File as FlowFile;

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

      $flow_config = new FlowConfig();
      if (false === is_dir(ROOT . DS . 'chunks_temp_folder')) {
          mkdir(ROOT . DS . 'chunks_temp_folder', 0755);
      }
      $flow_config->setTempDir(ROOT . DS . 'chunks_temp_folder');

      $flow_file = new FlowFile($flow_config);

      if (true === $request->isGet()) {
          if ($flow_file->checkChunk()) {
            return $response->withJson([
              'status' => 'ok'
            ]);
          } else {
            return $response->withJson([
              'status' => 'ok'
            ], 204);
          }
      } elseif (true === $request->isPost()) {
        if ($flow_file->validateChunk()) {
          $flow_file->saveChunk();
          $flow_request = new FlowRequest(NULL, $flow_file);

          $filename = $flow_request->getFileName();
          $new_filename = sprintf('%s.%s',
            Uuid::uuid5(Uuid::NAMESPACE_DNS, microtime(true)),
            strtolower(pathinfo($filename, PATHINFO_EXTENSION))
          );

          if ($flow_file->validateFile()
              && $flow_file->save($folder . $new_filename)
          ) {
            return $response->withJson([
              'status' => 'ok',
              'messages' => 'Succeeded!',
              'files' => [
                [
                  'filename' => $new_filename,
                  'url' => $api_static . $new_filename
                ]
              ]
            ], 200);
          } else {
            return $response->withJson([
              'status' => 'ok'
            ], 204);
          }
        } else {
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
        }
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
