@extends('layouts.app')

@section('content')
<div class="container view-event-page">
    <div class="row justify-content-center">
        <div class="col-md-8">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a class="nav-item nav-link active" id="nav-overview-tab" data-toggle="tab" href="#nav-overview" role="tab" aria-controls="nav-overview" aria-selected="true">장소 및 명단</a>
              <a class="nav-item nav-link" id="nav-teams-tab" data-toggle="tab" href="#nav-teams" role="tab" aria-controls="nav-teams" aria-selected="false">팀</a>
              @if($isHost)
              <a class="nav-item nav-link" id="nav-create-team-tab" data-toggle="tab" href="#nav-create-team" role="tab" aria-controls="nav-create-team" aria-selected="false">팀 생성</a>
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
                              @if(session()->has('message.level'))
                              <div class="alert alert-{{ session('message.level') }}">
                              {!! session('message.content') !!}
                              </div>
                              @endif
                              <form action="{{ route('event.join', ['eventId' => $eventDetails->id]) }}" method="post" enctype="multipart/form-data">
                                  @csrf
                                  {{-- <input type="hidden" name="eventId" value="{{ $eventDetails->id }}" /> --}}
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
                        @if(session()->has('delete.message'))
                        <div class="alert alert-danger">
                        {!! session('delete.message') !!}
                        </div>
                        @endif
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
                                            @if ($participant->status_id == 1)
                                            <span class="badge badge-primary">참가</span>
                                            @elseif ($participant->status_id == 2)
                                            <span class="badge badge-secondary">대기</span>
                                            @elseif ($participant->status_id == 3)
                                            <span class="badge badge-success">완료</span>
                                            @elseif ($participant->status_id == 4)
                                            <span class="badge badge-success">미납</span>
                                            @endif
                                        </td>
                                        <td class="col-team">{{$participant->team_name}}</td>
                                        <td class="col-action">
                                            <a href="{{ route('event.disjoin', ['eventId' => $eventDetails->id, 'participantId' => $participant->id]) }}" class="btn btn-danger btn-sm disjoin-btn"><i class="fa fa-times"></i></a>
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
                  <hr>
                  <div class="tab-content">
                    <form action="{{ route('event.team.select', ['eventId' => $eventDetails->id])}}" method="post" enctype="multipart/form-data">
                      @csrf
                      @foreach ($approvedParticipants as $participant)
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">{{$participant->name}}</label>
                        <div class="col-sm-9">
                          <select class="form-control" id="team-participant-{{$participant->id}}" name="team-participant-{{$participant->id}}">
                            <option value="">-- 팀 선택 --</option>
                            @foreach ($eventTeams as $team)
                            <option value="{{ $team->id }}" {{ $team->id == $participant->team_id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      @endforeach
                      <button type="submit" class="btn btn-primary">저장</button>
                      <button id="randomTeamBtn" type="button" class="btn btn-secondary" data-event-id="{{ $eventDetails->id }}">랜덤</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            {{-- Create Team --}}
            @if($isHost)
            <div class="tab-pane fade" id="nav-create-team" role="tabpanel" aria-labelledby="nav-create-team-tab">
              <div class="card">
                <div class="card-body">
                  <div class="tab-title">
                    <h3>팀 생성</h3>
                  </div>
                  <hr>
                  <div class="tab-content">
                    <form action="{{ route('event.team.create', ['eventId' => $eventDetails->id])}}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <label for="eventName">팀 이름</label>
                        <input type="text" class="form-control" id="teamName" name="teamName" placeholder="팀 이름">
                      </div>
                      <button type="submit" class="btn btn-primary">생성</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            @endif

            {{-- Settings Tab --}}
            @if($isHost)
            <div class="tab-pane fade" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings-tab">
              <div class="card">
                <div class="card-body">
                  <div class="tab-title">
                    <h3>설정</h3>
                  </div>
                  <hr>
                  <div class="tab-content">
                    <form action="{{ route('event.update', ['eventId' => $eventDetails->id]) }}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <label for="eventName">제목</label>
                        <input type="text" class="form-control" id="eventName" name="eventName" placeholder="제목" value="{{$eventDefaultData->name}}">
                      </div>
                      <div class="form-group">
                        <label for="eventAddress">주소</label>
                        <input type="text" class="form-control" id="eventAddress" name="eventAddress" placeholder="장소" value="{{$eventDefaultData->address}}">
                      </div>
                      <div class="form-group">
                        <label for="eventDate">날짜</label>
                        <input type="date" class="form-control" id="eventDate" name="eventDate" placeholder="날짜" value="{{$eventDefaultData->date}}">
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>시작 시간</label>
                            <div class="row">
                              <div class="col-4">
                                <select class="form-control" id="startHour" name="startHour">
                                  @foreach ($hoursOptions as $hourOption)
                                  <option value="{{ $hourOption }}" {{ $hourOption == $eventDefaultData->startHr ? 'selected' : '' }}>{{$hourOption}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="startMin" name="startMin">
                                  @foreach ($minsOptions as $minOption)
                                  <option value="{{ $minOption }}" {{ $minOption == $eventDefaultData->startMin ? 'selected' : '' }}>{{ $minOption }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="startAmPm" name="startAmPm">
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
                                <select class="form-control" id="endHour" name="endHour">
                                  @foreach ($hoursOptions as $hourOption)
                                  <option value="{{ $hourOption }}" {{ $hourOption == $eventDefaultData->endHr ? 'selected' : '' }}>{{$hourOption}}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="endMin" name="endMin">
                                  @foreach ($minsOptions as $minOption)
                                  <option value="{{ $minOption }}" {{ $minOption == $eventDefaultData->endMin ? 'selected' : '' }}>{{ $minOption }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="col-4">
                                <select class="form-control" id="endAmPm" name="endAmPm">
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
