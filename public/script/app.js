/**
 * main app
 * the little
 * flickrer
 */
(function () {

  var loadedJS = {};
  /**
   * the main search function
   * @param tags
   * @param text
   * @param page
   * @param callback
   */
  window.search = function (tags, text, page, callback) {
    loading(true);
    $.post('/search', {tags: tags, text: text, page: page}).done(function (resp) {
      loading(false);
      resp = JSON.parse(resp);

      if (!callback) {
        callback = function (resp) {
          // just notify change
          if (resp.success === true) {
            // tell main app to load results
            SimpleApp('flickrer').updateState('imagesFound', resp.data);
          }
          // also re-render the search form
          SimpleApp('flickrer-search').data.tags.value = tags;
          SimpleApp('flickrer-search').data.text.value = text;
          SimpleApp('flickrer-search').render();
        };
      }

      callback(resp);
    });
  };

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
  function checkUserState() {
    loading(true);
    $.getJSON('/user').done(function (resp) {
      loading(false);
      // check logged in or out
      if (resp.isLoggedIn === true) {
        // load search!
        loadJs('script/search.js', 'app-main');
      } else {
        loadJs('script/user.js', 'app-main');
      }
    });
  }

  // this app doesn't really do anything except for checking the state of user and decide whether to load form or load search
  var app = SimpleApp('flickrer', {
    localStorageWrite: false,
    localStorageRead: false
  });

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
    _info: 'Validating your login status, please wait'
  };

  // callback after render
  app.on(SimpleAppFinish, 'checkUserState', function () {
    checkUserState();
  });

  // watch user state, so when user app/search app calls it we can trigger a new load
  app.on(SimpleAppStateIsUpdated, 'userState', function (obj) {
    console.log('User state is changed, check again');
    checkUserState();
  });

  app.on(SimpleAppStateIsUpdated, 'imagesFound', function (obj) {
    window.searchData = obj.state.imagesFound;
    if (!window.isResultsAppLoaded) {
      loadJs('script/results.js');
    } else {
      SimpleApp('flickrer-results').data = obj.state.imagesFound;
      // always re-render
      SimpleApp('flickrer-results').render(true);
    }
  });

  // render
  app.init(document.getElementById('main'), true);

})
();
