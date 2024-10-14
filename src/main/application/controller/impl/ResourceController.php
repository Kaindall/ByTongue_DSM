<?php
require_once 'src\main\domain\model\request\HttpRequest.php';
require_once 'src\main\application\controller\Controller.php';


#[HttpController('/resources')]
class ResourceController implements Controller {
    #[HttpEndpoint(uri: "/styles/{file}", method: "GET")]
    public function serveCssContent(HttpRequest $request) {
        $directory = 'src/resources/style';  
        $directoryIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $file) {
            if ($file->isDir() || 
                !str_ends_with($file->getFilename(), ".css")) {
                    continue;
                }

            if ($file->getFilename() == $request->getPathParams()["file"]) {
                header("Content-Type: text/css");
                http_response_code(200);
                return file_get_contents($file->getPathname());
            }
        }

        http_response_code(404);
        return "Arquivo não encontrado!";
    }

    #[HttpEndpoint(uri: "/scripts/{file}", method: "GET")]
    public function serveJsContent(HttpRequest $request) {
        $directory = 'src\main\application\web\view';  
        $directoryIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $file) {
            if ($file->isDir() || 
                !str_ends_with($file->getFilename(), ".js")) {
                    continue;
                }

            if ($file->getFilename() == $request->getPathParams()["file"]) {
                header("Content-Type: application/javascript");
                http_response_code(200);
                return file_get_contents($file->getPathname());
            }
        }

        http_response_code(404);
        return "Arquivo não encontrado!";
    }

    #[HttpEndpoint(uri: "/static/{file}", method: "GET")]
    public function serveStaticContent(HttpRequest $request) {
        $directory = 'src/resources/static';  
        $directoryIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                    continue;
                }

            if ($file->getFilename() == $request->getPathParams()["file"]) {
                http_response_code(200);
                return file_get_contents($file->getPathname());
            }
        }

        http_response_code(404);
        return "Arquivo não encontrado!";
    }

    public function fallback(HttpRequest $request) {
        return 'Fallback do ResourceController';
    }
}