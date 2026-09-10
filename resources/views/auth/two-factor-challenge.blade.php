@extends('layouts.app')

@section('content')

{{-- Modal Nativo --}}
<dialog id="ec-auth-dialog">
    <div class="ec-auth-modal ec-shake">
        <div class="ec-auth-header">
            <div class="ec-auth-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h1>VERIFICACIÓN EN DOS PASOS</h1>
            <p>Ingresa el código de 6 dígitos enviado a tu correo.</p>
            <div class="ec-auth-expiration">
                Expira en <span id="countdown">05:00</span>
            </div>
        </div>

        <form method="POST" action="{{ route('2fa.verify') }}" class="ec-auth-form">
            @csrf
            <div class="ec-auth-input-group">
                <input type="text" name="code" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required autofocus autocomplete="one-time-code">
            </div>

            @error('code')
                <div class="ec-auth-alert">
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <button type="submit" class="ec-auth-submit">VERIFICAR CÓDIGO</button>
        </form>

        <div class="ec-auth-footer">
            <button type="button" id="resendButton" class="ec-auth-resend" disabled>
                Reenviar código (<span id="resendTimer">30</span>s)
            </button>
        </div>
    </div>
</dialog>

<style>
/* Reseteo del dialog nativo */
#ec-auth-dialog {
    padding: 0;
    border: none;
    background: transparent;
    overflow: visible;
    margin: auto; 
    outline: none;
}

/* Fondo oscurecido */
#ec-auth-dialog::backdrop {
    background: rgba(11, 15, 25, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    animation: ec-fade-in 250ms ease-out forwards;
}

/* El ventanal con los estilos de EndoCare */
.ec-auth-modal {
    width: 100%;
    min-width: 340px;
    max-width: 380px;
    background: rgba(18, 26, 43, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 20px;
    padding: 28px 24px;
    color: #EAF1FF;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.8),
        0 0 30px rgba(56, 199, 244, 0.15);
    animation: ec-pop-in 250ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes ec-fade-in { from { opacity: 0; } to { opacity: 1; } }
@keyframes ec-pop-in { from { transform: scale(0.92); opacity: 0; } to { transform: scale(1); opacity: 1; } }

/* Elementos internos */
.ec-auth-header { text-align: center; margin-bottom: 20px; }
.ec-auth-badge {
    width: 44px; height: 44px; background: rgba(56, 199, 244, 0.12); color: var(--cyan-400, #38c7f4);
    border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 12px; border: 1px solid rgba(56, 199, 244, 0.25); box-shadow: 0 0 16px rgba(56, 199, 244, 0.2);
}
.ec-auth-header h1 { font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #9FC4FF; margin: 0 0 6px 0; }
.ec-auth-header p { font-size: 12.5px; color: #8FA8D8; margin: 0 0 10px 0; line-height: 1.4; }
.ec-auth-expiration { display: inline-block; font-size: 11.5px; color: #8FA8D8; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); padding: 3px 12px; border-radius: 20px; }
.ec-auth-expiration #countdown { color: var(--cyan-400, #38c7f4); font-family: 'Sora', monospace; font-weight: 700; }

.ec-auth-form { display: flex; flex-direction: column; gap: 14px; }
.ec-auth-input-group input { 
    width: 100%; text-align: center; letter-spacing: 0.4em; font-family: 'Sora', sans-serif; 
    font-size: 24px; font-weight: 700; padding: 10px 12px; border: 1px solid rgba(255, 255, 255, 0.18); 
    border-radius: 12px; background: rgba(11, 15, 25, 0.6); color: #EAF1FF; transition: all 0.2s ease; 
    box-sizing: border-box; outline: none; 
}
.ec-auth-input-group input::placeholder { color: rgba(143, 168, 216, 0.25); letter-spacing: 0.3em; }
.ec-auth-input-group input:focus { border-color: var(--cyan-400, #38c7f4); background: rgba(11, 15, 25, 0.9); box-shadow: 0 0 18px rgba(56, 199, 244, 0.3); }

.ec-auth-alert { background: rgba(255, 90, 110, 0.15); border: 1px solid rgba(255, 90, 110, 0.3); color: #FF5A6E; padding: 8px 12px; border-radius: 8px; font-size: 12px; text-align: center; }

.ec-auth-submit { 
    width: 100%; height: 44px; font-family: 'Sora', sans-serif; font-size: 11.5px; font-weight: 600; 
    letter-spacing: 0.1em; background: linear-gradient(90deg, var(--blue-500, #1e5ae8), var(--cyan-400, #38c7f4)); 
    color: #ffffff; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s ease; 
    box-shadow: 0 6px 16px -4px rgba(30, 90, 232, 0.5); 
}
.ec-auth-submit:hover { opacity: 0.94; transform: translateY(-1px); }
.ec-auth-submit:active { transform: translateY(0); }

.ec-auth-footer { margin-top: 16px; padding-top: 14px; border-top: 1px solid rgba(255, 255, 255, 0.08); text-align: center; }
.ec-auth-resend { background: transparent; border: none; color: #9FC4FF; font-size: 12px; cursor: pointer; padding: 4px 10px; border-radius: 6px; transition: all 0.2s ease; }
.ec-auth-resend:hover:not(:disabled) { background: rgba(255, 255, 255, 0.06); color: #EAF1FF; }
.ec-auth-resend:disabled { color: #64748b; cursor: not-allowed; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const dialog = document.getElementById('ec-auth-dialog');
    if (dialog && typeof dialog.showModal === "function") {
        dialog.showModal();
    }
});

let seconds = 300;
const countdown = document.getElementById('countdown');
const timer = setInterval(() => {
    seconds--;
    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    if(countdown) countdown.textContent = `${m}:${s}`;
    if (seconds <= 0) clearInterval(timer);
}, 1000);

let resendSeconds = 30;
const resendBtn = document.getElementById('resendButton');
const resendTimer = document.getElementById('resendTimer');
const resendInterval = setInterval(() => {
    resendSeconds--;
    if(resendTimer) resendTimer.textContent = resendSeconds;
    if (resendSeconds <= 0) {
        clearInterval(resendInterval);
        if(resendBtn) {
            resendBtn.disabled = false;
            resendBtn.innerHTML = 'Reenviar código';
        }
    }
}, 1000);

if(resendBtn) {
    resendBtn.addEventListener('click', async () => {
        const res = await fetch('{{ route('2fa.resend') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });
        const data = await res.json().catch(() => ({}));
        alert(data.message || 'Código reenviado');
    });
}
</script>
@endsection