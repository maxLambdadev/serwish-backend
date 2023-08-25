<?php

namespace App\Http\Controllers\Manager\Services;

use App\Exports\ServiceExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SavePostRequest;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\City;
use App\Models\ServiceReview;
use App\Models\Services;
use App\Models\SpecialistCommentReview;
use App\Models\Tags;
use App\Models\TagsTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class CommentReviewController extends BaseController
{

    public function __construct()
    {
        $this->viewData = [
            'form'  => 'manager.pages.service.review.show',
            'list'  => 'manager.pages.service.review.list'
        ];

        parent::__construct();
    }

    public function list(Request $request, ?array $ordering = ['created_at', 'DESC'])
    {
        if ($request->review_status && $request->review_status !== 'all'){
            $this->model = $this->model->where('review_status','=', $request->review_status);
        }
        return parent::list($request, $ordering); // TODO: Change the autogenerated stub
    }

    public function show(Request $request)
    {

        if ($request->id) {
            $this->response['entity'] = $this->model->findOrFail($request->id);
        }

        return $this->responseView($this->viewData['form']);
    }



    function makeModel(): string
    {
        return SpecialistCommentReview::class;
    }
}
