<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MessageResource extends JsonResponse
{
    const OK = 'ok';
    const ERROR = 'error';

    protected ?string $title;

    protected ?string $message;

    protected string $type;

    /**
     * ApiMessage constructor.
     *
     * @param string|null $title
     * @param string|null $message
     * @param int         $httpStatus
     */
    public function __construct(string $title = null, string $message = null, int $httpStatus = Response::HTTP_OK)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = self::OK;

        if ($httpStatus >= 400) {
            $this->type = self::ERROR;
        }

        parent::__construct([
            'type'    => $this->type,
            'message' => [
                'title'       => $this->title,
                'description' => $this->message,
            ],
        ], $httpStatus);
    }
}
