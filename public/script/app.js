/**
 * main app
 * the little
 * flickrer
 */
(function () {

  var loadedJS = {};

  /**
   * load js dynamically,
   * we'll use this to switch user/search apps respectively
   * @param src
   * @param nodeName
   */
  function loadJs(src, nodeName) {
    if (loadedJS[nodeName]) {
      // remove existing one
      try {
        document.head.removeChild(loadedJS[nodeName]);
      } catch (e) {
        console.log('ERROR:', e);
      }
      loadedJS[nodeName] = null;
    }
    if (!loadedJS[src]) {
      var s = document.createElement('script');
      s.setAttribute("type", "text/javascript");
      s.setAttribute('defer', 'defer');
      s.setAttribute("src", src + '?d=' + Date.now());
      document.head.appendChild(s);
      if (!nodeName) nodeName = src;
      loadedJS[nodeName] = s;
      console.log('script: ' + src + ' is loaded');
    } else {
      // remove it and load again
      try {
        console.log('remove already loaded script and reload');
        document.head.removeChild(loadedJS[src]);
        // then load again...
        loadedJS[src] = null;
        loadJs(src);
      } catch (e) {
        console.log('ERROR', e);
      }
    }
  }

  /**
   * state check and load js accordingly
   */
  function checkUserState()
  {
    $.getJSON('/user').done(function (resp) {
      // check logged in or out
      if (resp.isLoggedIn === true) {
        // load search!
        loadJs('script/search.js', 'app-main');
      } else {
        loadJs('script/user.js', 'app-main');
      }
      // clean the middle
      document.getElementById('app').innerHTML = '';
    });
  }

  // this app doesn't really do anything except for checking the state of user and decide whether to load form or load search
  var app = SimpleApp('flickrer');

  app.template.main = {
    default: '<div class="container">' +
    '<h2>flickrer: <small>light weight flckr search powered by microservice and frontend JS app</small></h2>' +
    '<div id="app">{content}</div>' +
    '</div>'
  };

  app.template.sub.content = {
    default: '<div {attr}><label>{_info}</label></div>'
  };

  app.data.content = {
    _info: 'searching for current user, please wait...'
  };

  // callback after render
  app.on(SimpleAppDidRender, 'checkUserState', function () {
    checkUserState();
  });

  // watch user state, so when user app/search app calls it we can trigger a new load
  app.on(SimpleAppStateIsUpdated, 'userState', function (obj) {
    console.log('User state is changed, check again');
    checkUserState();
  });

  // render
  app.init(document.getElementById('main'), true);


})
();
