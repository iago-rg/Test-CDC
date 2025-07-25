<?php

namespace App\Helpers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class MessageHandler
{
    protected $CI;
    public function __construct()
    {
    }


    public function getMessage(string $messageCode): array
    {

        $messagesPath = APPPATH . 'Messages'; // Mensagens Globais
        $iterator = $this->setInteractor($messagesPath);

        if ($message = $this->searchMessage($iterator, $messageCode)) {
            return $message;
        }

        $messagesPath = APPPATH . 'Modules'; // Mensagens por Módulo
        $iterator =  $this->setInteractor($messagesPath);

        if ($message = $this->searchMessage($iterator, $messageCode)) {
            return $message;
        }

        exit('Código não encontrado');
    }

    private function searchMessage(RecursiveIteratorIterator $iterator, string $messageCode): array|bool
    {
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                if (str_contains($file->getPathName(), 'Messages')) {
                    require $file->getPathname();
                    if (isset($arrayCodes[$messageCode])) {
                        return [
                            'status_code' => $arrayCodes[$messageCode][1],
                            'message' => $arrayCodes[$messageCode][0],
                            'code' => $messageCode,
                        ];
                    }
                }
            }
        }

        return false;
    }

    private function setInteractor(string $messagesPath): RecursiveIteratorIterator
    {
        $directory = new RecursiveDirectoryIterator($messagesPath);
        return new RecursiveIteratorIterator($directory);
    }
}
