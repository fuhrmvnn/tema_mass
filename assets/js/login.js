function lmLogin() {
    var usuario = document.getElementById('lm_usuario').value.trim();
    var pass    = document.getElementById('lm_pass').value;
    var errBox  = document.getElementById('lm-error');
    var btn     = document.getElementById('lm-btn');
    var btnTxt  = document.getElementById('lm-btn-text');
    var loader  = document.getElementById('lm-btn-loader');
 
    // Limpiar error
    errBox.style.display = 'none';
    errBox.textContent   = '';
 
    // Validación
    if (!usuario) { lmError('Por favor ingresa tu nombre de usuario.'); return; }
    if (!pass)    { lmError('Por favor ingresa tu contraseña.');         return; }
 
    // Loading
    btn.disabled         = true;
    btnTxt.style.display = 'none';
    loader.style.display = 'inline';
 
    var data = new FormData();
    data.append('action', 'lm_do_login');
    data.append('log',    usuario);
    data.append('pwd',    pass);
    data.append('nonce',  LM.nonce);
 
    fetch(LM.ajaxUrl, { method: 'POST', body: data })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                window.location.href = res.data.redirect;
            } else {
                lmError(res.data.message || 'Usuario o contraseña incorrectos.');
                lmReset();
            }
        })
        .catch(function() {
            lmError('Error de conexión. Intenta nuevamente.');
            lmReset();
        });
}
 
function lmError(msg) {
    var errBox = document.getElementById('lm-error');
    errBox.textContent   = msg;
    errBox.style.display = 'block';
}
 
function lmReset() {
    var btn    = document.getElementById('lm-btn');
    var btnTxt = document.getElementById('lm-btn-text');
    var loader = document.getElementById('lm-btn-loader');
    btn.disabled         = false;
    btnTxt.style.display = 'inline';
    loader.style.display = 'none';
}
 
// Enter para enviar
document.addEventListener('DOMContentLoaded', function() {
    ['lm_usuario', 'lm_pass'].forEach(function(id) {
        document.getElementById(id).addEventListener('keydown', function(e) {
            if (e.key === 'Enter') lmLogin();
        });
    });
});
 