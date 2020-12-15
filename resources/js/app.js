require('./bootstrap');

function checkCurrentPage(path) {
  let pathSplited = path.split("/");

  if (pathSplited[1] == 'event' && pathSplited[3] == 'view') {
    console.log('Event View page');
    return 'event.view';
  }
}

let generalFunc = {
  init: function() {
    console.log('generalFunc init');
    this.setupCSRFToken();
    this.preventDoubleSubmit();
  },
  setupCSRFToken: function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
      }
    });
  },
  // Prevent multiple submites on every form trough the app
  preventDoubleSubmit: function() {
    // console.log('preventDoubleSubmit');
    $('form').on('submit', function() {
      $(this).find( '[type=\'submit\']').attr('disabled', 'true');
    });
  },
  
};

let eventViewFunc = {
  init: function() {
    console.log('eventViewFunc init');
    this.randomTeamSelect();
  },
  randomTeamSelect: function(eventId) {
    // console.log('randomTeamSelect');
    $('#randomTeamBtn').on('click', function() {
      let eventId = $(this).data('event-id');
      // console.log(eventId);
      $.post('/event/' + eventId + '/team/random', function(response) {
        // handle your response here
        location.reload();
      });
    });
  }
};

$(function () {
  generalFunc.init();

  let currentPage = checkCurrentPage($(location).attr('pathname'));
  if (currentPage == 'event.view') {
    eventViewFunc.init();
  }
});