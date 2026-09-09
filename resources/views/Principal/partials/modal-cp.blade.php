<!-- Modal de Verificación de Código Postal y Cobertura de Envío (Isolated Stacking Context) -->
<div id="sm-cp-modal" 
     style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 99999999; isolation: isolate;" 
     class="hidden flex items-center justify-center p-4 sm:p-6 overflow-y-auto" 
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="sm-cp-modal-title">

    <!-- Overlay Oscuro Sólido Opaco (Sin transparencias traslúcidas) -->
    <div id="sm-cp-overlay" 
         onclick="cerrarModalCP()" 
         style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; background-color: rgba(8, 8, 8, 0.94); z-index: 1; opacity: 0; transition: opacity 0.3s ease;"></div>

    <!-- Tarjeta del Modal con Color de Fondo Azul Media Noche #1E2440 -->
    <div id="sm-cp-card" 
         style="position: relative; z-index: 10; background-color: #1E2440; border: 1px solid rgba(255, 255, 255, 0.25); color: #ffffff; opacity: 0; transform: scale(0.95); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);" 
         class="w-full max-w-md rounded-3xl shadow-2xl p-6 sm:p-8 max-h-[92vh] overflow-y-auto my-auto ring-1 ring-black/20">
        
        <!-- Botón para cerrar -->
        <button type="button" 
                onclick="cerrarModalCP()" 
                style="background-color: rgba(255, 255, 255, 0.2);" 
                class="absolute top-4 right-4 text-white hover:bg-white/30 p-2 rounded-full transition-colors focus:outline-none shadow-md z-30" 
                aria-label="Cerrar modal">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Header del Modal -->
        <div class="text-center mb-6 relative z-10">
            <div style="background-color: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.4);" class="inline-flex items-center justify-center w-14 h-14 text-white rounded-2xl mb-3 shadow-sm">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 id="sm-cp-modal-title" class="text-xl sm:text-2xl font-extrabold text-white font-heading">
                Verifica Cobertura de Envío
            </h3>
            <p class="text-xs sm:text-sm text-white/90 mt-2 leading-relaxed font-medium">
                Ingresa tu <strong>Código Postal</strong> para verificar si tenemos envío a tu zona e informarte sobre los tiempos y cobertura en tu domicilio.
            </p>
        </div>

        <!-- Formulario de Entrada de CP -->
        <form id="sm-cp-form" onsubmit="procesarVerificacionCP(event)" class="space-y-4 relative z-10">
            <div>
                <label for="sm-input-cp" class="block text-xs font-bold uppercase tracking-wider text-white mb-1.5 text-center">
                    Código Postal (5 dígitos)
                </label>
                <div class="relative">
                    <input type="text" 
                           id="sm-input-cp" 
                           name="codigo_postal" 
                           maxlength="5" 
                           inputmode="numeric" 
                           pattern="[0-9]{5}" 
                           placeholder="Ej. 72760" 
                           required 
                           style="background-color: #FFFFFF; color: #09090b; border: 1px solid #d4d4d8;" 
                           class="w-full text-xl font-bold text-center tracking-widest px-4 py-3 rounded-2xl focus:outline-none focus:ring-2 focus:ring-amber-700 focus:border-amber-700 transition-all shadow-inner">
                    <div id="sm-cp-spinner" class="hidden absolute right-4 top-3.5">
                        <svg class="animate-spin h-6 w-6 text-amber-800" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <p id="sm-cp-error" class="hidden text-xs text-rose-200 mt-1.5 font-semibold text-center bg-rose-900/60 p-1.5 rounded-lg border border-rose-400/40"></p>
            </div>

            <button type="submit" 
                    id="sm-cp-btn-submit"
                    style="background-color: #88674B;" 
                    class="w-full hover:bg-[#74563C] active:scale-[0.99] text-white font-bold text-sm py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 border border-white/20">
                <span>Verificar Cobertura</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Resultados de la Consulta -->
        <div id="sm-cp-resultado" class="hidden space-y-4 mt-2 relative z-10">
            <!-- Alerta Cobertura Disponible -->
            <div id="sm-cp-exito-box" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;" class="hidden p-4 rounded-2xl shadow-sm text-left">
                <div class="flex items-start space-x-3">
                    <div class="p-1.5 bg-emerald-600 text-white rounded-full shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-emerald-950">¡Excelente! Sí contamos con envío</h4>
                        <p id="sm-cp-exito-mensaje" class="text-xs text-emerald-900 mt-1 font-medium leading-snug"></p>
                        <div class="mt-2.5 pt-2.5 border-t border-emerald-200 text-[11px] text-emerald-950">
                            <strong>Zonas y colonias incluidas:</strong>
                            <p id="sm-cp-exito-zonas" class="text-emerald-800 italic mt-0.5 line-clamp-4"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerta Sin Cobertura Directa -->
            <div id="sm-cp-no-cobertura-box" style="background-color: #fffbeb; border: 2px solid #f59e0b;" class="hidden p-4.5 rounded-2xl shadow-md text-left">
                <div class="flex items-start space-x-3">
                    <div class="p-2 bg-amber-600 text-white rounded-full shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-amber-950">¡Queremos ayudarte a coordinar tu envío de la mejor manera!</h4>
                        <p id="sm-cp-no-cobertura-mensaje" class="text-xs text-amber-900 mt-1.5 font-medium leading-relaxed">
                            Para poder dar seguimiento a tu solicitud, haz clic en "Contactar agente de ventas" y nos comunicaremos contigo muy pronto.
                        </p>
                        <a href="{{ route('checkout') }}" 
                           style="background-color: #25D366;" 
                           class="mt-3.5 inline-flex items-center justify-center space-x-2 w-full hover:bg-[#20bd5a] active:scale-[0.99] text-white text-xs font-bold uppercase tracking-wider py-3 px-4 rounded-xl transition-all shadow-md hover:shadow-lg border border-white/20">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>Contactar agente de ventas</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Botones de Confirmación / Cambio -->
            <div class="flex flex-col sm:flex-row items-center gap-2 pt-2">
                <button type="button" 
                        onclick="confirmarYGuardarCP()" 
                        style="background-color: #88674B;" 
                        class="w-full hover:bg-[#74563C] text-white font-bold text-xs sm:text-sm py-3 px-4 rounded-2xl shadow transition-all text-center border border-white/20">
                    Confirmar mi Código Postal
                </button>
                <button type="button" 
                        onclick="resetearFormularioCP()" 
                        style="background-color: rgba(255, 255, 255, 0.25); color: #ffffff;" 
                        class="w-full hover:bg-white/30 font-bold text-xs py-3 px-4 rounded-2xl transition-colors text-center border border-white/20">
                    Probar otro CP
                </button>
            </div>
        </div>
        
    </div>
