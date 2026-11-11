<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /** Ubah alamat atau batalkan: hanya pemilik, dan hanya saat Menunggu. */
    public function modify(User $user, Order $order): Response
    {
        if ($order->user_id !== $user->id) {
            return Response::denyAsNotFound();   // milik orang lain dibalas 404
        }

        if ($order->status !== OrderStatus::Menunggu) {
            return Response::deny('Pesanan hanya dapat diubah atau dibatalkan saat berstatus Menunggu');   // 403
        }

        return Response::allow();
    }
}
