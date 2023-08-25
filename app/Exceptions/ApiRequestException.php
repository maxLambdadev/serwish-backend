<?php

namespace App\Exceptions;

/**
 * @OA\Schema()
 */
class ApiRequestException extends \Exception
{
    /**
     * The err message
     * @var string
     *
     * @OA\Property(
     *   property="message",
     *   type="string",
     *   example="Not Found"
     * )
     */
    public function __construct(string $message = null)
    {
        parent::__construct("resource not found", $message ?? Response::$statusTexts[self::NO_FOUND_ERROR]);
    }
}
