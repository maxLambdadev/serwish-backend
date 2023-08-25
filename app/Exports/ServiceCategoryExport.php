<?php

namespace App\Exports;
use App\Models\Blog\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;



class ServiceCategoryExport implements FromView
{
    public function __construct(private array $exports = []){

    }

    public function view(): View
    {
        $services = Category::with([])->orderBy('id','DESC');
        if (count($this->exports) > 0 && $this->exports[0] != null){
            $services = $services->whereIn('id', $this->exports);
        }
        $services = $services->get();
        return view('manager.pages.service.category.export',[
            'list'=>$services
        ]);
    }
}
