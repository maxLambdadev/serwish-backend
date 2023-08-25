<?php

namespace App\Exports;
use App\Models\Services;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;



class ServiceExport implements FromView
{
    public function __construct(private array $exports = []){

    }

    public function view(): View
    {
        $services = Services::with(['categories'])->orderBy('id','DESC');
        if (count($this->exports) > 0 && $this->exports[0] != null){
            $services = $services->whereIn('id', $this->exports);
        }
        $services = $services->get();
        return view('manager.pages.service.export',[
            'list'=>$services
        ]);
    }
}
