<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
//use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
//use Maatwebsite\Excel\Concerns\Exportable;

class BulkExport implements FromCollection, WithHeadings
{

    //use Exportable;

    public function __construct(string $fromDate, string $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */

    public function headings(): array
    {
        return [
            'ID',
            'Username',
            'Email',
            'Created At'
        ];
    }

    // public function query()
    // {
    //     return User::whereBetween('created_at', [ $this->fromDate.' 00:00:00',
    //                                 $this->toDate.' 23:59:59']); //->get(['id', 'username', 'email','created_at']);
    //     // return Bulk::query()->whereRaw('id > 5');*/
    // }
    /**
    * @return \Illuminate\Support\Array
    */
    public function map($bulk): array
    {   
        return [
            $bulk->id,
            $bulk->username,
            $bulk->email,
            Carbon::parse($bulk->created_at)->format('d-m-Y')
            //Date::dateTimeToExcel($bulk->updated_at),
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::whereBetween('created_at',
                    [ $this->fromDate.' 00:00:00', $this->toDate.' 23:59:59'])
                ->orderBy('created_at', 'ASC')
                ->get(['id', 'username', 'email','created_at']);
    }
}
