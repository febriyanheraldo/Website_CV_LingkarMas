<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $counts = DB::table('orders')->selectRaw('status, COUNT(*) as n')->groupBy('status')->pluck('n', 'status');

        return view('admin.dashboard', [
            'stat' => [
                'Menunggu' => $counts['Menunggu'] ?? 0,
                'Diproses' => $counts['Diproses'] ?? 0,
                'Dikirim' => $counts['Dikirim'] ?? 0,
                'Klien' => User::where('role', 'klien')->count(),
            ],
            'recent' => Order::with('user')->latest()->take(5)->get(),
            'low' => Product::where('stok', '<=', 5)->orderBy('stok')->get(),
        ]);
    }

    public function clients()
    {
        return view('admin.clients.index', [
            'items' => User::where('role', 'klien')->withCount('orders')->latest()->paginate(10),
        ]);
    }

    public function clientShow(User $user)
    {
        abort_if($user->role !== 'klien', 404);

        return view('admin.clients.show', [
            'user' => $user,
            'orders' => $user->orders()->latest()->get(),
        ]);
    }
}
