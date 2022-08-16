@extends('_layouts.main')
@section('title', 'Account')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{asset('img/pub.jpg')}}" width="600"/>
            <hr>
            <h4>@lang('Your DAARA has launch') <strong>{{ $commCount }}</strong> @lang('Jackpot')</h4>
            @foreach ($jackpots as $jackpot)
            <div class="d-flex">
                <p class="card-text">
                    <span class=" btn btn-outline-success mt-2">@lang('Jackpot Name') : {{ $jackpot->name }}</span>
                    <span class=" btn btn-outline-primary mt-2">@lang('Jackpot Amount') : {{ number_format( $jackpot->amount, 0, ',', '.') }}</span>
                </p>
            </div>
            @endforeach
        </div>
        <div class="col-md-6">
            <h4 class="text-center">@lang('Communication')</h4>
            @foreach ($communications as $communication)
            <h5 class="card-body text-center">{{ $communication->title }}</h5>
            <p class="text-justify mr-1 ml-1">
                {{ $communication->body }}
            </p>
            <span class="text-muted ml-1">{{ $communication->created_at }}</span>
            <hr>
            @endforeach
        </div>
    </div>
</div>
@endsection