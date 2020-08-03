<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class MessageException extends Exception
{
    protected $title;

    protected $description;

    /**
     * MessageException constructor.
     *
     * @param null           $title
     * @param null           $description
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($title = null, $description = null, $code = Response::HTTP_UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct(null, $code, $previous);

        $this->title = $title;

        $this->description = $description;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
