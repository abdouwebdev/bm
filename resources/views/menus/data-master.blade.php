@section('title', 'Data Master')

@push('breadcrumb')
    <li class="breadcrumb-item active">@lang('Data Master')</li>
@endpush

<section id="card-content-types">
    <div class="row">
        <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.contact.index') }}">
                <div class="card border-0 text-white" style="background-color: #542E71;">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Contact')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-users fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Contact Data')</h1>
                            <i class="fa fa-users"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Create and edit customer, supplier and employee data').
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.product.index') }}">
                <div class="card border-0 text-white" style="background-color: #008891;">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Product')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-briefcase fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Product')</h1>
                            <i class="fa fa-briefcase"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Manage data Product')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.member.index') }}">
                <div class="card border-0 text-white" style="background-color:#FF00FF;">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Members')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-users fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Members')</h1>
                            <i class="fa fa-user"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Manage Members Data')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.account.index') }}">
                <div class="card border-0 text-white" style="background-color:#4f8fe4;">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Accounts')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-chart-line fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Accounts')</h1>
                            <i class="fa fa-chart-line"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Manage Member Account')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.add-user-manager') }}">
                <div class="card border-0 text-white" style="background-color: #c5135d;">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('User & Manager')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-user fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('User & Manager')</h1>
                            <i class="fa fa-user"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Create User And Manager')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.department.index') }}">
                <div class="card border-0 text-white" style="background-color: #ae1dbb;">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Department')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-building fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Department')</h1>
                            <i class="fa fa-building"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Create New Department')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.sector.index') }}">
                <div class="card border-0 text-white" style="background-color: #e23d8f;">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Sector')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-city fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Sector')</h1>
                            <i class="fa fa-city"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Create New Sector')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
    </div>
</section>
