@extends('_layouts.main')
@section('title', 'Edit Contact')
@push('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.data-store') }}">@lang('Data Master')</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.contact.index') }}">@lang('Contact Data')</a>
    </li>
    <li class="breadcrumb-item" aria-current="page">@lang('Edit')</li>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="alert-body">
                        @foreach ($errors->all() as $error)
                        <ul style="margin: 0 12px 0 -11px">
                            <li>{{ $error }}</li>
                        </ul>
                        @endforeach
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card ">
                <div class="card-header">
                    <h3><i data-feather="user"></i></h3>
                </div>
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{ route('admin.contact.update', $contact->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">{{ __('Name') }}<span class="text-red">*</span></label>
                                    <input id="name" type="text" value="{{ old('name') ?? $contact->name}}"
                                        class="form-control @error('name') is-invalid @enderror" name="name" >
                                    <div class="help-block with-errors"></div>
                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="email">{{ __('Email') }}: (@lang('Optional'))</label>
                                    <input id="email" type="email" value="{{ old('email') ?? $contact->email}}"
                                        class="form-control @error('email') is-invalid @enderror" name="email" >
                                    <div class="help-block with-errors"></div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="phone">@lang('Phone'): (@lang('Optional'))</label>
                                    <input id="phone" type="text" value="{{ old('phone') ?? $contact->phone}}"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        minlength="7" maxlength="13" onkeypress="onlyNumber(event)">
                                    <div class="help-block with-errors"></div>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="demo-inline-spacing">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customer" name="customer" {{ old('customer') ?? $contact->customer == 1 ? ' checked' : '' }} />
                                            <label class="custom-control-label" for="customer">@lang('Customer')</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="supplier" name="supplier" {{ old('supplier') ?? $contact->supplier == 1 ? ' checked' : '' }} />
                                            <label class="custom-control-label" for="supplier">@lang('Supplier')</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="employee" name="employee" {{ old('employee') ?? $contact->employee == 1 ? ' checked' : '' }} />
                                            <label class="custom-control-label" for="employee">@lang('Employee')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <p><strong>@lang('Address')</strong></p>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="address">@lang('Address')</label>
                                    <textarea name="address" id="address"
                                        class="form-control @error('address') is-invalid @enderror">{{ old('address') ?? $contact->address}}</textarea>
                                    <div class="help-block with-errors"></div>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="city">@lang('City'): (@lang('Optional'))</label>
                                    <input id="city" type="text" value="{{ old('city') ?? $contact->city}}"
                                        class="form-control @error('city') is-invalid @enderror" name="city">
                                    <div class="help-block with-errors"></div>
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="code_post">@lang('Zip Code'): (@lang('Optional'))</label>
                                    <input id="code_post" type="text" value="{{ old('code_post') ?? $contact->code_post}}"
                                        class="form-control @error('code_post') is-invalid @enderror" name="code_post">
                                    <div class="help-block with-errors"></div>
                                    @error('code_post')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="profession">@lang('Profession'): (@lang('Optional'))</label>
                                    <input id="profession" type="text" value="{{ old('profession') ?? $contact->profession}}"
                                        class="form-control @error('profession') is-invalid @enderror" name="profession">
                                    <div class="help-block with-errors"></div>
                                    @error('profession')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="code_contact">@lang('Contact Code')</label>
                                    <input id="code_contact" type="text" value="{{ old('code_contact') ?? $contact->code_contact}}"
                                        class="form-control @error('code_contact') is-invalid @enderror" name="code_contact" >
                                    <div class="help-block with-errors"></div>
                                    @error('code_contact')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="nik">@lang('N-IDENTITY')</label>
                                    <input id="nik" type="text" value="{{ old('nik') ?? $contact->nik}}"
                                        class="form-control @error('nik') is-invalid @enderror" name="nik"
                                        onkeypress="onlyNumber(event)" minlength="11" maxlength="16">
                                    <div class="help-block with-errors"></div>
                                    @error('nik')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="website">@lang('Website'): (@lang('Optional'))</label>
                                    <input id="website" type="text" value="{{ old('wesite') ?? $contact->website}}"
                                        class="form-control @error('website') is-invalid @enderror" name="website">
                                    <div class="help-block with-errors"></div>
                                    @error('website')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 mt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="active" name="active" {{ ($contact->active == 1 ? ' checked' : '') }} />
                                    <label class="custom-control-label" for="active">@lang('Active')</label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <a href="{{ route('admin.contact.index') }}" class="btn btn-danger">@lang('RETURN')</a>
                        <button type="submit" class="btn btn-primary">@lang('Edit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/helpers.js') }}"></script>
    <script>
        $(document).ready(function(){
            let csrf = '{{ csrf_token() }}'

            let inputName = document.getElementById('name')
            inputName.addEventListener('input', function(e){
                let name = this.value
                $.ajax({
                    url: '{{ route('admin.contact.code') }}',
                    type: 'post',
                    data: {
                        _token: csrf,
                        name: name
                    },
                    success: data => {
                        $("#code_contact").val(data.success)
                    },
                })
            })
        })
    </script>
@endpush
