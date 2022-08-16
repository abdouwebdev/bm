@extends('_layouts.main')
@section('title', 'Member Data')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.member.index') }}">@lang('Members')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Edit') @lang('Members')</li>
@endpush
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal card form-label-left" method="post" action="{{ route('admin.member.update', $member->id)}}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <h4 class="text-white bg-primary text-center mb-4">@lang('MEMBER INFORMATION')</h4>
                <div class="row card-body">
                    <div class="col-md-4">
                        <label for="department_id">@lang('Department'): <span class="text-danger">*</span></label>
                        {{ Form::select('department_id',$departments,$member->department_id,['class'=>'select2 form-control has-feedback-left','tabindex'=>'-1','id'=>'department_id']) }}
                        @error('department_id')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="session">@lang('Session'): <span class="text-danger">*</span></label>
                        <input type="text" id="session" class="form-control has-feedback-left" name="session"  data-inputmask="'mask': '9999-9999'" value="{{$member->session}}"required />									
                        <i class="fa fa-clock-o form-control-feedback left" aria-hidden="true"></i>
                        @error('session')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="idNo">@lang('N-IDENTITY'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="info"></i></span>
                            </div>
                            <input type="text" id="idNo" disabled="disabled" class="form-control has-feedback-left" name="idNo" value="{{$member->idNo}}" />
                            @error('idNo')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="firstName">@lang('First Name'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="firstName" class="form-control has-feedback-left" name="firstName" value="{{$member->firstName}}" required />
                            @error('firstName')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="lastName">@lang('Last Name'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="lastName" class="form-control has-feedback-left" name="lastName" value="{{$member->lastName}}" required />
                            @error('lastName')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="gender">@lang('Gender'): <span class="text-danger">*</span></label>
                        <p>
                            @lang('Male'):
                            @if($member->gender=="Male")
                            <input type="radio" class="flat" name="gender" id="genderM" value="Male" checked=""  /> @lang('Female'):
                            <input type="radio" class="flat" name="gender" id="genderF" value="Female" />
                            @else
                            <input type="radio" class="flat" name="gender" id="genderM" value="Male"   /> @lang('Female'):
                            <input type="radio" class="flat" name="gender" id="genderF" value="Female" checked="" />
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="bloodgroup">@lang('Blood Type'):</label>
                        <?php  $data =[
                            ''=>'',
                            'A+'=>'A+',
                            'A-'=>'A-',
                            'B+'=>'B+',
                            'B-'=>'B-',
                            'AB+'=>'AB+',
                            'AB-'=>'AB-',
                            'O+'=>'O+',
                            'O-'=>'O-'
                        ];?>
                        {{ Form::select('bloodgroup',$data,$member->bloodgroup,['class'=>'select2 form-control has-feedback-left','id'=>'bloodgroup','tabindex'=>'-1'])}}
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="nationality">@lang('Nationality'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="globe"></i></span>
                            </div>
                            <input type="text" id="nationality" class="form-control has-feedback-left" value="{{$member->nationality}}" name="nationality" />
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="dob">@lang('Birthday'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="globe"></i></span>
                            </div>
                            <input type="text" name="dob" id="dob" class="form-control has-feedback-left" value="{{$member->dob->format('d-m-Y')}}" data-inputmask="'mask': '99/99/9999'" required>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="mobileNo">@lang('Phone'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="phone"></i></span>
                            </div>
                            <input type="text" id="mobileNo" class="form-control has-feedback-left" value="{{$member->mobileNo}}" name="mobileNo" />
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <label for="photo">@lang('Photo'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon10" style="color:green"><i data-feather="file"></i></span>
                            </div>
                            <input type="file" id="photo"  class="form-control has-feedback-left" name="photo">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="sector_id">@lang('Sector'): <span class="text-danger">*</span></label>
                        {{ Form::select('sector_id',$sectors,$member->sector_id,['class'=>'select2 form-control has-feedback-left','tabindex'=>'-1','id'=>'sector_id']) }}
                        @error('sector_id')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
                <h4 class="text-center text-white bg-primary">@lang('GUARDIAN INFORMATION (CHILDREN)')</h4>
                <div class="row card-body">
                    <div class="col-md-3">
                        <label for="fatherName">@lang('Father Name')</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="fatherName" class="form-control has-feedback-left" value="{{$member->fatherName}}" name="fatherName"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fatherMobileNo">@lang('Father Phone'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text " id="basic-addon1" style="color:green"><i data-feather="phone"></i></span>
                            </div>
                            <input type="text" id="fatherMobileNo" value="{{$member->fatherMobileNo}}" class="form-control has-feedback-left" data-inputmask="'mask': '880 9999999999'" name="fatherMobileNo" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="motherName">@lang('Mother Name'): </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="motherName" class="form-control has-feedback-left" value="{{$member->motherName}}" name="motherName" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="motherMobileNo">@lang('Mother Phone'): </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" style="color:green"><i data-feather="phone"></i></span>
                            </div>
                            <input type="text" id="motherMobileNo" value="{{$member->motherMobileNo}}" class="form-control has-feedback-left" data-inputmask="'mask': '880 9999999999'" name="motherMobileNo" />
                        </div>
                    </div>
                    <div class="col-md-12 mt-2 d-flex justify-content-center">
                        <button type="submit" class="btn btn-md btn-primary">@lang('UPDATE')</button>
                    </div>
                </div>
            </form>
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
                placeholder: "Select Department",
            });
		})
</script>
<script src="{{ asset('js/helpers.js') }}"></script>
@endpush