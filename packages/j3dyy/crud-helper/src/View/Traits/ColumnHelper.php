<?php
namespace J3dyy\CrudHelper\View\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use J3dyy\CrudHelper\Components\Table\Column;

trait ColumnHelper
{

    public static function parseAndCreateColumns(Model $model): array{
        $columns = [];

        $dbColumns = self::getDbColumns($model->getTable());
        foreach ($dbColumns as $dbColumn){
            //here we need detect other type
            $columns[] = Column::raw($dbColumn,$dbColumn);
        }
        return $columns;
    }

    private static function getDbColumns(string $table){
        return Schema::getColumnListing($table);
    }

}
