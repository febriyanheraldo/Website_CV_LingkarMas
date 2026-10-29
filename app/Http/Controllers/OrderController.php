<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = OrderStatus::tryFrom((string) $request->status)?->value;
        $search = trim((string) $request->q);

        $items = Order::with('user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->whereHas('user', fn ($u) => $u->where('nama', 'like', "%{$search}%"));
                    if (preg_match('/^(?:LM-?)?0*(\d+)$/i', $search, $m)) {
                        $w->orWhere('id', (int) $m[1]);
                    }
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', ['items' => $items, 'status' => $status, 'q' => $search]);
    }

    public function show(Order $order)
    {
        $order->load('user');

        return view('admin.orders.show', ['order' => $order]);
    }

    public function updateStatus(Request $request, Order $order, OrderService $service)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.enum' => 'Status tidak valid.',
        ]);

        $service->changeStatus($order, OrderStatus::from($data['status']));

        return back()->with('ok', "Pesanan {$order->kode} berhasil diubah menjadi {$data['status']}.");
    }
}
