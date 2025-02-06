<?php

namespace App\Http\Controllers\V1;

use App\Actions\Cart\AddToCartAction;
use App\Actions\Cart\RemoveFromCartAction;
use App\Actions\Cart\UpdateCartItemAction;
use App\Actions\Cart\ClearCartAction;
use App\Actions\Cart\GetCartAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddItemToCartRequest;
use App\Services\CartService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CartController extends Controller
{
    use ResponseTrait;

    public function __construct(protected CartService $cartService)
    {}

    /**
     * @OA\Post(
     *     path="/cart/add",
     *     summary="Adds a product to the cart",
     *     tags={"Cart"},
     *     @OA\Parameter(
     *         name="x-agent-id",
     *         in="header",
     *         description="Unique agent ID for tracking guest users",
     *         required=true,
     *         @OA\Schema(type="string", example="agent-12345")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"product_id", "quantity"},
     *             @OA\Property(property="product_id", type="integer", example=15, description="ID of the product to add to the cart"),
     *             @OA\Property(property="quantity", type="integer", example=2, description="Quantity to add to the cart")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Product successfully added to the cart."),
     *     @OA\Response(response=400, description="Error message")
     * )
     */
    public function add(AddItemToCartRequest $request, AddToCartAction $action): JsonResponse
    {
        try {
            $action->execute($request->payload());
            return $this->successResponse();
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/cart/remove/{productId}",
     *     summary="Removes a product from the cart",
     *     tags={"Cart"},
     *     @OA\Parameter(
     *         name="x-agent-id",
     *         in="header",
     *         description="Unique agent ID for tracking guest users",
     *         required=true,
     *         @OA\Schema(type="string", example="agent-12345")
     *     ),
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID of the product to remove from the cart",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Product successfully removed from the cart."),
     *     @OA\Response(response=400, description="Error message")
     * )
     */
    public function remove(int $productId, RemoveFromCartAction $action): JsonResponse
    {
        $action->execute($productId);
        return $this->successResponse();
    }


    /**
     * @OA\Put(
     *     path="/cart/update/{productId}/{quantity}",
     *     summary="Updates the quantity of a product in the cart",
     *     tags={"Cart"},
     *     @OA\Parameter(
     *         name="x-agent-id",
     *         in="header",
     *         description="Unique agent ID for tracking guest users",
     *         required=true,
     *         @OA\Schema(type="string", example="agent-12345")
     *     ),
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="quantity",
     *         in="path",
     *         description="New quantity",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Product quantity successfully updated."),
     *     @OA\Response(response=400, description="Error message")
     * )
     */
    public function update(int $productId, int $quantity, UpdateCartItemAction $action): JsonResponse
    {
        $action->execute($productId, $quantity);
        return $this->successResponse();
    }

    /**
     * @OA\Delete(
     *     path="/cart/flush",
     *     summary="Clears the cart",
     *     tags={"Cart"},
     *     @OA\Parameter(
     *         name="x-agent-id",
     *         in="header",
     *         description="Unique agent ID for tracking guest users",
     *         required=true,
     *         @OA\Schema(type="string", example="agent-12345")
     *     ),
     *     @OA\Response(response=200, description="Cart successfully cleared."),
     *     @OA\Response(response=400, description="Error message")
     * )
     */
    public function clear(ClearCartAction $action): JsonResponse
    {
        $action->execute();
        return $this->successResponse();
    }

    /**
     * @OA\Get(
     *     path="/cart",
     *     summary="Retrieves the cart",
     *     tags={"Cart"},
     *     @OA\Parameter(
     *         name="x-agent-id",
     *         in="header",
     *         description="Unique agent ID for tracking guest users",
     *         required=true,
     *         @OA\Schema(type="string", example="agent-12345")
     *     ),
     *     @OA\Response(response=200, description="Cart details retrieved successfully."),
     *     @OA\Response(response=400, description="Error message")
     * )
     */
    public function get(GetCartAction $action): JsonResponse
    {
        return $this->successResponse(data:$action->execute());
    }
}