</div>

<script>
    let smCPActual = '';
    let smCoberturaActual = null;

    document.addEventListener('DOMContentLoaded', function () {
        const cpGuardado = localStorage.getItem('sm_codigo_postal');
        const cpSession = "{{ session('codigo_postal', '') }}";

        if (cpGuardado) {
            smCPActual = cpGuardado;
            const municipioGuardado = localStorage.getItem('sm_cp_municipio') || '';
            actualizarHeaderCP(smCPActual, municipioGuardado);
        } else if (cpSession) {
            smCPActual = cpSession;
            localStorage.setItem('sm_codigo_postal', cpSession);
            actualizarHeaderCP(smCPActual, '');
        }
    });

    function abrirModalCP(esPrimeraVez = false) {
        const modal = document.getElementById('sm-cp-modal');
        const overlay = document.getElementById('sm-cp-overlay');
        const card = document.getElementById('sm-cp-card');
        const input = document.getElementById('sm-input-cp');

        if (smCPActual && !esPrimeraVez) {
            input.value = smCPActual;
        }

        document.body.style.overflow = 'hidden';
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            overlay.style.opacity = '1';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
            if (input) input.focus();
        }, 30);
    }

    function cerrarModalCP() {
        const modal = document.getElementById('sm-cp-modal');
        const overlay = document.getElementById('sm-cp-overlay');
        const card = document.getElementById('sm-cp-card');

        overlay.style.opacity = '0';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    async function procesarVerificacionCP(e) {
        e.preventDefault();
        const input = document.getElementById('sm-input-cp');
        const errorElem = document.getElementById('sm-cp-error');
        const spinner = document.getElementById('sm-cp-spinner');
        const btnSubmit = document.getElementById('sm-cp-btn-submit');
        const form = document.getElementById('sm-cp-form');
        const resultadoContainer = document.getElementById('sm-cp-resultado');
        const exitoBox = document.getElementById('sm-cp-exito-box');
        const noCoberturaBox = document.getElementById('sm-cp-no-cobertura-box');

        const cp = input.value.trim();

        if (!/^[0-9]{5}$/.test(cp)) {
            errorElem.innerText = 'Ingresa un código postal válido de 5 números.';
            errorElem.classList.remove('hidden');
            return;
        }

        errorElem.classList.add('hidden');
        spinner.classList.remove('hidden');
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-70');

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch("{{ route('cp.verificar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ codigo_postal: cp })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                errorElem.innerText = data.message || 'Ocurrió un error al verificar el CP.';
                errorElem.classList.remove('hidden');
                return;
            }

            smCPActual = cp;
            smCoberturaActual = data.data;

            form.classList.add('hidden');
            resultadoContainer.classList.remove('hidden');

            if (data.tiene_cobertura) {
                noCoberturaBox.classList.add('hidden');
                exitoBox.classList.remove('hidden');
                document.getElementById('sm-cp-exito-mensaje').innerText = data.data.mensaje;
                document.getElementById('sm-cp-exito-zonas').innerText = data.data.zona_cobertura;
            } else {
                exitoBox.classList.add('hidden');
                noCoberturaBox.classList.remove('hidden');
                document.getElementById('sm-cp-no-cobertura-mensaje').innerText = 'Para poder dar seguimiento a tu solicitud, haz clic en "Contactar agente de ventas" y nos comunicaremos contigo muy pronto.';
            }

            localStorage.setItem('sm_codigo_postal', cp);

        } catch (err) {
            console.error(err);
            errorElem.innerText = 'Error de conexión. Inténtalo de nuevo.';
            errorElem.classList.remove('hidden');
        } finally {
            spinner.classList.add('hidden');
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-70');
        }
    }

    function confirmarYGuardarCP() {
        if (smCPActual) {
            localStorage.setItem('sm_codigo_postal', smCPActual);
            const municipio = smCoberturaActual && smCoberturaActual.municipio ? smCoberturaActual.municipio : '';
            if (municipio) {
                localStorage.setItem('sm_cp_municipio', municipio);
            }
            actualizarHeaderCP(smCPActual, municipio);
        }
        cerrarModalCP();
    }

    function resetearFormularioCP() {
        document.getElementById('sm-cp-form').classList.remove('hidden');
        document.getElementById('sm-cp-resultado').classList.add('hidden');
        document.getElementById('sm-input-cp').value = '';
        document.getElementById('sm-input-cp').focus();
    }

    function actualizarHeaderCP(cp, municipio) {
        const headerBtns = document.querySelectorAll('.cp-header-text-span');
        headerBtns.forEach(el => {
            if (municipio) {
                el.innerHTML = `CP: <strong>${cp}</strong> (${municipio})`;
            } else {
                el.innerHTML = `CP: <strong>${cp}</strong>`;
            }
        });
    }
</script>
