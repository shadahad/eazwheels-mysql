<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function __construct(
        private readonly ItemRepositoryInterface $itemRepository
    ) {}

    public function index(Request $request): View
    {
        $query = (string) $request->query('q', '');
        $items = !empty($query) 
            ? $this->itemRepository->search($query) 
            : $this->itemRepository->getAll();

        return view('storefront.index', compact('items', 'query'));
    }
}