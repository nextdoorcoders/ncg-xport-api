<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MessageResource extends JsonResponse
{
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const DANGER = 'danger';

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
        $this->type = self::SUCCESS;

        if ($httpStatus >= 400) {
            $this->type = self::WARNING;
        }

        if ($httpStatus >= 500) {
            $this->type = self::DANGER;
        }

        parent::__construct([
            'message' => [
                'title'       => $this->title,
                'description' => $this->message,
                'type'        => $this->type,
            ],
        ], $httpStatus);
    }
}
