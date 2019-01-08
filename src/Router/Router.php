<?php

namespace App\Router;

class Router 
{
    private $collection = [];
    private  $method;
    private $path;

    public function __construct(string $method, string $path)
    {
       $this->collection = new RouterCollection;
       $this->method = $method;
       $this->path = $path;
    }

    public function get($path, $function)
    {
        $this->request('GET', $path, $function);
    }

    public function post($path, $function)
    {
        $this->request('POST', $path, $function);
    }

    public function request($method, $path, $function)
    {
        $this->collection->add($method, $path, $function);
    }

    private function checkUrl(string $toFind, $subject)
    {
        preg_match_all('/\{([^\}]*)\}/', $toFind, $variables);
        $regex = str_replace('/', '\/', $toFind);
        foreach ($variables[1] as $k => $variable) {
            $as = explode(':', $variable);
            $replacement = $as[1] ?? '([a-zA-Z0-9\-\_\ ]+)';
            $regex = str_replace($variables[$k], $replacement, $regex);
        }
        $regex = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $regex);
        $result = preg_match('/^' . $regex . '$/', $subject, $params);
        return compact('result', 'params');
    }   
    
    public function run()
    {
        $result = [];
        $data = $this->collection->filter($this->method);
        foreach ($data as $key => $value) {
            $result = $this->checkUrl($key, $this->path);
            $callback = $value;
            if ($result['result']) {
                break;
            }
        }
        if (!$result['result']) {
            $callback = null;
        }
        return [
            'params' => $result['params'],
            'callback' => $callback
        ];
    }

}
