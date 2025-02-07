<?php

namespace App\Http\Controllers\V1;

use App\Actions\AddToCartAction;
use App\Actions\ClearCartAction;
use App\Actions\GetCartAction;
use App\Actions\RemoveFromCartAction;
use App\Actions\UpdateCartItemAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemToCartRequest;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    use ResponseTrait;

    #[OA\Post(
        path: "/cart/add",
        summary: "Adds a product to the cart",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["product_id", "quantity"],
                properties: [
                    new OA\Property(property: "product_id", description: "ID of the product to add to the cart", type: "integer", example: 15),
                    new OA\Property(property: "quantity", description: "Quantity to add to the cart", type: "integer", example: 2),
                ],
                type: "object"
            )
        ),
        tags: ["Cart"],
        parameters: [
            new OA\Parameter(
                name: "x-agent-id",
                description: "Unique agent ID for tracking guest users",
                in: "header",
                required: true,
                schema: new OA\Schema(type: "string", example: "agent-12345")
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: "Product successfully added to the cart."),
            new OA\Response(response: 400, description: "Error message"),
        ]
    )]
    public function add(AddItemToCartRequest $request, AddToCartAction $action): JsonResponse
    {
        try {
            $action->execute($request->payload());
            return $this->successResponse();
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    #[OA\Delete(
        path: "/cart/remove/{productId}",
        summary: "Removes a product from the cart",
        tags: ["Cart"],
        parameters: [
            new OA\Parameter(
                name: "x-agent-id",
                description: "Unique agent ID for tracking guest users",
                in: "header",
                required: true,
                schema: new OA\Schema(type: "string", example: "agent-12345")
            ),
            new OA\Parameter(
                name: "productId",
                description: "ID of the product to remove from the cart",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: "Product successfully removed from the cart."),
            new OA\Response(response: 400, description: "Error message"),
        ]
    )]
    public function remove(int $productId, RemoveFromCartAction $action): JsonResponse
    {
        $action->execute($productId);
        return $this->successResponse();
    }

    #[OA\Put(
        path: "/cart/update/{productId}/{quantity}",
        summary: "Updates the quantity of a product in the cart",
        tags: ["Cart"],
        parameters: [
            new OA\Parameter(
                name: "x-agent-id",
                description: "Unique agent ID for tracking guest users",
                in: "header",
                required: true,
                schema: new OA\Schema(type: "string", example: "agent-12345")
            ),
            new OA\Parameter(
                name: "productId",
                description: "ID of the product",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "quantity",
                description: "New quantity",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: "Product quantity successfully updated."),
            new OA\Response(response: 400, description: "Error message"),
        ]
    )]
    public function update(int $productId, int $quantity, UpdateCartItemAction $action): JsonResponse
    {
        $action->execute($productId, $quantity);
        return $this->successResponse();
    }

    #[OA\Delete(
        path: "/cart/flush",
        summary: "Clears the cart",
        tags: ["Cart"],
        parameters: [
            new OA\Parameter(
                name: "x-agent-id",
                description: "Unique agent ID for tracking guest users",
                in: "header",
                required: true,
                schema: new OA\Schema(type: "string", example: "agent-12345")
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: "Cart successfully cleared."),
            new OA\Response(response: 400, description: "Error message"),
        ]
    )]
    public function clear(ClearCartAction $action): JsonResponse
    {
        $action->execute();
        return $this->successResponse();
    }

    #[OA\Get(
        path: "/cart",
        summary: "Retrieves the cart",
        tags: ["Cart"],
        parameters: [
            new OA\Parameter(
                name: "x-agent-id",
                description: "Unique agent ID for tracking guest users",
                in: "header",
                required: true,
                schema: new OA\Schema(type: "string", example: "agent-12345")
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: "Cart details retrieved successfully."),
            new OA\Response(response: 400, description: "Error message"),
        ]
    )]
    public function get(GetCartAction $action): JsonResponse
    {
        return $this->successResponse(data: $action->execute());
    }
}
