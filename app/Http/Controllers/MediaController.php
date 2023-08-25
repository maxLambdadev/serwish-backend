<?php

namespace App\Http\Controllers;

use App\Helpers\WebpConverter;
use App\Http\Requests\DetachResourceRequest;
use App\Http\Requests\MakeDefaultImageRequest;
use App\Http\Requests\MediaDeleteRequest;
use App\Http\Requests\MediaStoreRequest;
use App\Http\Requests\MediaUpdateRequest;
use App\Models\Resource;
use App\Models\ResourceToAny;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class MediaController extends BaseController
{
    public function __construct()
    {
        $this->viewData = [
            'list'  => 'manager.pages.media.list'
        ];

        parent::__construct();
    }

    public function explore(Request $request)
    {
        $resources = Resource::orderBy('id','DESC')->paginate(20);
        return response()->json($resources);

    }

    public function store(MediaStoreRequest $request)
    {
        if ($request->file('file')){
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $filename = explode('.',$filename);
            $extension = $filename[count($filename) - 1];

            unset($filename[count($filename) - 1]);

            $file = $request->file('file')->storeAs('uploads', implode($filename).'.'.$extension);

            $created = WebpConverter::convertFromAny('app/public/'.$file);

            if ($created){
                $fileNameArray = explode('.', $file);
                $fileNameArray[count($fileNameArray) - 1] = 'webp';

                $file = implode('.', $fileNameArray);
            }

//            $optimizerChain = OptimizerChainFactory::create();
//            $optimizerChain->optimize('storage/'.$file);

            $media = new Resource;
            $media->path = $file;
            $media->name = $file;
            $media->save();

            \App\Helpers\ImageResizer::cropper($file);

//
//            $optimizerChain = OptimizerChainFactory::create();
//            $optimizerChain->optimize('storage/'.$file);
//            dd($explode366x228,'storage/'.implode($explode366x228).'.'.$extension);

            return response()->json([
                'media' => $media
            ]);
        }
        return response("file doesn't exists", 400);
    }

    public function update(MediaUpdateRequest $request)
    {
        $media = Resource::findOrFail($request->id);
        $media->name = $request->name;
        $media->save();
        return response()->json([
            'success'=>true,
            'message'=> __('media.updated')
        ]);
    }

    public function setDefaultResource(MakeDefaultImageRequest $request){

       //check if have default and set false todo#for old datas get and make 1 default
        $check = ResourceToAny::where('resource_id', '!=',$request->resource_id)
            ->where('other_id',$request->other_id)
            ->where('other_entity', $request->other_entity)
            ->where('is_active','=',true)
        ->get();

        foreach ($check as $c){
            $c->is_active = 0;
            $c->save();
        }



        DB::table('resource_to_any')
            ->select('*')
            ->where('resource_id', $request->resource_id)
            ->where('other_id',$request->other_id)
            ->where('other_entity', $request->other_entity)
            ->update(['is_active'=>$request->is_active]);

        return response()->json([
            'success'=>true,
            'message'=>__('mediaDefaultSet')
        ]);
    }

    public function detach(DetachResourceRequest $request) {
        DB::table('resource_to_any')
            ->select('*')
            ->where('resource_id', $request->resource_id)
            ->where('other_id',$request->other_id)
            ->where('other_entity', $request->other_entity)
            ->delete();


        return response()->json([
           'success'=>true,
           'message'=>__('mediaRemovedFromEntity')
        ]);
    }

    public function trash(MediaDeleteRequest $request){
        Resource::findOrFail($request->id)->delete();
        return response()->json([
            'success'=>true,
            'message'=>__('media.trashed')
        ]);
    }

    public function restore(MediaDeleteRequest $request){
        Resource::findOrFail($request->id)->restore();
        return response()->json([
            'success'=>true,
            'message'=>__('media.trashed')
        ]);
    }

    public function delete(MediaDeleteRequest $request){
        Resource::findOrFail($request->id)->forceDelete();
        return response()->json([
            'success'=>true,
            'message'=>__('media.deleted')
        ]);
    }


    function makeModel(): string
    {
        return Resource::class;
    }
}
