<?php

namespace App\Exports;
use App\Models\Orders\OrderGroupPayment;
use App\Models\Orders\OrderGroups;
use App\Models\Services;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;



class OrdersExport implements FromView
{
    public function __construct(private array $exports = []){

    }

    public function view(): View
    {
        $users = OrderGroups::with('service','customer','specialist')->orderBy('id','DESC');

        if (count($this->exports) > 0 && $this->exports[0] != null){
            $users = $users->whereIn('id', $this->exports);
        }

        $users = $users->get();
        return view('manager.pages.orderPayments.export',[
            'list'=>$users
        ]);
    }
}
