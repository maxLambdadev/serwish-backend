<?php

namespace App\Http\Responses;

/**
 *
 * @OA\Schema(
 *     description="Auth response",
 *     title="Api response"
 * )
 */
class ApiAuthResponse
{

    /**
     * @OA\Property(
     *     description="Token Type",
     *     title="token_type",
     *     format="string"
     * )
     *
     * @var string
     */
    private $token_type;


    /**
     * OA\Property(
     *    description="Expires in",
     *    title="expires_in",
     * )
     *
     * @var int
     */
    private $expires_in;

    /**
     * @OA\Property(
     *     description="access_token",
     *     title="access_token"
     * )
     *
     * @var string
     */
    private $access_token;

    /**
     * @OA\Property(
     *     description="refresh_token",
     *     title="refresh_token"
     * )
     *
     * @var string
     */
    private $refresh_token;
}
