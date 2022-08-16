@extends('_layouts.main')
@section('title', 'Purchase')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.purchase.') }}">@lang('Purchase')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Receivable Payment')</li>
@endpush
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="card card-payment">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.purchase.payment.create') }}" class="btn btn-sm btn-primary shadow"><i data-feather="plus"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 1px">#</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Code')</th>
                                <th>@lang('Supplier Name')</th>
                                <th>@lang('Total')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->date }}</td>
                                <td>{{ $payment->code }}</td>
                                <td>{{ $payment->supplier->name }}</td>
                                <td>{{ number_format($payment->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" align="center">
                                    @lang('No Data')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <hr style="margin-top: -1px">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
