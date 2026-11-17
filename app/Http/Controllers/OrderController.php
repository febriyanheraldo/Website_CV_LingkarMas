<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\PesananTemplateExport;
use App\Imports\PesananImport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = OrderStatus::tryFrom((string) $request->status)?->value;
        $search = trim((string) $request->q);

        $items = Order::with('user')
            ->filterPesanan($status, $search)
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

    public function template()
    {
        return Excel::download(new PesananTemplateExport, 'template-import-pesanan.xlsx');
    }

    
public function import(Request $request, OrderService $service)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
    ], [
        'file.required' => 'File wajib dipilih.',
        'file.file' => 'Unggahan harus berupa file.',
        'file.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
        'file.max' => 'Ukuran file maksimal 2 MB.',
    ]);

    try {
        $import = new PesananImport($service);

        Excel::import($import, $request->file('file'));

        $pesan = 'Import selesai: '
            . $import->imported . ' pesanan ditambahkan, '
            . $import->updated . ' pesanan diperbarui';

        if ($import->unchanged > 0) {
            $pesan .= ', ' . $import->unchanged . ' pesanan tidak berubah';
        }

        $pesan .= '.';

        if ($import->skipped > 0) {
            $pesan .= ' ' . $import->skipped . ' baris dilewati.';
        }

        return redirect()->route('admin.orders')
            ->with('ok', $pesan)
            ->with('import_log', array_slice($import->log, 0, 15));

    } catch (\Throwable $e) {
        return redirect()->route('admin.orders')
            ->withErrors([
                'file' => 'Import gagal: ' . $e->getMessage(),
            ]);
   
             }
                      }
}