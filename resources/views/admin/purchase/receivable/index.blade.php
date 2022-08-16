@extends('_layouts.main')
@section('title', 'Purchase')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.purchase.') }}">@lang('Purchase')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Accounts Payable')</li>
@endpush
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="card card-payment">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 1px">#</th>
                                <th>@lang('Supplier Name')</th>
                                <th>@lang('Total Receive')</th>
                                <th>@lang('Paid Off')</th>
                                <th>@lang('Remainder')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($receives as $receive)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $receive->supplier->name }}</td>
                                <td>{{ number_format($receive->total_amount) }}</td>
                                <td>{{ number_format($receive->paid_off) }}</td>
                                <td>{{ number_format($receive->remainder) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" align="center">
                                    @lang('No Data')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <hr style="margin-top: -1px">
                    {{ $receives->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
