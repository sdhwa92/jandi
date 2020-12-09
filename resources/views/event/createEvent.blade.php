@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
          	<div class="create-event-form-container">
              <form action="{{ route('event.new.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="eventName">제목</label>
                  <input type="text" class="form-control" id="eventName" name="eventName" placeholder="Event title">
                </div>
                <div class="form-group">
                  <label for="eventAddress">주소</label>
                  <input type="text" class="form-control" id="eventAddress" name="eventAddress" placeholder="Where">
                </div>
                <div class="form-group">
                  <label for="eventDate">날짜</label>
                  <input type="date" class="form-control" id="eventDate" name="eventDate" placeholder="When">
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>시작 시간</label>
                      <div class="row">
                        <div class="col-4">
                          <select class="form-control" id="startHour" name="startHour">
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                          </select>
                        </div>
                        <div class="col-4">
                          <select class="form-control" id="startMin" name="startMin">
                            <option value="00">00</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                          </select>
                        </div>
                        <div class="col-4">
                          <select class="form-control" id="startAmPm" name="startAmPm">
                            <option value="am">AM</option>
                            <option value="pm">PM</option>
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
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                          </select>
                        </div>
                        <div class="col-4">
                          <select class="form-control" id="endMin" name="endMin">
                            <option value="00">00</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                          </select>
                        </div>
                        <div class="col-4">
                          <select class="form-control" id="endAmPm" name="endAmPm">
                            <option value="am">AM</option>
                            <option value="pm">PM</option>
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
                      <input type="number" class="form-control" id="minHead" name="minHead" placeholder="Min">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="maxHead">최대 인원</label>
                      <input type="number" class="form-control" id="maxHead" name="maxHead" placeholder="Max">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="eventMemo">메모</label>
                  <textarea class="form-control" id="eventMemo" name="eventMemo"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">생성</button>
                <button type="button" class="btn btn-default">취소</button>
              </form>
            </div>
        </div>
    </div>
</div>
@endsection
