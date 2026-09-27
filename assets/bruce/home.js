/* ==========================================================================
   St. Joseph Catholic School of Sagay, Inc. - Public homepage behaviour.
   Vanilla JS, no dependencies. Loaded with `defer` from homepage_public.php.
   Every effect degrades to a static, fully readable page without JS, and
   prefers-reduced-motion switches all decorative motion off.
   ========================================================================== */
(function () {
  'use strict';

  var doc = document;
  var body = doc.body;
  var reduceMQ = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : { matches: false };
  var desktopMQ = window.matchMedia ? window.matchMedia('(min-width: 1024px)') : { matches: true };
  var reduce = reduceMQ.matches;

  function $(sel, root) { return (root || doc).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || doc).querySelectorAll(sel)); }
  function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

  /* ---------- 1. Hero entrance ---------- */
  function heroReady() { body.classList.add('bc-ready'); }
  if (reduce) { heroReady(); }
  else if (doc.fonts && doc.fonts.ready) {
    // Wait briefly for web fonts so the lines don't re-flow mid-animation.
    var done = false;
    var go = function () { if (!done) { done = true; requestAnimationFrame(heroReady); } };
    doc.fonts.ready.then(go);
    setTimeout(go, 450);
  } else { requestAnimationFrame(heroReady); }

  /* ---------- 2. Scroll reveals (staggered through --d) ---------- */
  var revealEls = $$('[data-reveal], .bc-clip');
  function settle(el) {
    var d = parseFloat(getComputedStyle(el).getPropertyValue('--d')) || 0;
    setTimeout(function () { el.classList.add('bc-settled'); }, d + 950);
  }
  function show(el) { el.classList.add('is-visible'); if (el.hasAttribute('data-reveal')) { settle(el); } }
  if (reduce || !('IntersectionObserver' in window)) {
    revealEls.forEach(function (el) { el.classList.add('is-visible', 'bc-settled'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { show(e.target); io.unobserve(e.target); }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(function (el) { io.observe(el); });
  }

  /* ---------- 3. Counters ---------- */
  var counters = $$('[data-count]');
  function runCounter(el) {
    var target = parseInt(el.getAttribute('data-count'), 10) || 0;
    var start = null, dur = 1400;
    el.textContent = '0';
    function tick(t) {
      if (start === null) { start = t; }
      var k = clamp((t - start) / dur, 0, 1);
      var eased = 1 - Math.pow(1 - k, 3);
      el.textContent = String(Math.round(target * eased));
      if (k < 1) { requestAnimationFrame(tick); }
    }
    requestAnimationFrame(tick);
  }
  if (!reduce && 'IntersectionObserver' in window && counters.length) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { runCounter(e.target); cio.unobserve(e.target); }
      });
    }, { threshold: 0.6 });
    counters.forEach(function (c) { cio.observe(c); });
  }

  /* ---------- 4. Mobile drawer ---------- */
  var menuBtn = $('#bcMenuBtn');
  var drawer = $('#bcDrawer');
  var drawerClose = $('#bcDrawerClose');
  var lastFocus = null;
  function focusables(root) {
    return $$('a[href], button:not([disabled]), input, video[controls], [tabindex]:not([tabindex="-1"])', root)
      .filter(function (el) { return el.offsetParent !== null || el === doc.activeElement; });
  }
  function trap(root, ev) {
    if (ev.key !== 'Tab') { return; }
    var f = focusables(root);
    if (!f.length) { return; }
    var first = f[0], last = f[f.length - 1];
    if (ev.shiftKey && doc.activeElement === first) { ev.preventDefault(); last.focus(); }
    else if (!ev.shiftKey && doc.activeElement === last) { ev.preventDefault(); first.focus(); }
  }
  function openDrawer() {
    if (!drawer) { return; }
    lastFocus = doc.activeElement;
    drawer.hidden = false;
    body.classList.add('bc-lock');
    menuBtn.setAttribute('aria-expanded', 'true');
    requestAnimationFrame(function () { requestAnimationFrame(function () { drawer.classList.add('is-open'); }); });
    drawerClose.focus();
  }
  function closeDrawer(restore) {
    if (!drawer || drawer.hidden) { return; }
    drawer.classList.remove('is-open');
    menuBtn.setAttribute('aria-expanded', 'false');
    body.classList.remove('bc-lock');
    var hide = function () { drawer.hidden = true; };
    if (reduce) { hide(); } else { setTimeout(hide, 300); }
    if (restore !== false && lastFocus) { lastFocus.focus(); }
  }
  if (menuBtn && drawer) {
    menuBtn.addEventListener('click', openDrawer);
    drawerClose.addEventListener('click', function () { closeDrawer(); });
    drawer.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape') { closeDrawer(); } else { trap(drawer, ev); }
    });
    $$('a', drawer).forEach(function (a) {
      a.addEventListener('click', function () { closeDrawer(false); });
    });
    desktopMQ.addEventListener && desktopMQ.addEventListener('change', function (e) { if (e.matches) { closeDrawer(false); } });
  }

  /* ---------- 5. Email chooser + photo lightbox (dialogs) ---------- */
  function makeDialog(root, onKey) {
    var opener = null;
    function open(from) {
      opener = from || doc.activeElement;
      root.hidden = false;
      body.classList.add('bc-lock');
      var f = focusables(root);
      if (f.length) { f[0].focus(); }
    }
    function close() {
      root.hidden = true;
      body.classList.remove('bc-lock');
      if (opener) { opener.focus(); }
    }
    root.addEventListener('click', function (ev) { if (ev.target.closest('[data-close]')) { close(); } });
    root.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape') { close(); return; }
      if (onKey) { onKey(ev); }
      trap(root, ev);
    });
    return { open: open, close: close };
  }

  var emailModal = $('#bcEmailModal');
  var emailBtn = $('#bcEmailBtn');
  if (emailModal && emailBtn) {
    var emailDlg = makeDialog(emailModal);
    emailBtn.addEventListener('click', function () { emailDlg.open(emailBtn); });
    $$('.bc-modal-opt', emailModal).forEach(function (a) {
      a.addEventListener('click', function () { setTimeout(emailDlg.close, 120); });
    });
  }

  var lb = $('#bcLightbox');
  var lbItems = $$('[data-lightbox]').filter(function (f) { return $('img', f); });
  if (lb && lbItems.length) {
    var elImg = $('#bcLbImg'), elCat = $('#bcLbCat'), elTitle = $('#bcLbTitle'), elText = $('#bcLbText'), elCount = $('#bcLbCount');
    var index = 0;
    var render = function (i) {
      index = (i + lbItems.length) % lbItems.length;
      var f = lbItems[index], img = $('img', f);
      elImg.src = img.currentSrc || img.src;
      elImg.alt = img.alt || '';
      elCat.textContent = f.getAttribute('data-cat') || '';
      elTitle.textContent = f.getAttribute('data-title') || img.alt || '';
      var t = f.getAttribute('data-text') || '';
      elText.textContent = t; elText.hidden = !t;
      elCount.textContent = (index + 1) + ' of ' + lbItems.length;
    };
    var lbDlg = makeDialog(lb, function (ev) {
      if (ev.key === 'ArrowLeft') { render(index - 1); }
      if (ev.key === 'ArrowRight') { render(index + 1); }
    });
    $('#bcLbPrev').addEventListener('click', function () { render(index - 1); });
    $('#bcLbNext').addEventListener('click', function () { render(index + 1); });
    lbItems.forEach(function (f, i) {
      f.setAttribute('tabindex', '0');
      f.setAttribute('role', 'button');
      f.setAttribute('aria-label', 'Enlarge photo: ' + (f.getAttribute('data-title') || $('img', f).alt));
      var open = function () { render(i); lbDlg.open(f); };
      f.addEventListener('click', open);
      f.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); open(); }
      });
    });
  }

  /* ---------- 6. Learner's journey ---------- */
  var track = $('#bcTrack');
  var path = $('#bcTrackPath');
  var tabs = $$('.bc-tab');
  var panels = $$('.bc-panel');
  var nodes = $$('.bc-node');
  var labels = $$('.bc-track-label');
  var vline = $('#bcVline');
  var vfill = $('#bcVfill');
  var vstages = $$('.bc-vstage');
  var pathLen = 0;
  var active = 0;
  var lastScrollStage = -1;
  var userLockUntil = 0; // a click/key choice wins over scroll for a moment
  function userChose() {
    userLockUntil = Date.now() + 900;
    lastScrollStage = stageFromProgress(trackProgress());
  }
  var STAGES = tabs.length || 4;

  if (path && path.getTotalLength) {
    pathLen = path.getTotalLength();
    path.style.strokeDasharray = pathLen + ' ' + pathLen;
    path.style.strokeDashoffset = reduce ? '0' : String(pathLen);
    // Place nodes and labels exactly on the curve.
    nodes.forEach(function (g, i) {
      var pt = path.getPointAtLength(pathLen * i / (STAGES - 1));
      $$('circle', g).forEach(function (c) { c.setAttribute('cx', pt.x.toFixed(1)); c.setAttribute('cy', pt.y.toFixed(1)); });
      if (labels[i]) { labels[i].style.left = (pt.x / 800 * 100).toFixed(2) + '%'; }
    });
  }

  function setActive(i, opts) {
    opts = opts || {};
    i = clamp(i, 0, STAGES - 1);
    var changed = i !== active;
    active = i;
    tabs.forEach(function (t, k) {
      var on = k === i;
      t.setAttribute('aria-selected', on ? 'true' : 'false');
      t.setAttribute('tabindex', on ? '0' : '-1');
    });
    panels.forEach(function (p, k) {
      var on = k === i;
      p.hidden = !on;
      if (on && changed && !reduce) {
        p.classList.remove('is-entering'); void p.offsetWidth; p.classList.add('is-entering');
      }
    });
    nodes.forEach(function (n, k) { n.classList.toggle('is-active', k === i); });
    labels.forEach(function (l, k) { l.classList.toggle('is-active', k === i); });
    if (opts.focus && tabs[i]) { tabs[i].focus(); }
    updateTrack();
  }

  // Desktop progress: starts when the path is 85% down the viewport and
  // finishes when it reaches 45%, i.e. while the whole section is still in view.
  function trackProgress() {
    if (!track) { return 0; }
    var r = track.getBoundingClientRect(), vh = window.innerHeight || 800;
    return clamp((vh * 0.85 - r.top) / (vh * 0.40), 0, 1);
  }
  function stageFromProgress(p) { return clamp(Math.floor(p * (STAGES - 1) + 0.02), 0, STAGES - 1); }

  function updateTrack() {
    if (!track || !pathLen) { return; }
    var p = trackProgress();
    // Never show the active milestone without its segment of the path.
    var drawn = reduce ? 1 : Math.max(p, active / (STAGES - 1));
    path.style.strokeDashoffset = String(pathLen * (1 - drawn));
    var reachedUpTo = reduce ? STAGES - 1 : Math.max(stageFromProgress(drawn), active);
    nodes.forEach(function (n, k) { n.classList.toggle('is-reached', k <= reachedUpTo); });
    labels.forEach(function (l, k) { l.classList.toggle('is-reached', k <= reachedUpTo); });
  }

  function journeyOnScroll() {
    if (desktopMQ.matches) {
      if (!track) { return; }
      var s = stageFromProgress(trackProgress());
      if (Date.now() < userLockUntil) { lastScrollStage = s; updateTrack(); }
      else if (s !== lastScrollStage) { lastScrollStage = s; setActive(s); }
      else { updateTrack(); }
    } else if (vline) {
      var r = vline.getBoundingClientRect(), vh = window.innerHeight || 800, mark = vh * 0.6;
      if (vfill && !reduce) { vfill.style.setProperty('--fill', (clamp((mark - r.top) / r.height, 0, 1) * 100).toFixed(1) + '%'); }
      var cur = 0;
      vstages.forEach(function (li, k) { var top = li.getBoundingClientRect().top; li.classList.toggle('is-reached', top < mark); if (top < mark) { cur = k; } });
      vstages.forEach(function (li, k) {
        li.classList.toggle('is-active', k === cur);
        if (k === cur) { li.setAttribute('aria-current', 'step'); } else { li.removeAttribute('aria-current'); }
      });
    }
  }

  tabs.forEach(function (t, i) {
    t.addEventListener('click', function () { userChose(); setActive(i); });
    t.addEventListener('keydown', function (ev) {
      var k = ev.key, next = null;
      if (k === 'ArrowDown' || k === 'ArrowRight') { next = active + 1; }
      else if (k === 'ArrowUp' || k === 'ArrowLeft') { next = active - 1; }
      else if (k === 'Home') { next = 0; }
      else if (k === 'End') { next = STAGES - 1; }
      if (next === null) { return; }
      ev.preventDefault();
      userChose();
      setActive((next + STAGES) % STAGES, { focus: true });
    });
  });
  nodes.concat(labels).forEach(function (el) {
    el.addEventListener('click', function () {
      userChose();
      setActive(parseInt(el.getAttribute('data-stage'), 10) || 0);
    });
  });

  /* ---------- 7. Header state, scroll-spy, parallax (one rAF loop) ---------- */
  var header = $('#bcHeader');
  var navLinks = $$('.bc-nav-link');
  var dotLinks = $$('.bc-dots a');
  var spyIds = dotLinks.map(function (a) { return a.getAttribute('data-dot'); });
  var spySections = spyIds.map(function (id) { return doc.getElementById(id); });
  var parallax = $$('[data-parallax]');
  var parallaxImg = $$('[data-parallax-img]');

  function spy() {
    var line = (window.innerHeight || 800) * 0.35;
    var current = spyIds[0];
    spySections.forEach(function (sec, k) { if (sec && sec.getBoundingClientRect().top <= line) { current = spyIds[k]; } });
    navLinks.forEach(function (a) {
      if (a.getAttribute('data-spy') === current) { a.setAttribute('aria-current', 'true'); }
      else { a.removeAttribute('aria-current'); }
    });
    dotLinks.forEach(function (a) {
      var on = a.getAttribute('data-dot') === current;
      a.classList.toggle('is-current', on);
      if (on) { a.setAttribute('aria-current', 'true'); } else { a.removeAttribute('aria-current'); }
    });
  }

  function doParallax() {
    if (reduce) { return; }
    var vh = window.innerHeight || 800;
    parallax.forEach(function (el) {
      var r = el.getBoundingClientRect();
      if (r.bottom < -200 || r.top > vh + 200) { return; }
      var f = parseFloat(el.getAttribute('data-parallax')) || 0.1;
      var y = clamp((vh / 2 - (r.top + r.height / 2)) * f, -40, 40);
      el.style.transform = 'translate3d(0,' + y.toFixed(1) + 'px,0)';
    });
    parallaxImg.forEach(function (el) {
      var r = el.getBoundingClientRect();
      if (r.bottom < 0 || r.top > vh) { return; }
      var f = parseFloat(el.getAttribute('data-parallax-img')) || 0.04;
      el.style.setProperty('--py', clamp((vh / 2 - (r.top + r.height / 2)) * f, -24, 24).toFixed(1) + 'px');
    });
  }

  var ticking = false;
  function frame() {
    ticking = false;
    if (header) { header.classList.toggle('is-scrolled', window.scrollY > 24); }
    spy();
    doParallax();
    journeyOnScroll();
  }
  function request() { if (!ticking) { ticking = true; requestAnimationFrame(frame); } }
  window.addEventListener('scroll', request, { passive: true });
  window.addEventListener('resize', request);
  if (desktopMQ.addEventListener) { desktopMQ.addEventListener('change', request); }
  setActive(0);
  frame();
})();
