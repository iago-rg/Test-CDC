<?php

namespace Config;

use App\Helpers\MessageHandler;

final class ResponseTrait
{
    //phpcs:ignoreFile
    public static function Error(string $code, array $errors = [])
    {
        $messageHandler = new MessageHandler();
        $messagePayload = $messageHandler->getMessage($code);

        $response = [
            'success' => false,
            'message' => $messagePayload['message'],
            'code' => $messagePayload['code'],
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        $responseService = service('response');
        $responseService->setJSON($response);
        $responseService->setStatusCode($messagePayload['status_code']);
        $responseService->send();
        die;
    }

    public static function Success(string $code, array $data = [])
    {
        $messageHandler = new MessageHandler();
        $messagePayload = $messageHandler->getMessage($code);

        $response = [
            'success' => true,
            'message' => $messagePayload['message'],
            'code' => $messagePayload['code'],
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        $responseService = service('response');
        $responseService->setJSON($response);
        $responseService->setStatusCode($messagePayload['status_code']);
        $responseService->send();
        die;
    }
}
