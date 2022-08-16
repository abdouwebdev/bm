@extends('_layouts.main')
@section('title', 'Purchase')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.purchase.') }}">@lang('Purchase')</a>
</li>
<li class="breadcrumb-item" aria-current="page">@lang('Offer')</li>
@endpush
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="card card-payment">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.purchase.offer.create') }}" class="btn btn-sm btn-primary shadow"><i data-feather="plus"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover " @if($countOffers == 1) style="height: 140px" @endif>
                        <thead>
                            <tr>
                                <th style="width: 1px">#</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Offer Code')</th>
                                <th>@lang('Total')</th>
                                <th>@lang('Status')</th>
                                <th style="width: 1px">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($offers as $offer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $offer->date }}</td>
                                <td>{{ $offer->code }}</td>
                                <td>{{ number_format($offer->total, 0, ',', '.') }}</td>
                                <td>@if($offer->status == 1)
                                    <span class="badge badge-success">@lang('Open')</span>
                                    @else
                                    <span class="badge badge-warning">@lang('Closed')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow"
                                            data-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.purchase.offer.show', $offer->id) }}">
                                                <i data-feather="eye"></i>
                                                <span class="ml-1">@lang('Show')</span>
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.purchase.offer.edit', $offer->id) }}">
                                                <i data-feather="edit"></i>
                                                <span class="ml-1">@lang('Edit')</span>
                                            </a>
                                            <a href="javascript:void('delete')" class="dropdown-item text-danger" 
                                                onclick="deleteConfirm('form-delete', '{{ $offer->id }}')">
                                                <i data-feather="trash"></i>
                                                <span class="ml-1">@lang('Delete')</span>
                                            </a>
                                            <form id="form-delete{{ $offer->id }}" action="{{ route('admin.purchase.offer.destroy', $offer->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </div>
                                    </div>
                                </td>
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
                    {{ $offers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
