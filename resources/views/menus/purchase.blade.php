@section('title', 'Purchase')

@push('breadcrumb')
    <li class="breadcrumb-item active">@lang('Purchase')</li>
@endpush

<section>
    <div class="row">

        <div class="col-md-4 col-xl-4">
            <a href="{{ route('admin.purchase.offer.index') }}">
                <div class="card bg-warning border-0 text-white shadow">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Offer')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-chart-line fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Offer')</h1>
                            <i class="fa fa-chart-line"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Make price quotes for suppliers')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-4">
            <a href="{{ route('admin.purchase.order.index') }}">
                <div class="card  bg-success border-0 text-white shadow">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Order')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-hand-holding-usd fa-lg fa-5x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Order')</h1>
                            <i class="fa fa-chart-line"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Create purchase orders for suppliers')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-4">
            <a href="{{ route('admin.purchase.invoice.index') }}">
                <div class="card bg-primary border-0 text-white shadow" >
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Invoice')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-book-open fa-lg fa-4x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Invoice')</h1>
                            <i class="fa fa-book-open"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Record sales invoices for customers')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- <div class="col-md-3 col-xl-3">
            <a href="{{ route('admin.purchase.receivable.index') }}">
                <div class="card border-0 text-white shadow" style="background-color : #F6A9A9">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Accounts Payable')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-tasks fa-lg fa-4x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Accounts Payable')</h1>
                            <i class="fa fa-tasks"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                                @lang('Display a detailed list of accounts receivable for each supplier')
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
            <a href="{{ route('admin.purchase.payment.index') }}">
                <div class="card border-0 text-white shadow" style="background-color : #B85C38">
                    <div class="card-body">
                        <div class="title">
                            <div class="row">
                                <div class="col">
                                    <h1 class="text-light">@lang('Payment of Accounts Payable')</h1>
                                </div>
                                <div class="col">
                                    <i class="fa fa-credit-card fa-lg fa-4x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="info">
                            <p class="card-text">
                            <h1 class="card-text display-5 text-white font-weight-bold">@lang('Payment of Accounts Payable')</h1>
                            <i class="fa fa-credit-card"></i>
                            </p>
                            <hr class="mr-1">
                            <p class="card-text">
                               @lang('Record receipt of Accounts receivable Payments')
                            </p>
                            <p class="card-text">
                                <small class="text-light"></small>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div> --}}
        
    </div>
</section>