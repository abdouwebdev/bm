@extends('_layouts.main')
@section('title', 'Members')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Members')</li>
@endpush

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-payment">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.member.create') }}" class="btn btn-sm btn-primary shadow"><i data-feather="plus"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="container">
                <form class="form-inline" method="POST" action="{{ route('admin.member.department') }}">
                    @csrf
                    <div class="col-md-6">
                        {{ Form::select('department_id', $departments, $selectDep, ['placeholder' => 'Select Departement', 'class' => 'select2 form-control has-feedback-left','tabindex'=>'-1','id'=>'department_id']) }}
                        @error('department_id')
                        <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-check"></i> @lang('Search') </button>
                </form>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>@lang('Photo')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Department')</th>
                        <th>@lang('Sector')</th>
                        <th>@lang('Action')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($members as $member)
                      <tr>
                        @if(!empty($member->idNo))
                        <td>{{$member->idNo}}</td>
                        @else
                        <td>#</td>
                        @endif
                        <td>
                            <img src="{{ asset('assets/images/members')}}/{{$member->photo}}" alt="{{$member->photo}}" class="rounded" width="50px" height="50px">
                        </td>
                        <td>{{$member->firstName}} {{$member->lastName}}</td>
                        <td>{{$member->department->name}}</td>
                        <td>{{$member->sector->name}}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                    data-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item"
                                        href="{{ route('admin.member.show', $member->id) }}">
                                        <i data-feather="eye"></i>
                                        <span class="ml-1">@lang('Show')</span>
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.member.edit', $member->id) }}">
                                        <i data-feather="edit"></i>
                                        <span class="ml-1">@lang('Edit')</span>
                                    </a>
                                    <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                        onclick="deleteConfirm('form-delete', '{{ $member->id }}')">
                                        <i data-feather="trash"></i>
                                        <span class="ml-1">@lang('Delete')</span>
                                    </a>
                                    <form id="form-delete{{ $member->id }}" action="{{ route('admin.member.destroy', $member->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </div>
                            </div>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('select2')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script>
    $(function () {
			$('.select2').select2({
                placeholder: "@lang('Select Department')",
            });
	})
</script>
<script src="{{ asset('js/helpers.js') }}"></script>
@endpush
