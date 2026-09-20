<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        private readonly ItemRepositoryInterface $itemRepository
    ) {}

    public function dashboard(Request $request): View
    {
        $items = $this->itemRepository->getAll();
        $adminKey = $request->input('admin_key') ?? session('admin_key', '');

        return view('admin.dashboard', compact('items', 'adminKey'));
    }

    public function store(StoreItemRequest $request): RedirectResponse
    {
        $adminKey = $request->input('admin_key');
        $this->itemRepository->create($request->validated());

        return redirect()
            ->route('admin.dashboard', ['admin_key' => $adminKey])
            ->with('status', 'Item successfully cataloged via pure SQL.');
    }

    public function updateStock(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'unitsInStock' => ['required', 'integer', 'min:0'],
            'admin_key' => ['required', 'string']
        ]);

        $this->itemRepository->updateStock($id, (int) $request->input('unitsInStock'));

        return redirect()
            ->route('admin.dashboard', ['admin_key' => $request->input('admin_key')])
            ->with('status', 'Stock level successfully adjusted.');
    }
}