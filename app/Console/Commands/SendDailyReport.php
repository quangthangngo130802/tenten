<?php

namespace App\Console\Commands;

use App\Mail\DailyReportMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReport extends Command
{
    protected $signature = 'email:daily-report';
    protected $description = 'Gửi báo cáo hằng ngày qua email';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::get();

        foreach ($users as $user) {
            $data = ['message' => "Xin chào {$user->name}, đây là báo cáo của bạn."];
            Mail::to($user->email)->send(new DailyReportMail($data));
        }

        $this->info('Email báo cáo đã được gửi thành công.');
    }
}
