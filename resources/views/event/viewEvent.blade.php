@extends('layouts.app')

@section('content')
<div class="container view-event-page">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-overview" role="tab" aria-controls="nav-overview" aria-selected="true">장소 및 명단</a>
              <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-teams" role="tab" aria-controls="nav-teams" aria-selected="false">팀</a>
              @if($isHost)
              <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-settings" role="tab" aria-controls="nav-settings" aria-selected="false">설정</a>
              @endif
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
              <div class="card">
                <!-- <div class="card-header">{{ __('Event Details') }}</div> -->
                <div class="card-body">
                    
                    <div class="event-details-wrapper">
                        <h3>{{ $eventDetails->name }}</h3>
                        <ul>
                            <li>장소: {{ $eventDetails->address }}</li>
                            <li>날짜: {{ $eventDetails->event_date }}</li>
                            <li>시간: {{ $eventDetails->start_time . ' - ' . $eventDetails->end_time }}</li>
                        </ul>
                    </div>

                    <div class="register-form-wrapper mt-4">
                      <div class="row">
                          <div class="col-12">
                              <form action="{{ route('event.join') }}" method="post" enctype="multipart/form-data">
                                  @csrf
                                  <input type="hidden" name="eventId" value="{{ $eventDetails->id }}" />
                                  <div class="input-group mb-3">
                                      <input type="text" class="form-control" id="participantName" name="participantName" placeholder="이름">
                                      <div class="input-group-append">
                                          <button class="btn btn-primary" type="submit">참가</button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                    </div>

                    <div class="event-participants-wrapper">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">이름</th>
                                    <th scope="col">상태</th>
                                    <th scope="col">팀</th>
                                    <th scope="col">취소</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach ($eventParticipants as $participant)
                                    <tr>
                                        <td class="col-number" scope="row">{{$i}}</td>
                                        <td class="col-name">{{$participant->name}}</td>
                                        <td class="col-state">
                                            @if ($i <= $eventDetails->max_head)
                                            <span class="badge badge-primary">참가</span>
                                            @else
                                            <span class="badge badge-secondary">대기</span>
                                            @endif
                                        </td>
                                        <td class="col-team">{{$participant->team}}</td>
                                        <td class="col-action">
                                            <a href="{{ route('event.disjoin', ['participantId' => $participant->id]) }}" class="btn btn-danger btn-sm disjoin-btn"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>                            
                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>

            {{-- Team Tab --}}
            <div class="tab-pane fade" id="nav-teams" role="tabpanel" aria-labelledby="nav-teams-tab">
              <div class="card">
                <div class="card-body">
                  <div class="tab-title">
                    <h3>팀</h3>
                  </div>
                  <div class="tab-content">
                    
                  </div>
                </div>
              </div>
            </div>

            {{-- Settings Tab --}}
            @if($isHost)
            <div class="tab-pane fade" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings-tab">
              <div class="card">
                <div class="card-body">
                  <div class="tab-title">
                    <h3>설정</h3>
                  </div>
                  <div class="tab-content">
                    <form action="{{ route('event.update', ['eventId' => $eventDetails->id]) }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <label for="eventName">제목</label>
                        <input type="text" class="form-control" id="eventName" name="eventName" placeholder="Event title" value="{{$eventDefaultData->name}}">
                      </div>
                      <div class="form-group">
                        <label for="eventAddress">주소</label>
                        <input type="text" class="form-control" id="eventAddress" name="eventAddress" placeholder="Where" value="{{$eventDefaultData->address}}">
                      </div>
                      <div class="form-group">
                        <label for="eventDate">날짜</label>
                        <input type="date" class="form-control" id="eventDate" name="eventDate" placeholder="When" value="{{$eventDefaultData->date}}">
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>시작 시간</label>
                            <div class="row">
                              <div class="col-4">
                                <select class="form-control" id="startHour" name="startHour" value={{$eventDefaultData->startHr}}>
                                  @foreach ($hoursOptions as $hourOption)
                                  <option value="{{ $hourOption }}" {{ $hourOption == $eventDefaultData->startHr ? 'selected' : '' }}>{{$hourOption}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="startMin" name="startMin" value="{{$eventDefaultData->startMin}}">
                                  @foreach ($minsOptions as $minOption)
                                  <option value="{{ $minOption }}" {{ $minOption == $eventDefaultData->startMin ? 'selected' : '' }}>{{ $minOption }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="startAmPm" name="startAmPm" value="{{$eventDefaultData->startAmPm}}">
                                  <option value="am" {{ $eventDefaultData->startAmPm == 'am' ? 'selected' : '' }}>AM</option>
                                  <option value="pm" {{ $eventDefaultData->startAmPm == 'pm' ? 'selected' : '' }}>PM</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
      
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>종료 시간</label>
                            <div class="row">
                              <div class="col-4">
                                <select class="form-control" id="endHour" name="endHour" value="{{$eventDefaultData->endHr}}">
                                  @foreach ($hoursOptions as $hourOption)
                                  <option value="{{ $hourOption }}" {{ $hourOption == $eventDefaultData->endHr ? 'selected' : '' }}>{{$hourOption}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="endMin" name="endMin" value="{{$eventDefaultData->endMin}}">
                                  @foreach ($minsOptions as $minOption)
                                  <option value="{{ $minOption }}" {{ $minOption == $eventDefaultData->endMin ? 'selected' : '' }}>{{ $minOption }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="endAmPm" name="endAmPm" value="{{$eventDefaultData->endAmPm}}">
                                  <option value="am" {{ $eventDefaultData->endAmPm == 'am' ? 'selected' : '' }}>AM</option>
                                  <option value="pm" {{ $eventDefaultData->endAmPm == 'am' ? 'selected' : '' }}>PM</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="minHead">최소 인원</label>
                            <input type="number" class="form-control" id="minHead" name="minHead" placeholder="Min" value="{{$eventDefaultData->min}}">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label for="maxHead">최대 인원</label>
                            <input type="number" class="form-control" id="maxHead" name="maxHead" placeholder="Max" value="{{$eventDefaultData->max}}">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="eventMemo">메모</label>
                        <textarea class="form-control" id="eventMemo" name="eventMemo">{{$eventDefaultData->memo}}</textarea>
                      </div>
                      <button type="submit" class="btn btn-primary">수정</button>
                      <button type="button" class="btn btn-default">취소</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>
            
        </div>
    </div>
</div>
@endsection
