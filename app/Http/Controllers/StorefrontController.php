<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function enquire(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'vehicle_notes'  => ['nullable', 'string', 'max:1000'],
            'items'          => ['required', 'array', 'min:1'],
            'total'          => ['required', 'numeric', 'min:0'],
        ]);

        $items = $validated['items'];
        $total = number_format((float) $validated['total'], 2);
        $customerName = $validated['customer_name'];
        $customerEmail = $validated['customer_email'];
        $notes = $validated['vehicle_notes'] ?? 'None provided';
        $timestamp = now()->setTimezone('Europe/London')->toDayDateTimeString();

        // 1. Build the Itemized Email Body
        $body = "====================================================\n";
        $body .= "   EAZWHEELS CUSTOMER BASKET ENQUIRY SPECIFICATION\n";
        $body .= "====================================================\n\n";
        $body .= "Time:            {$timestamp} (UK Local Time)\n";
        $body .= "Customer Name:   {$customerName}\n";
        $body .= "Customer Email:  {$customerEmail}\n";
        $body .= "Vehicle / Notes: {$notes}\n\n";
        $body .= "----------------------------------------------------\n";
        $body .= "ITEMIZED BASKET BREAKDOWN:\n";
        $body .= "----------------------------------------------------\n";

        foreach ($items as $index => $item) {
            $num = $index + 1;
            $name = $item['name'] ?? 'Unnamed Item';
            $key = $item['id'] ?? 'N/A';
            $size = $item['size'] ?? 'N/A';
            $qty = $item['qty'] ?? 1;
            $cost = number_format((float) ($item['cost'] ?? 0), 2);
            $lineTotal = number_format((float) (($item['cost'] ?? 0) * $qty), 2);

            $body .= "{$num}. {$name}\n";
            $body .= "   • Item Key (UUID): {$key}\n";
            $body .= "   • Rim Size:        {$size} Inches\n";
            $body .= "   • Quantity:        {$qty}\n";
            $body .= "   • Unit Price:      £{$cost}\n";
            $body .= "   • Line Subtotal:   £{$lineTotal}\n\n";
        }

        $body .= "----------------------------------------------------\n";
        $body .= "ESTIMATED BASKET TOTAL: £{$total} (Inc UK VAT)\n";
        $body .= "====================================================\n\n";
        $body .= "Reply directly to this email to contact the customer.";

        // 2. Dispatch Email to info@eazwheels.co.uk
        try {
            Mail::raw($body, function ($message) use ($customerEmail, $customerName, $items) {
                $message->to('info@eazwheels.co.uk')
                        ->replyTo($customerEmail, $customerName)
                        ->subject("New Basket Fitment Enquiry: " . count($items) . " Item(s) from {$customerName}");
            });
        } catch (\Throwable $e) {
            // Log fallback if local mail server is in development mode
            Log::info("Enquiry Mail logged for info@eazwheels.co.uk: " . $e->getMessage(), ['body' => $body]);
        }

        return response()->json([
            'status' => 'success',
            'message' => "Your enquiry has been dispatched to info@eazwheels.co.uk with all itemized specifications."
        ]);
    }
}