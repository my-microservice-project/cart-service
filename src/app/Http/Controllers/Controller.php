<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Cart-Service API",
 *     version="1.0.0",
 *     description="This API was developed for e-commerce cache based cart management.",
 *     @OA\Contact(
 *         email="bugrabozkurtt@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8084/api/v1",
 *     description="Local API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    use ResponseTrait;
}
