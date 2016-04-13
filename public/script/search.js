/**
 * main app
 * the little
 * flickrer
 */
(function () {
  // this app doesn't really do anything except for checking the state of user and decide whether to load form or load search
  var app = SimpleApp('flickrer-search', {
    localStorageWrite: false,
    localStorageRead: false
  });

  // tempaltes
  app.template.main = {
    default: '<div class="panel panel-default"><div class="panel-heading">{hdr}</div><div class="panel-body">' +
    '<div class="form-inline">{info}' +
    '<div class="form-group" {attr}><label>tags: </label>{tags}</div>' +
    '<div class="form-group" {attr}><label>text: </label>{text}</div>' +
    '{submit}' +
    '</div>' +
    '<div class="container">{recent_searches}</div>' +
    '</div>' +
    '</div>'
  };

  app.template.sub.hdr = {
    default: '<strong {attr}>{_lbl}</strong>'
  };
  app.template.sub.info = {
    default: '<div {attr}>{_info}</div>'
  };
  app.template.sub.tags = {
    _type: 'input',
    default: '<input style="margin:0 10px" {attr} />'
  };
  app.template.sub.text = {
    _type: 'input',
    default: '<input style="margin:0 10px" {attr} />'
  };
  app.template.sub.submit = {
    _type: 'button',
    default: '<button {attr}>{_caption}</button>'
  };
  app.template.sub.recent_searches = {
    _wrapper: ['<div {attr} class="list-group col-xs-8" style="margin: 20px 0;">', '</div>'],
    default: '<a {attr} class="list-group-item" href="#" onclick="window.search(\'{_tags}\', \'{_text}\', 1)">You searched for:' +
    ' <em class="label label-info">{_tags} {_text}</em> at <small>{_created_at}</small></a>',
    info: '<a {attr} class="list-group-item">{_info}</a>'
  };

  // data
  app.data.hdr = {
    _lbl: 'Flickr Search'
  };
  app.data.info = {
    _info: '<p><strong>Search by text or tags</strong></p>',
    class: ''
  };
  app.data.tags = {
    type: 'text',
    class: 'form-control',
    placeholder: 'please enter tags separated by comma'
  };
  app.data.text = {
    type: 'text',
    class: 'form-control',
    placeholder: 'please enter any text you would like to search'
  };
  app.data.submit = {
    type: 'button',
    class: 'btn btn-primary',
    _caption: 'Search'
  };
  app.data.recent_searches = {
    element: [{
      _style: 'info',
      _info: 'your recent searches will show here'
    }]
  };

  app.state.tags = '';
  app.state.text = '';


  app.on(SimpleAppFinish, 'show-info', function (obj) {
    $.getJSON('/user').done(function (resp) {
      // check logged in or out
      app.data.hdr._lbl = 'Flickr Search | <small>Welcome, ' + resp.user.name + ', you registered at ' +
        resp.user.created_at + '</small>' +
        '<button class="pull-right btn btn-danger btn-xs" onclick="logout()">logout</button>';
      app.render();
    });
    $.getJSON('/search/recent').done(function (resp) {
      // check logged in or out
      if (resp.data) {
        app.data.recent_searches = resp.data;
        app.render();
      }
    });
  });

  // callback of submit
  app.on(SimpleAppStateIsUpdated, 'submit', function (obj) {
    loading(true);
    var tags = obj.state.tags.replace(/ /g, '');
    var text = obj.state.text.replace(/ /g, '');
    if (tags.length <= 0 && text.length <= 0) {
      app.data.info = {
        _info: '<p><b>ERROR</b>: Please enter text into at least one field</p>',
        class: 'alert alert-danger'
      };
      loading(false);
      return app.render();
    } else {
      app.data.info = {
        _info: '<p><strong>Search for </strong><em>' + tags + ' ' + text + '</em></p>',
        class: ''
      }
    }
    // request search
    search(obj.state.tags, obj.state.text, 1, function (resp) {
      // verify
      if (resp.success !== true) {
        app.data.info = {
          _info: '<p><b>ERROR</b>: ' + resp.error + '</p>',
          class: 'alert alert-danger'
        };
      } else {
        // tell main app to load results
        SimpleApp('flickrer').updateState('imagesFound', resp.data);
      }
      app.render();
    });
  });

  // render
  app.init(document.getElementById('app'), true);

  // extra
  window.logout = function () {
    $.get('/user/logout').done(function (resp) {
      // alert state change
      SimpleApp('flickrer').updateState('userState', {state: resp});
    })
  }

})
();
