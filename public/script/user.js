/**
 * main app
 * the little
 * flickrer
 */
(function () {

  // this app doesn't really do anything except for checking the state of user and decide whether to load form or load search
  var app = SimpleApp('flickrer-user');

  // tempaltes
  app.template.main = {
    default: '<div class="panel panel-primary"><div class="panel-heading">{hdr}</div><div class="panel-body">' +
    '<form role="form">{info}' +
    '<div class="form-group" {attr}><label>Your name:</label>{name}</div>' +
    '<div class="form-group" {attr}><label>Username:</label>{username}</div>' +
    '<div class="form-group" {attr}><label>Password:</label>{password}</div>' +
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
  app.template.sub.name = {
    _type: 'input',
    default: '<input {attr} />'
  };
  app.template.sub.username = {
    _type: 'input',
    default: '<input {attr} />'
  };
  app.template.sub.password = {
    _type: 'input',
    default: '<input {attr} />'
  };
  app.template.sub.submit = {
    _type: 'button',
    default: '<button {attr}>{_caption}</button>'
  };

  // data
  app.data.hdr = {
    _lbl: 'Please register/login to use this service'
  };
  app.data.info = {
    _info: '<p>Please use the following form to register - if you already have an account with us, ' +
    'simply put in your username and password, click the same button and we\'ll log you in</p>',
    class: 'alert alert-info'
  };
  app.data.name = {
    type: 'text',
    class: 'form-control',
    placeholder: 'please enter your full name'
  };
  app.data.username = {
    type: 'text',
    class: 'form-control',
    placeholder: 'please enter your username, must be one word with no spaces or special characters'
  };
  app.data.password = {
    type: 'password',
    class: 'form-control',
    placeholder: 'please enter your password, make it hard to guess!'
  };
  app.data.submit = {
    type: 'button',
    class: 'btn btn-primary',
    _caption: 'Register/Login'
  };

  // callback of submit
  app.on(SimpleAppStateIsUpdated, 'submit', function (obj) {
    $.post('/user/rego', app.state).done(function (resp) {
      // alert state change
      SimpleApp('flickrer').updateState('userState', {state: resp});
    });
  });

  // render
  app.init(document.getElementById('app'), true);

})
();
