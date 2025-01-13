<?php

namespace App\Console\Commands;

use App\Mail\ReminderMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    protected $signature = 'email:send-reminders';
    protected $description = 'Send reminder emails to users based on a specific date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Lấy danh sách các dịch vụ sắp hết hạn
        $expiringOrders = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->select('orders.email', 'orders.fullname', 'orders.code', 'order_details.active_at', 'order_details.number', 'order_details.type', 'order_details.product_id', 'order_details.type')
            ->where('order_details.active_at', '!=', null)
            ->whereRaw("DATE_ADD(order_details.active_at, INTERVAL order_details.number MONTH) <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)")
            ->whereRaw("DATE_ADD(order_details.active_at, INTERVAL order_details.number MONTH) >= CURDATE()")
            ->orWhereRaw("DATE_ADD(order_details.active_at, INTERVAL order_details.number MONTH) <= CURDATE()") 
            ->get();


        foreach ($expiringOrders as $order) {
            $expireDate = Carbon::parse($order->active_at)->addMonths($order->number)->format('Y-m-d');

            if ($expireDate >= now()->format('Y-m-d') && $expireDate <= now()->addDays(30)->format('Y-m-d')) {

                $details = [
                    'title' => 'Thông báo hết hạn dịch vụ',
                    'message' => "Kính gửi {$order->fullname}, <p>Dịch vụ của bạn với mã {$order->code} sẽ hết hạn vào ngày {$expireDate}</p>.",
                ];
            } else {
                // Dịch vụ đã hết hạn
                $details = [
                    'title' => 'Thông báo hết hạn dịch vụ',
                    'message' => "Kính gửi {$order->fullname}, <p> Dịch vụ của bạn với mã {$order->code} đã hết hạn vào ngày {$expireDate}.</p>",
                ];
            }

            try {
                Mail::to($order->email)->send(new ReminderMail($details));
                $this->info("Email đã được gửi tới {$order->email} cho đơn hàng {$order->code}");
            } catch (\Exception $e) {
                $this->error("Gửi email thất bại tới {$order->email}: " . $e->getMessage());
            }
        }

        $this->info('Tất cả email nhắc nhở đã được gửi.');
    }
}
