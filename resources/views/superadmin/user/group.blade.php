
<div class="card">
    <div class="card-body">
        <form class="form form-horizontal" method="post" action="{{ route('superadmin.create') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-2 col-form-label">
                            <label for="name">@lang('Name')</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" id="name" name="name" placeholder="@lang('Name')" class="form-control"/>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-2 col-form-label">
                            <label for="email">@lang('Email')</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" id="email" name="email" placeholder="@lang('Email')" class="form-control"/>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-2 col-form-label">
                            <label for="password">@lang('Password')</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="password" id="password" name="password" placeholder="@lang('Password')" class="form-control"/>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-2 col-form-label">
                            <label for="address">@lang('Address')</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" id="address" name="address" placeholder="@lang('Address')" class="form-control"/>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-2 col-form-label">
                            <label for="role">@lang('Role')</label>
                        </div>
                        <div class="col-sm-10">
                            <select name="role" class="form-control">
                                <option value="admin">@lang('Admin')</option>
                                <option value="user">@lang('User')</option>
                                <option value="superadmin">@lang('Super Admin')</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 offset-sm-3">
                    <button type="submit" class="btn btn-primary" style="width: 200px">
                        @lang('Save')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>