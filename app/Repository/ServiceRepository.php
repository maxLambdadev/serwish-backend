<?php

namespace App\Repository;

use App\Models\Services;

class ServiceRepository extends BaseRepository
{

    function getServices(){

    }

    private function defaultRelations(){
        $this->model = $this->model->with(['images','workingHours','cities','categories']);
    }

    function make(): string
    {
        return Services::class;
    }
}
