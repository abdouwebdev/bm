<div class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Create New Account')</h4>
    </div>
    <div class="card-body">
        <form class="form form-horizontal" method="post" action="{{ route('admin.communication.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-4 col-form-label">
                            <label for="title">@lang('Communication Title')</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="title"  name="title" placeholder="Ex: @lang('General Assembly')"  class="form-control" />
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-4 col-form-label">
                            <label for="body">@lang('Communication Body')</label>
                        </div>
                        <div class="col-sm-8">
                            <textarea name="body" id="body" class="form-control" rows="7"></textarea>
                            @error('body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="group_id" value="{{$group->id}}" name="group_id" readonly/>
                </div>
                <div class="col-sm-6 offset-sm-4">
                    <button type="submit" class="btn btn-primary" style="width: 200px">@lang('Save')
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>