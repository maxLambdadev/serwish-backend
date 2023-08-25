
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ isset($panelTitle) ? $panelTitle : 'სამართავი პანელი' }}</h3>
        </div>

        <div class="card-body row">

            <div class="col-4">
                @if(isset($createRoute))
                    <a class="btn btn-app" href="{{route($createRoute)}}">
                        <i class="fas fa-play"></i> დამატება
                    </a>
                @endif

                @if(isset($bulkDelete))
                    @if(!isset($disableEdit))
                        <a class="btn btn-app" href="#" >
                            <i class="fas {!! isset($customEditIcon) ? $customEditIcon : 'fa-edit' !!}"></i> {!! isset($customEditName) ? $customEditName : 'რედაქტირება' !!}
                        </a>
                    @endif
{{--                    <a class="btn btn-app"--}}
{{--                       href="#"--}}
{{--                       id="{{isset($bulkDelete) ? 'bulkDelete': ''}}"--}}
{{--                       data-route="{{isset($bulkDeleteRoute) ? route($bulkDeleteRoute): ''}}">--}}
{{--                        <i class="fas fa-trash-alt"></i> წაშლა--}}
{{--                    </a>--}}
                @endif


                @isset($customButtons)
                    @foreach($customButtons as $button)
                        <a class="btn btn-app {{isset($button['triggerClass']) ? $button['triggerClass'] : '' }}"
                           href="{!! isset($button['href']) ? $button['href'] : '#' !!}"
                           id="customButton-{{$button['name']}}"
                           data-route="{{$button['route']}}">
                            <i class="fas {{$button['icon']}}"></i> {{$button['name']}}
                        </a>
                    @endforeach
                @endisset
            </div>

            <div class="row col-2">

                <div class="col-4 dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-envelope"></i>
                        <span class="badge badge-warning navbar-badge" style="margin-right: 10px;">{{\App\Models\ContactRequests::where('seen',false)->count()}}</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">კონტაქტის მოთხოვნები</span>
                        @foreach($contactReqs as $contactReq)
                            <a href="{{route('manager.contact-requests.list', ['highlight'=>$contactReq->id])}}" class="dropdown-item">
                                {{$contactReq->phone}} {{\Illuminate\Support\Str::limit($contactReq->title,10)}} {{\Illuminate\Support\Str::limit($contactReq->email,20)}}
                            </a>
                            <div class="dropdown-divider"></div>
                        @endforeach
                    </div>
                </div>

                <div class="col-4 dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-phone"></i>
                        <span class="badge badge-warning navbar-badge" style="margin-right: 10px;">{{\App\Models\CallRequests::where('is_called',false)->count()}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">ზარის მოთხოვნები</span>
                        @foreach($callReqs as $callReq)
                            <a href="{{route('manager.call-requests.list', ['highlight'=>$callReq->id])}}" class="dropdown-item">
                                {{$callReq->phone_number}}  {!! $callReq->category != null ? $callReq->category->title : '' !!}
                            </a>
                            <div class="dropdown-divider"></div>
                        @endforeach
                    </div>
                </div>

                <div class="col-4 dropdown">
                    <a class="nav-link"  href="{{ route('manager.services.service.index',['review_type'=>'started'])  }}">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge" style="margin-right: 10px;">{{ $newServicesCount }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
{{--                        <span class="dropdown-item dropdown-header">ახალი სერვისები</span>--}}
{{--                        @foreach($newServices as $newService)--}}
{{--                            <a href="{{ route('manager.services.service.show',['id'=>$newService->id])  }}" class="dropdown-item">--}}
{{--                                {{\Illuminate\Support\Str::limit($newService->title,12)}} {{\Illuminate\Support\Str::limit($newService->specialist->name,15)}}--}}
{{--                            </a>--}}
{{--                            <div class="dropdown-divider"></div>--}}
{{--                        @endforeach--}}

{{--                        <div class="dropdown-divider"></div>--}}
{{--                        <a href="{{ route('manager.services.service.index',['review_type'=>'started'])  }}" class="dropdown-item" style="color: blue; font-style: italic">--}}
{{--                            ყველას ნახვა--}}
{{--                        </a>--}}
                    </div>
                </div>
            </div>


            @if(request()->routeIs('manager.dashboard'))
           <div class="col-6">
               <form action="" method="get" class="row">

                   <div class="form-group col-4">
                       <div class="form-group">
                           <div class="select2-purple">
                               <select name="review_status" class="form-control review-change"  data-placeholder="აირჩიეთ განხილვის სტატუსი" data-dropdown-css-class="select2-purple" style="width: 100%;"
                                   {!! isset($entity) && $entity->reviewer_id  !== \Illuminate\Support\Facades\Auth::user()->id && $entity->reviewer_id !== null ? 'disabled' : '' !!}
                               >
                                   <option value="in_review" {!!request()->get('review_status') && request()->get('review_status') == 'in_review' ? 'selected' : '' !!} >განხილვის პროცესში</option>
                                   <option value="published" {!! request()->get('review_status') == null || request()->get('review_status') != 'in_review' ? 'selected' : '' !!} >შემოწმებული</option>
                               </select>
                           </div>
                       </div>
                   </div>

                   <input type="hidden" name="category" value="{{request()->get('category')}}">

                   <div class="col-7 form-group" >
                       <div class="input-group input-group-md div " >
                           <input type="text" name="title" class="form-control float-right" placeholder="ძებნა" @if(request()->has('title')) value="{{request()->get('title')}}" @endif>
                           <div class="input-group-append">
                               <button type="submit" class="btn btn-default filter-form">
                                   <i class="fas fa-search"></i>
                               </button>
                           </div>
                       </div>
                   </div>

                   <div class="col-1">
                       <div class="category-overlay-button d-flex justify-content-center align-items-center" style="border: 1px solid #27aae1; border-radius:12px; width: 40px; height: 40px">
                           <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                               <img src="/lines.svg" width="20" height="20" alt="">
                           </a>

                       </div>
                   </div>

                   <button style="visibility: hidden" type="submit" class="btn btn-default filter-form">
                       <i class="fas fa-search"></i>
                   </button>
               </form>
           </div>

            @endif

        </div>
    </div>
</div>
