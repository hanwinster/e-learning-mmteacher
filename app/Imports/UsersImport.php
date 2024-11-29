<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

//class UsersImport implements ToModel
class UsersImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading, SkipsOnError
{
    use Importable;

    public function collection(Collection $rows)
    {
        /*
        foreach ($rows as $key => $row) {
            $user = new User();
            $user->name = $row['name'];
            $user->username = $row['username'];
            $user->email = $row['email'];
            $user->password = Hash::make($row['password']);
            $user->mobile_no = $row['mobile_no'];
            $user->notification_channel = $row['notification_channel'];
            $user->user_type = $row['user_type'];
            $user->ec_college = $row['ec_college'];
            $user->suitable_for_ec_year = $row['suitable_for_ec_year'];
            $user->approved = $row['approved'];
            $user->verified = $row['verified'];
            $user->blocked = $row['blocked'];
            $user->type = $row['type'];
            $user->verification_code = $row['verification_code'];
            $user->email_verified_at = $row['email_verified_at'];
            $user->sms_verified_at = $row['sms_verified_at'];

            $user->save();

            if ($row['subjects']) {
                $row['subjects'] = explode(',', $row['subjects']);

                if (count($row['subjects'])) {
                    $user->subjects()->sync($row['subjects']);
                }
            }
        }
        */
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
}
