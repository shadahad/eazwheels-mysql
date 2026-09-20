<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemApiController extends Controller
{
    public function __construct(
        private readonly ItemRepositoryInterface $itemRepository
    ) {}

    public function store(StoreItemRequest $request): JsonResponse
    {
        try {
            $item = $this->itemRepository->create($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => $item->toArray()
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error while persisting entity.'
            ], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('q');
        if (!empty($search)) {
            $items = $this->itemRepository->search((string) $search);
        } else {
            $items = $this->itemRepository->getAll();
        }

        return response()->json([
            'status' => 'success',
            'data' => array_map(fn($item) => $item->toArray(), $items)
        ], 200);
    }
}