<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $adminKey = $request->input('admin_key') ?? session('admin_key', '');
        $data = $request->validated();

        if ($request->hasFile('itemImage')) {
            $file = $request->file('itemImage');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $targetDir = public_path('uploads/items');
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            copy($file->getRealPath(), $targetDir . DIRECTORY_SEPARATOR . $filename);
            $data['itemImage'] = '/uploads/items/' . $filename;
        }

        unset($data['admin_key']);

        $this->itemRepository->create($data);

        return redirect()
            ->route('admin.dashboard', ['admin_key' => $adminKey])
            ->with('status', 'Item successfully cataloged with uploaded image.');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $adminKey = $request->input('admin_key') ?? session('admin_key', '');

        $data = $request->validate([
            'itemName'     => ['required', 'string', 'max:255'],
            'itemImage'    => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'cost'         => ['required', 'numeric', 'min:0.01'],
            'size'         => ['required', 'numeric', 'min:1', 'max:50'],
            'unitsInStock' => ['required', 'integer', 'min:0'],
            'description'  => ['required', 'string'],
        ]);

        // Process new image upload if provided
        if ($request->hasFile('itemImage')) {
            $file = $request->file('itemImage');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $targetDir = public_path('uploads/items');
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            copy($file->getRealPath(), $targetDir . DIRECTORY_SEPARATOR . $filename);
            $data['itemImage'] = '/uploads/items/' . $filename;
        } else {
            // Keep the existing image from database if no new file was uploaded
            $existing = DB::selectOne("SELECT item_image FROM items WHERE id = ? LIMIT 1", [$id]);
            $data['itemImage'] = $existing->item_image ?? null;
        }

        unset($data['admin_key']);

        // Pass to repository for update
        $this->itemRepository->update($id, $data);

        return redirect()
            ->route('admin.dashboard', ['admin_key' => $adminKey])
            ->with('status', "Item '{$data['itemName']}' was successfully updated.");
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