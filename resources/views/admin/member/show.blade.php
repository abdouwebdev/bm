@extends('_layouts.main')
@section('title', 'Data Member')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.member.index') }}">@lang('Members')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Show') @lang('Members')</li>
@endpush
 <style>
 .pres{
    margin: 10px;
 }
 </style>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="profile_img">
                        <div id="crop-avatar">
                          <img class="img-responsive avatar-view" src="{{ asset('assets/images/members')}}/{{$member->photo}}" alt="Avatar" title="avatar" width="200" height="200">
                        </div>
                        <h4 class="mt-2">{{$member->firstName}} {{$member->lastName}}</h4>
                        <ul class="list-unstyled user_data">
                            <li class="mb-2"><i data-feather="map-pin"></i> {{$member->presentAddress}}</li>
                            <li class="mb-2"><i data-feather="phone"></i> {{$member->mobileNo}}</li>
                            <li class="mb-2"><i data-feather="users"></i> {{$member->department->name}}</li>
                            <li class="mb-2"><i data-feather="calendar"></i> {{$member->session}}</li>
                        </ul>
                        <a class="btn btn-success" href="{{ route('admin.member.edit',$member->id)}}"><i class="fa fa-edit m-right-xs"></i> @lang('Edit')</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="pres"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="btn btn-primary">@lang('Daara Information')</a>
                            </li>
                            <li role="presentation" class="pres"><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false" class="btn btn-secondary">@lang('Personnal Information')</a>
                            </li>
                            <li role="presentation" class="pres"><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false" class="btn btn-success">@lang('Family Information')</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                     <i data-feather="users"></i> <strong>@lang('Department'): </strong> {{$member->department->name}}
                                    </li>
                                    <li class="mb-2">
                                      <i data-feather="users"></i> <strong>@lang('Sector'): </strong> {{$member->sector->name}}
                                     </li>
                                    <li class="mb-2">
                                      <i data-feather="clock"></i> <strong>@lang('Session'): </strong>  {{$member->session}}
                                    </li>
                                    <li class="mb-2">
                                       <i data-feather="info"></i> <strong>@lang('N-IDENTITY'): </strong>  {{$member->idNo}}
                                    </li>
                                </ul>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                      <i data-feather="user"></i> <strong>@lang('Name'): </strong>{{$member->firstName}} {{$member->lastName}}
                                    </li>
                                    <li class="mb-2">
                                      @if($member->gender=="Male")
                                      <i class="fa fa-male"></i> <strong>@lang('Gender'): </strong>  {{$member->gender}}
                                    @else
                                      <i class="fa fa-female"></i> <strong>@lang('Gender'): </strong>  {{$member->gender}}
                                    @endif
                                    </li>
                                    <li class="mb-2">
                                      <i data-feather="activity"></i> <strong>@lang('Blood Type'): </strong>  {{$member->bloodgroup}}
                                    </li>
                                    <li class="mb-2">
                                      <i data-feather="globe"></i> <strong>@lang('Nationality'): </strong>  {{$member->nationality}}
                                    </li>
                                    <li class="mb-2">
                                      <i data-feather="calendar"></i> <strong>@lang('Birthday'): </strong>  {{$member->dob->format('F j, Y')}}
                                    </li>
                                    <li class="mb-2">
                                      <i data-feather="phone"></i> <strong>@lang('Phone'): </strong>  {{$member->mobileNo}}
                                    </li>
                                </ul>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                      <i class="fa fa-male"></i> <strong>@lang('Father Name'): </strong> {{$member->fatherName}}
                                    </li>
                                    <li class="mb-2">
                                      <i data-feather="phone"></i> <strong>@lang('Father Phone'): </strong>  {{$member->fatherMobileNo}}
                                    </li>
                                    <li class="mb-2">
                                      <i class="fa fa-female"></i> <strong>@lang('Mother Name'): </strong>  {{$member->motherName}}
                                    </li>
                                    <li class="mb-2">
                                      <i data-feather="phone"></i> <strong>@lang('Mother Phone'): </strong>  {{$member->motherMobileNo}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection