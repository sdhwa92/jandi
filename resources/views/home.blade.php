@extends('layouts.app')

@section('content')
<div class="container home-page">
    @if(Auth::check())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row d-flex justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card event-list-wrapper">
                <div class="d-flex justify-content-between align-items-center"> <span class="font-weight-bold">리스트</span>
                    @if(Auth::check())
                    <div class="d-flex flex-row">
                        <a class="btn btn-primary new" href="{{ route('event.new.view') }}"><i class="fa fa-plus"></i> 생성</a> 
                    </div>
                    @endif
                </div>
				        <!-- <div class="mt-3 inputs"> <i class="fa fa-search"></i> <input type="text" class="form-control " placeholder="Search Tasks..."> </div> -->
                @foreach($events as $event)
                <div class="mt-3 event-list">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex flex-row align-items-center"> <span class="star"><i class="fa fa-futbol-o blue"></i></span>
                      <div class="d-flex flex-column"> <span>{{ $event->name }}</span>
                        <div class="d-flex flex-row align-items-center time-text"> <small>{{ $event->event_date . ' ' . $event->start_time }}</small> <span class="dots"></span> <small>viewed Just now</small> <span class="dots"></span> <small>Edited 15 minutes ago</small> </div>
                      </div>
                    </div>
                    <a class="btn btn-dark view-event-btn" href="{{ route('event.view', ['eventId' => $event->id]) }}">보기</a>
                  </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

    

    
@endsection
