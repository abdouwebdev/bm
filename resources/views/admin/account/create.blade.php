<div class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Create New Account')</h4>
    </div>
    <div class="card-body">
        <form class="form form-horizontal" method="post" action="{{ route('admin.account.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="code">@lang('Code')</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="code" value="BMA-{{ $code }}" name="code"  class="form-control" readonly/>
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="date">{{ __('Date') }}</label>
                        </div>
                        <div class="col-sm-9">
                            <input id="date" type="date" value="{{ date('Y-m-d') }}"
                                    class="form-control @error('date') is-invalid @enderror" name="date">
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="department_id">@lang('Department')</label>
                        </div>
                        <div class="col-sm-9">
                            {!!Form::select('department_id', $departments, null, ['placeholder' => 'Select Department','class'=>'select2 department form-control','required'=>'required','id'=>'department_id'])!!}
                            @error('department_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="sector_id">@lang('Sector')</label>
                        </div>
                        <div class="col-sm-9">
                            {!!Form::select('sector_id', $sectors, null, ['placeholder' => 'Select Sector','class'=>'select1 sector form-control','required'=>'required','id'=>'sector_id'])!!}
                            @error('sector_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="session">@lang('Session')</label>
                        </div>
                        <div class="col-sm-9">
                            {!!Form::select('', $sessions, null, ['placeholder' => 'Session','class'=>'select3 session form-control ','required'=>'required' ,'id'=>'session'])!!}
                            <span id="session" class="text-danger" >{{ $errors->first('session') }}</span>
                            @error('session')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="member_id">@lang('Members')</label>
                        </div>
                        <div class="col-sm-9">
                            {{ Form::select('member_id',[], null, ['placeholder' => 'Member','class'=>'select form-control member','required'=>'required','id'=>'member_id']) }}
                            @error('member_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="group_id" name="group_id" value="{{ $group->id}}" class="form-control" readonly/>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="amount">@lang('Total Amount')</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="amount" name="amount"placeholder="Ex: 1000000" class="form-control"/>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="beginning_balance">@lang('Beginning Balance')</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="beginning_balance" name="beginning_balance"  class="form-control" placeholder="Ex: 50000" />
                            @error('beginning_balance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary" style="width: 200px">@lang('Save')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>