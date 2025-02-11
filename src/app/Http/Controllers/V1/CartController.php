<?php

namespace App\Http\Controllers\V1;

use App\Actions\{AddToCartAction, ClearCartAction, GetCartAction, RemoveFromCartAction, UpdateCartItemAction};
use App\Exceptions\{CartItemNotFoundException,ProductStockNotEnoughException};
use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemToCartRequest;
use App\Http\Resources\CartResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    #[OA\Post(
        path: "/api/v1/cart",
        summary: "Adds a product to the cart",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["product_id", "quantity"],
                properties: [
                    new OA\Property(property: "product_id", description: "ID of the product to add to the cart", type: "integer", example: 101),
                    new OA\Property(property: "quantity", description: "Quantity to add to the cart", type: "integer", example: 1),
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
            new OA\Response(response: Response::HTTP_CREATED, description: "Product successfully added to the cart."),
        ]
    )]
    public function store(AddItemToCartRequest $request, AddToCartAction $action): JsonResponse
    {
        $action->execute($request->payload());
        return $this->successResponse(status: Response::HTTP_CREATED);
    }


    #[OA\Delete(
        path: "/api/v1/cart/{productId}",
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
                schema: new OA\Schema(type: "integer",example: 101)
            ),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_NO_CONTENT, description: "Product successfully removed from the cart."),
        ]
    )]
    public function destroy(int $productId, RemoveFromCartAction $action): JsonResponse
    {
        $action->execute($productId);
        return $this->successResponse(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws \Throwable
     * @throws ProductStockNotEnoughException
     * @throws CartItemNotFoundException
     */
    #[OA\Put(
        path: "/api/v1/cart/{productId}/{quantity}",
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
            new OA\Parameter(name: "productId", description: "ID of the product", in: "path", required: true, schema: new OA\Schema(type: "integer"), example: 101),
            new OA\Parameter(name: "quantity", description: "New quantity", in: "path", required: true, schema: new OA\Schema(type: "integer"), example: 1),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Product quantity successfully updated."),
        ]
    )]
    public function update(int $productId, int $quantity, UpdateCartItemAction $action): JsonResponse
    {
        $action->execute($productId, $quantity);
        return $this->successResponse();
    }

    #[OA\Delete(
        path: "/api/v1/cart/flush",
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
            new OA\Response(response: Response::HTTP_NO_CONTENT, description: "Cart successfully cleared."),
        ]
    )]
    public function clear(ClearCartAction $action): JsonResponse
    {
        $action->execute();
        return $this->successResponse();
    }

    #[OA\Get(
        path: "/api/v1/cart",
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
            new OA\Response(response: Response::HTTP_OK, description: "Cart details retrieved successfully."),
        ]
    )]
    public function index(GetCartAction $action): CartResource
    {
        return (new CartResource($action->execute()))->additional(['success' => true]);
    }

}
