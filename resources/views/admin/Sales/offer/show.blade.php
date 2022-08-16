@extends('_layouts.main')
@section('title', 'Offer')
@push('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.sales.') }}">@lang('Sale')</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.sales.offer.index') }}">@lang('Offer')</a>
</li>
@endpush
@section('content')

<div class="row">
  <!-- end message area-->
  <div class="col-md-12">
      <div class="card py-2">
          <div class="card-header d-flex justify-content-center">
              @if($offer->status == 1)
                  <h1 class="card-title">
                      <span class="badge badge-success">@lang('Status') : @lang('Open')</span>
                  </h1>
              @else
                  <h1 class="card-title">
                      <span class="badge badge-warning">@lang('Status') : @lang('Closed')</span>
                  </h1>
              @endif
          </div>
          <div class="card-body pt-2">
              <table class="table">
                  <tr>
                      <th class="code_journal">
                          @lang('Code')
                          <span class="float-right">:</span>
                      </th>
                      <td>{{ $offer->code  }}</td>
                  </tr>
                  <tr>
                      <th>
                          @lang('Date')
                          <span class="float-right">:</span>
                      </th>
                      <td>{{ $offer->date }}</td>
                  </tr>
              </table>
              <div class="table-responsive">
                  <table class="table mt-2">
                      <thead>
                          <tr>
                              <th>@lang('Product')</th>
                              <th>@lang('Unit')</th>
                              <th>@lang('Price')</th>
                              <th>@lang('Amount')</th>
                              <th>@lang('Total')</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($offer->offer_details as $item)
                              <tr>
                                  <td>{{ $item->product->name }}</td>
                                  <td>{{ $item->unit }}</td>
                                  <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                                  <td>{{ $item->amount }}</td>
                                  <td>{{  number_format($item->total, 0, ',', '.') }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                      <tfoot>
                          <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th>
                                {{ number_format($offer->total, 0, ',', '.') }}
                              </th>
                          </tr>
                      </tfoot>
                  </table>
              </div>
          </div>
          <div class="card-footer">
            <a class="btn btn-primary" href="{{ route('admin.sales.offer.index') }}">
              @lang('RETURN')
            </a>
          </div>
      </div>
  </div>
</div>
@endsection
