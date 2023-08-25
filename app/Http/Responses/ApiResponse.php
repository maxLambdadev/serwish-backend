<?php

namespace App\Http\Responses;

/**
 *
 * @OA\Schema(
 *     description="Api response",
 *     title="Api response"
 * )
 */
class ApiResponse
{

    /**
     * @OA\Property(
     *     description="Code",
     *     title="Code",
     *     format="int32"
     * )
     *
     * @var int
     */
    private $code;


    /**
     * OA\Property(
     *    description="Message",
     *    title="Message",
     * )
     *
     * @var string
     */
    private $message;

    /**
     * @OA\Property(
     *     description="Body",
     *     title="Body"
     * )
     *
     * @var string
     */
    private $body;
}
