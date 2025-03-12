<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row

     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        Log::info('Thêm user mới: ' . json_encode($row));

        return new User([
            'full_name'  => $row['ho_va_ten'],
            'email'      => $row['email'],
            'username'   => $row['ten_dang_nhap'],
            'password'   => bcrypt('123456'),
            'role_id'    => 2,
            'status'     => 'active',
        ]);
    }



    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchInsert(array $rows)
    {
        DB::table('users')->insert($rows);  // Chèn nhiều bản ghi một lần
    }
}
