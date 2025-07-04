<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class SmtpController extends Controller
{
    public function email()
    {
        return view('backend.smtp.email');
    }
    public function emailSubmit(Request $request)
    {

        // dd($request->all());


        Config::set('mail.mailers.smtp.username', $request->email);
        Config::set('mail.mailers.smtp.password', $request->password);
        Config::set('mail.from.name', $request->mail_name);

        // Cập nhật vào file .env
        $this->updateEnv([
            'MAIL_USERNAME' => $request->email,
            'MAIL_PASSWORD' => $request->password,
            'MAIL_FROM_NAME' => $request->mail_from_name,
        ]);

        // Clear cache để áp dụng cấu hình mới
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        toastr()->success('Cập nhật thành công.');
        return redirect()->back();
    }

    private function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^$key=.*/m";
            $replacement = "$key=\"$value\"";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n$replacement";
            }
        }

        file_put_contents($envPath, $envContent);
    }

    public function template()
    {
        $email = EmailAdmin::first();
        return view('backend.smtp.template', compact('email'));
    }


    public function emailAdmin(Request $request)
    {

        $data = $request->only(['email',]);

        $email = EmailAdmin::first();

        if ($email) {
            $email->update($data);
        } else {
            EmailAdmin::create($data);
        }

        toastr()->success('Cập nhật thành công.');
        return redirect()->back();
    }
}
