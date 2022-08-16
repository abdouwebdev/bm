@extends('_layouts.main')
@section('title', 'Create Member')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.member.index') }}">@lang('Members')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('ADD') @lang('Members')</li>
@endpush
@section('content')
<div class="container">
    <div class="row card">
        <div class="col-md-12">
            <form class="form form-horizontal card-body" action="{{route('admin.member.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <h4 class="text-white bg-primary text-center mb-4">@lang('MEMBER INFORMATION')</h4>
                <div class="row mb-2">
                    <input type="hidden" id="age" name="age" readonly/>
                    <input type="hidden" id="group_id" name="group_id" value="{{$group->id}}" readonly/>
                    <div class="col-md-4 col-12">
                        <label for="department_id">@lang('Department'): <span class="text-danger">*</span></label>
						{{ Form::select('department_id', $departments, null, ['required', 'placeholder' => 'Select Department', 'class'=>'select2 form-control has-feedback-left','tabindex'=>'-1','id'=>'department_id']) }}
                        @error('department_id')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-12">
                        <label for="session">@lang('Session'): <span class="text-danger">*</span></label>
                        <input type="text" id="session" class="form-control has-feedback-left" name="session" data-inputmask="'mask': '9999-9999'" required />
                        <i class="fa fa-clock-o form-control-feedback left" aria-hidden="true"></i>
                        @error('session')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-12">
                        <label for="idNo">@lang('N-IDENTITY'): <span class="text-danger"></span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon2" style="color:green"><i data-feather="info"></i></span>
                            </div>
                            <input type="text" id="idNo" class="form-control has-feedback-left" name="idNo"/>
                            @error('idNo')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <label for="firstName">@lang('First Name'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon4" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="firstName" class="form-control has-feedback-left" name="firstName" required />
                            @error('firstName')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <label for="lastName">@lang('Last Name'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon4" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="lastName" class="form-control has-feedback-left" name="lastName" required />
                            @error('lastName')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <label for="gender">@lang('Gender'): <span class="text-danger">*</span></label>
                        <p>
                            @lang('Male'):
                            <input type="radio" class="flat" name="gender" id="genderM" value="Male" checked="" required /> 
                            @lang('Female'):
                            <input type="radio" class="flat" name="gender" id="genderF" value="Female" />
                        </p>
                    </div>
                    <div class="col-md-4 col-12 mt-2">
                        <label for="bloodgroup">@lang('Blood Type'):</label>
                        <select name="bloodgroup" id="bloodgroup" class="has-feedback-left select form-control" tabindex="-1">
                            <option></option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                        @error('bloodgroup')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-6 mt-2">
                        <label for="nationality">@lang('Nationality'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon7" style="color:green"><i data-feather="globe"></i></span>
                            </div>
                            <input type="text" id="nationality" value="sénégalais" class="form-control has-feedback-left" name="nationality" required />
                            @error('nationality')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mt-2">
                        <label for="dob">@lang('Birthday'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon7" style="color:green"><i data-feather="calendar"></i></span>
                            </div>
                            <input type="text" name="dob" id="dob" class="form-control has-feedback-left" data-inputmask="'mask': '99-99-9999'" required>
                            @error('dob')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mt-2">
                        <label for="mobileNo">@lang('Phone')</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon8" style="color:green"><i data-feather="phone"></i></span>
                            </div>
                            <input type="text" id="mobileNo" class="form-control has-feedback-left" name="mobileNo"/>                        </div>
                        </div>
                        @error('mobileNo')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    <div class="col-md-4 col-12 mt-2">
                        <label for="photo">@lang('Photo'): <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon10" style="color:green"><i data-feather="file"></i></span>
                            </div>
                            <input type="file" id="photo"  class="form-control has-feedback-left" name="photo">
                            @error('photo')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mt-2">
                        <label for="sector_id">@lang('Sector'): <span class="text-danger">*</span></label>
						{{ Form::select('sector_id', $sectors, null, ['required','placeholder' => 'Sector','class'=>'select3 form-control has-feedback-left','tabindex'=>'-1','id'=>'sector_id']) }}
                        @error('sector_id')
                            <div class="d-block invalid-feedback"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
                <h4 class="text-center text-white bg-primary mb-4">@lang('GUARDIAN INFORMATION (CHILDREN)')</h4>
                <div class="row mb-2">
                    <div class="col-md-3 col-12">
                        <label for="fatherName">@lang('Father Name'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon8" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="fatherName" class="form-control has-feedback-left" name="fatherName"/>
                            @error('fatherName')
                                <div class="d-block invalid-feedback"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="fatherMobileNo">@lang('Father Phone'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11" style="color:green"><i data-feather="phone"></i></span>
                            </div>
                            <input type="text" id="fatherMobileNo" class="form-control has-feedback-left" name="fatherMobileNo"/>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="motherName">@lang('Mother Name'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon8" style="color:green"><i data-feather="user"></i></span>
                            </div>
                            <input type="text" id="motherName" class="form-control has-feedback-left" name="motherName"/>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="motherMobileNo">@lang('Mother Phone'):</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon12" style="color:green"><i data-feather="phone"></i></span>
                            </div>
                            <input type="text" id="motherMobileNo" class="form-control has-feedback-left"  name="motherMobileNo"/>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2 d-flex justify-content-center">
                        <button type="submit" class="btn btn-md btn-primary">@lang('Save')</button>
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
<script src="{{ asset('assets/js/jquery.inputmask.bundle.min.js')}}"></script>
<script>
    $(function () {
			$('.select2').select2({
                placeholder: "@lang('Select Department')",
            });
            $('.select').select2({
                placeholder: "@lang('Blood Type')",
            });
            $('.select3').select2({
                placeholder: "@lang('Sector')",
            });
            $(":input").inputmask();
		})
</script>
<script src="{{ asset('js/helpers.js') }}"></script>
@endpush