// JavaScript to highlight the clicked sidebar link
document.querySelectorAll('aside ul li a').forEach(link => {
    link.addEventListener('click', function() {
        // Remove 'active' class from all links
        document.querySelectorAll('aside ul li a').forEach(item => {
            item.classList.remove('active');
        });

        // Add 'active' class to the clicked link
        this.classList.add('active');
    });
});

// Theme toggle + menu active handling (robust)
(function () {
  function isDark() {
    return document.documentElement.classList.contains('theme-dark');
  }

  function applyTheme(dark) {
    document.documentElement.classList.toggle('theme-dark', dark);
    document.body.classList.toggle('theme-dark', dark);
    localStorage.setItem('theme', dark ? 'dark' : 'light');
    syncToggleIcons();
  }

  function syncToggleIcons() {
    var dark = isDark();
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      var icon = btn.querySelector('[data-theme-icon]');

      if (!icon) {
        // Clear any legacy icons (svg or i elements) before inserting the managed icon
        btn.querySelectorAll('i, svg').forEach(function (extraIcon) {
          extraIcon.remove();
        });

        icon = document.createElement('i');
        icon.setAttribute('data-theme-icon', '');
        btn.appendChild(icon);
      } else if (icon.tagName !== 'I') {
        // Font Awesome JS can replace <i> with <svg>; normalize back to <i> so toggle logic stays simple
        var replacement = document.createElement('i');
        replacement.setAttribute('data-theme-icon', '');
        icon.replaceWith(replacement);
        icon = replacement;
      }

      icon.className = 'fas ' + (dark ? 'fa-sun' : 'fa-moon');
      btn.title = dark ? 'Switch to light theme' : 'Switch to dark theme';
      btn.setAttribute('aria-label', dark ? 'Activate light theme' : 'Activate dark theme');
      btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
    });
  }

  try {
    var saved = localStorage.getItem('theme');
    if (saved === 'dark' || saved === 'light') {
      applyTheme(saved === 'dark');
    } else {
      syncToggleIcons();
    }

    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        applyTheme(!isDark());
      });
    });

    window.addEventListener('storage', function (e) {
      if (e.key === 'theme') {
        applyTheme((e.newValue || '') === 'dark');
      }
    });

    document.querySelectorAll('nav.menu a').forEach(function (link) {
      link.addEventListener('click', function () {
        document.querySelectorAll('nav.menu a').forEach(function (item) {
          item.classList.remove('active');
        });
        this.classList.add('active');
      });
    });
  } catch (e) {
    console.warn('Theme toggle setup failed', e);
  }
})();

