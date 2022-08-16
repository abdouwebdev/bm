<div class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Create New Jackpot')</h4>
    </div>
    <div class="card-body">
        <form class="form form-horizontal" method="post" action="{{ route('admin.jackpot.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="code">@lang('Code')</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="code" value="BMJ-{{ $code }}" name="code"  class="form-control" readonly/>
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
                            <label for="name">@lang('Jackpot Name')</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="name" name="name" placeholder="Ex: Ziar..." class="form-control"/>
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
                        <div class="col-sm-3 col-form-label">
                            <label for="amount">@lang('Jackpot Amount')</label>
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
                    <input type="hidden" id="member_id" name="member_id" class="form-control" readonly/>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="group_id" name="group_id" value="{{ $group->id}}" class="form-control" readonly/>
                </div>
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary" style="width: 200px">@lang('Save')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>