/**
 * main app
 * the little
 * flickrer
 */
(function () {

  // this app doesn't really do anything except for checking the state of user and decide whether to load form or load search
  var app = SimpleApp('flickrer-search');

  // tempaltes
  app.template.main = {
    default: '<div class="panel panel-default"><div class="panel-heading">{hdr}</div><div class="panel-body">' +
    '<form role="form">{info}' +
    '<div class="form-group" {attr}><label>tag:</label>{tag}</div>' +
    '<div class="form-group" {attr}><label>text:</label>{text}</div>' +
    '{submit}' +
    '</form>' +
    '</div></div>'
  };

  app.template.sub.hdr = {
    default: '<strong {attr}>{_lbl}</strong>'
  };
  app.template.sub.info = {
    default: '<div {attr}>{_info}</div>'
  };
  app.template.sub.tag = {
    _type: 'input',
    default: '<input {attr} />'
  };
  app.template.sub.text = {
    _type: 'input',
    default: '<input {attr} />'
  };
  app.template.sub.submit = {
    _type: 'button',
    default: '<button {attr}>{_caption}</button>'
  };

  // data
  app.data.hdr = {
    _lbl: 'Flickr Search'
  };
  app.data.info = {
    _info: '<p>Search by text or tag</p>',
    class: 'alert alert-info'
  };
  app.data.tag = {
    type: 'text',
    class: 'form-control',
    placeholder: 'please enter a tag name'
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


  app.on(SimpleAppDidRender, 'show-info', function (obj) {
    $.getJSON('/user').done(function (resp) {
      console.log(resp);
      // check logged in or out
      app.data.hdr._lbl = 'Flickr Search | <small>Welcome, ' + resp.user.name + ', you registered at ' +
        resp.user.created_at + '</small>' +
        '<button class="pull-right btn btn-danger btn-xs" onclick="logout()">logout</button>';
      app.render();
    });
  });

  // callback of submit
  app.on(SimpleAppStateIsUpdated, 'submit', function (obj) {
    console.log(obj);
    var tag = obj.state.tag.replace(/ /g, '');
    var text = obj.state.text.replace(/ /g, '');
    if (tag.length <= 0 && text.length <= 0) {
      app.data.info = {
        _info: '<p><b>ERROR</b>: Please enter text into at least one field</p>',
        class: 'alert alert-danger'
      }
    } else {
      app.data.info = {
        _info: '<p>Search by text or tag</p>',
        class: 'alert alert-info'
      }
    }
    app.render();
  });

  // render
  app.init(document.getElementById('app'), true);

  // extra
  window.logout = function () {
    $.get('/user/logout').done(function(resp) {
      // alert state change
      SimpleApp('flickrer').updateState('userState', {state: resp});
    })
  }

})
();
