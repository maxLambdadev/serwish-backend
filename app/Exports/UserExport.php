<?php

namespace App\Exports;
use App\Models\Services;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;



class UserExport implements FromView
{
    public function __construct(private array $exports = []){

    }

    public function view(): View
    {
        $users = User::with([])->orderBy('id','DESC');
        if (count($this->exports) > 0 && $this->exports[0] != null){
            $users = $users->whereIn('id', $this->exports);
        }
        $users = $users->get();
        return view('manager.pages.users.export',[
            'list'=>$users
        ]);
    }
}
