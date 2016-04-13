/**
 * main app
 * the little
 * flickrer
 */
(function () {

  // this app doesn't really do anything except for checking the state of user and decide whether to load form or load search
  var app = SimpleApp('flickrer-results');

  // tempaltes
  app.template.main = {
    default: '<div class="container">{meta}{images}{paging}</div>'
  };
  app.template.sub.meta = {
    default: '<div {attr}><h3>We found: {_total} images<small> showing page {_page}/{_pages}</small></h3></div>'
  };
  app.template.sub.images = {
    _wrapper: ['<div {attr} class="gallery">', '</div>'],
    default: '<a {attr} class="gallery-item" target="_blank" href="{_url}">' +
    '<img src="{_thumb_src}" class="img-thumbnail img-responsive" alt="{_caption}">' +
    '<label>{_caption}</label>' +
    '</a>'
  };
  app.template.sub.paging = {
    _wrapper: ['<ul {attr} class="pagination">', '</ul>'],
    default: '<li><a href="#" onclick="window.search(\'{_tags}\', \'{_text}\', {_page})">{_page}</a></li>'
  };

  // data (late binding)
  app.data = window.searchData;

  app.on(SimpleAppDidRender, 'show-info', function (obj) {
    window.isResultsAppLoaded = true;
  });

  // render
  app.init(document.getElementById('results'), true);
})
();
