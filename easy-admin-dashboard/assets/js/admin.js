(function($){
  function eadGet(key, fallback){
    try{ return localStorage.getItem(key) ?? fallback; }catch(e){ return fallback; }
  }
  function eadSet(key, value){
    try{ localStorage.setItem(key, value); }catch(e){}
  }

  // Apply compact preference from localStorage immediately
  const compactEnabled = Boolean(parseInt(eadGet('ead_compact_menu', '0')));
  if (compactEnabled || document.body.classList.contains('ead-compact')) {
    document.body.classList.add('ead-compact');
  }

  // Scheme management (light/dark/system)
  function applyScheme(scheme){
    if (scheme === 'system'){
      document.body.removeAttribute('data-ead-scheme');
      return;
    }
    document.body.setAttribute('data-ead-scheme', scheme);
  }

  window.EAD_setScheme = function(scheme){
    eadSet('ead_scheme', scheme);
    applyScheme(scheme);
  };

  // Initialize from saved preference or server-provided default via inline style
  applyScheme(eadGet('ead_scheme', document.body.getAttribute('data-ead-scheme') || 'system'));

  // Hotkey: Alt+M toggles compact
  $(document).on('keydown', function(e){
    if (e.altKey && e.key.toLowerCase() === 'm'){
      document.body.classList.toggle('ead-compact');
      eadSet('ead_compact_menu', document.body.classList.contains('ead-compact') ? '1' : '0');
    }
  });
})(jQuery);
