@extends('layouts.app')

@section('titulo', 'Finalizar Compra | Sector Mueble')

@section('contenido')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-16">
        <h1 class="serif-title text-2xl sm:text-3xl font-bold text-zinc-950 mb-6 sm:mb-8 font-sans">Finalizar Compra</h1>

        <form id="checkout-form" action="{{ route('checkout.procesar') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-12">
                
                <!-- Formulario de Envío y Pago (Izquierda - Col 7) -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-8">
                    <!-- Paso 1: Datos de Contacto -->
                    <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-6 shadow-sm">
                        <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                            <span class="bg-amber-800 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center text-xs">1</span>
                            <h2 class="text-sm sm:text-base font-bold text-zinc-950 uppercase tracking-wider">Datos de Contacto</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nombre_cliente" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Nombre Completo</label>
                                <input type="text" name="nombre_cliente" id="nombre_cliente" required value="{{ old('nombre_cliente', auth()->user()->name ?? '') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('nombre_cliente')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="correo_cliente" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Correo Electrónico</label>
                                <input type="email" name="correo_cliente" id="correo_cliente" required value="{{ old('correo_cliente', auth()->user()->email ?? '') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('correo_cliente')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="telefono_cliente" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Teléfono móvil</label>
                                <input type="tel" name="telefono_cliente" id="telefono_cliente" required value="{{ old('telefono_cliente', auth()->user()->telefono ?? '') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('telefono_cliente')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Dirección de Envío -->
                    <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-6 shadow-sm">
                        <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                            <span class="bg-amber-800 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center text-xs">2</span>
                            <h2 class="text-sm sm:text-base font-bold text-zinc-950 uppercase tracking-wider">Dirección de Envío</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-3">
                                <label for="direccion_envio" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Calle, número, piso y puerta</label>
                                <input type="text" name="direccion_envio" id="direccion_envio" required value="{{ old('direccion_envio') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('direccion_envio')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="ciudad" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Ciudad / Localidad</label>
                                <input type="text" name="ciudad" id="ciudad" required value="{{ old('ciudad', session('cobertura_info.municipio', '')) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('ciudad')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="codigo_postal" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Código Postal</label>
                                <input type="text" name="codigo_postal" id="codigo_postal" required maxlength="5" value="{{ old('codigo_postal', session('codigo_postal', '')) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700 font-mono font-bold">
                                <span id="cp-cobertura-status" class="text-xs font-semibold mt-1.5 hidden"></span>
                                @error('codigo_postal')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="sm:col-span-3">
                                <label for="referencias" class="block text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-1">Referencias de entrega <span class="text-zinc-400 font-normal">(Opcional)</span></label>
                                <input type="text" name="referencias" id="referencias" value="{{ old('referencias') }}" placeholder="Ej. Entre calle Olivos y Sauces, fachada blanca con portón de madera" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                            </div>

                            <!-- Mensaje Informativo de Envío a ancho completo debajo de todo el bloque de dirección -->
                            <div class="sm:col-span-3 mt-1 p-4 bg-[#1E2440] text-white border border-white/20 rounded-2xl text-xs flex items-start space-x-3 shadow-xs">
                                <div class="p-1.5 bg-white/20 text-white rounded-xl shrink-0 mt-0.5 shadow-2xs">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="leading-relaxed font-medium">¡Queremos ayudarte a coordinar tu envío de la mejor manera! Para poder dar seguimiento a tu solicitud, haz clic en "Contactar agente de ventas" y nos comunicaremos contigo muy pronto.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Método de Pago (Stripe) -->
                    <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-6 shadow-sm">
                        <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                            <span class="bg-amber-800 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center text-xs">3</span>
                            <h2 class="text-sm sm:text-base font-bold text-zinc-950 uppercase tracking-wider">Método de Pago Seguro</h2>
                        </div>

                        <div class="bg-gradient-to-r from-amber-500/10 via-amber-100/40 to-amber-50/20 p-4 sm:p-5 rounded-2xl border border-amber-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-amber-800 text-white rounded-xl shadow-sm">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-extrabold text-amber-950">Tarjeta de Crédito / Débito (Stripe)</h3>
                                        <p class="text-xs text-zinc-600 mt-0.5">Procesamiento seguro directo en Stripe</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1.5 opacity-80">
                                    <span class="text-[10px] font-bold bg-zinc-100 text-zinc-700 px-2 py-1 rounded">VISA</span>
                                    <span class="text-[10px] font-bold bg-zinc-100 text-zinc-700 px-2 py-1 rounded">MC</span>
                                    <span class="text-[10px] font-bold bg-zinc-100 text-zinc-700 px-2 py-1 rounded">AMEX</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Resumen de Compra (Derecha - Col 5) -->
                <div class="lg:col-span-5">
                    <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-6 shadow-sm sticky top-24">
                        <h2 class="serif-title text-lg font-bold text-zinc-950 pb-4 border-b border-zinc-150">Resumen del Pedido</h2>
                        
                        <!-- Listado de Artículos -->
                        <div class="divide-y divide-zinc-100 max-h-60 overflow-y-auto py-2">
                            @foreach($carrito as $id => $item)
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-zinc-100 rounded overflow-hidden flex-shrink-0">
                                            <img src="{{ $item['imagen_url'] }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="text-xs font-bold text-zinc-900 line-clamp-1">{{ $item['nombre'] }}</h3>
                                            <span class="text-[11px] text-zinc-500">Cant: {{ $item['cantidad'] }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-zinc-900 font-sans">$ {{ number_format($item['precio'] * $item['cantidad'], 2, '.', ',') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totales -->
                        <div class="space-y-3 py-4 border-t border-b border-zinc-150 text-xs mt-4">
                            <div class="flex justify-between text-zinc-500">
                                <span>Subtotal</span>
                                <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($subtotal, 2, '.', ',') }}</span>
                            </div>
                            @if($descuento > 0)
                                <div class="flex justify-between text-rose-700 font-medium">
                                    <span>Descuento (Cupón: {{ $cuponAplicado['codigo'] ?? '' }})</span>
                                    <span class="font-sans">-$ {{ number_format($descuento, 2, '.', ',') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-zinc-500">
                                <span>Envío</span>
                                @if($envio == 0)
                                    <span class="font-bold text-emerald-700 uppercase">Gratis</span>
                                @else
                                    <span class="font-semibold text-zinc-900 font-sans">$ {{ number_format($envio, 2, '.', ',') }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-zinc-500 mt-1 italic font-normal">* Aplican términos y condiciones, no acumulable con otras promociones.</p>
                        </div>

                        <div class="flex justify-between items-center py-6 text-zinc-950">
                            <span class="text-sm font-semibold">Total a pagar</span>
                            <span class="text-lg font-bold font-sans">$ {{ number_format($total, 2, '.', ',') }}</span>
                        </div>

                        <!-- Términos y Condiciones Checkbox -->
                        <div class="mb-4 pt-2 border-t border-zinc-200">
                            <label class="flex items-start space-x-3 cursor-pointer select-none">
                                <input type="checkbox" id="aceptar_terminos" name="aceptar_terminos" required class="mt-1 h-4 w-4 text-[#88674B] border-zinc-300 rounded focus:ring-[#88674B]">
                                <span class="text-xs text-zinc-700 font-medium leading-tight">
                                    He leído y acepto los <button type="button" onclick="abrirModalTerminos(event)" class="text-[#88674B] font-bold underline hover:text-amber-950 focus:outline-none">Términos y Condiciones</button> de Sector Mueble para efectuar mi compra. *
                                </span>
                            </label>
                            <p id="error-terminos" class="hidden text-xs text-rose-600 font-semibold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Debes aceptar los Términos y Condiciones para continuar con el pago.
                            </p>
                        </div>

                        <button type="button" 
                                id="btn-procesar-stripe"
                                onclick="iniciarPagoStripe(event)"
                                style="background-color: #88674B; color: #ffffff;"
                                class="w-full text-xs sm:text-sm font-bold uppercase tracking-wider py-4 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center space-x-2 cursor-pointer border border-white/20">
                            <svg id="btn-stripe-icon" class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span id="btn-stripe-texto" class="font-bold">Pagar $ {{ number_format($total, 2, '.', ',') }} MXN</span>
                        </button>

                        <!-- Alerta Informativa / Bloqueo por CP -->
                        <div id="cp-bloqueo-alerta" class="mt-4 p-4 bg-[#1E2440] border-2 border-white/20 rounded-2xl text-xs text-white leading-relaxed shadow-sm">
                            <div class="flex items-start space-x-3">
                                <div class="p-1.5 bg-white/20 text-white rounded-full shrink-0 mt-0.5 shadow-sm">
                                    <svg class="w-5 h-5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong class="font-extrabold text-sm block text-white mb-1">¡Queremos ayudarte a coordinar tu envío de la mejor manera!</strong>
                                    <span id="cp-bloqueo-mensaje" class="text-xs text-white/95 leading-relaxed block font-medium">Para poder dar seguimiento a tu solicitud, haz clic en "Contactar agente de ventas" y nos comunicaremos contigo muy pronto.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Contactar Agente de Ventas (Verde oficial WhatsApp) -->
                        <button type="button" 
                                id="btn-contactar-agente"
                                onclick="contactarAgenteVentas()"
                                class="w-full bg-[#25D366] hover:bg-[#20bd5a] active:scale-[0.99] text-white text-xs sm:text-sm font-bold uppercase tracking-wider py-4 px-4 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center space-x-2.5 mt-3 cursor-pointer border border-white/20">
                            <svg class="w-5 h-5 fill-current text-white" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>Contactar agente de ventas</span>
                        </button>
                        
                        <a href="{{ route('carrito') }}" class="w-full block text-center border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-xs font-bold uppercase tracking-wider py-3 mt-3 rounded-xl transition-colors">
                            Volver al Carrito
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        const carritoItemsCheckout = @json(array_values($carrito));
        const totalCarritoCheckout = "{{ number_format($total, 2, '.', ',') }}";

        let cpValidadoEnBD = false;

        document.addEventListener('DOMContentLoaded', function () {
            const inputCP = document.getElementById('codigo_postal');
            if (inputCP) {
                inputCP.addEventListener('input', function () {
                    const cpVal = this.value.trim();
                    if (cpVal.length === 5 && /^[0-9]{5}$/.test(cpVal)) {
                        verificarCPCheckout(cpVal);
                    } else {
                        bloquearPagoCP('Ingresa un Código Postal válido de 5 dígitos.');
                    }
                });

                // Si ya tiene un CP cargado al iniciar la página
                if (inputCP.value.trim().length === 5) {
                    verificarCPCheckout(inputCP.value.trim());
                } else {
                    bloquearPagoCP('Ingresa un Código Postal para verificar la cobertura de envío.');
                }
            }
        });

        async function verificarCPCheckout(cp) {
            const statusElem = document.getElementById('cp-cobertura-status');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch("{{ route('cp.verificar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ codigo_postal: cp })
                });

                const data = await response.json();

                if (data.success && data.tiene_cobertura) {
                    cpValidadoEnBD = true;
                    desbloquearPagoCP();

                    if (statusElem) {
                        statusElem.className = 'text-xs font-semibold mt-1.5 text-emerald-700 block';
                        statusElem.innerText = `✓ Cobertura confirmada para ${data.data.municipio}, ${data.data.estado}.`;
                    }

                    const ciudadInput = document.getElementById('ciudad');
                    if (ciudadInput && !ciudadInput.value) {
                        ciudadInput.value = data.data.municipio;
                    }
                } else {
                    cpValidadoEnBD = false;
                    bloquearPagoCP();

                    if (statusElem) {
                        statusElem.className = 'hidden';
                        statusElem.innerText = '';
                    }
                }
            } catch (err) {
                console.error(err);
                bloquearPagoCP('No se pudo verificar el Código Postal. Inténtalo de nuevo.');
            }
        }

        function bloquearPagoCP(mensaje) {
            const btnPagar = document.getElementById('btn-procesar-stripe');
            const alertaBox = document.getElementById('cp-bloqueo-alerta');
            const mensajeElem = document.getElementById('cp-bloqueo-mensaje');
            const btnTexto = document.getElementById('btn-stripe-texto');

            if (btnPagar) {
                btnPagar.disabled = true;
                btnPagar.style.backgroundColor = '#e4e4e7';
                btnPagar.style.color = '#52525b';
                btnPagar.style.border = '2px dashed #d4d4d8';
                btnPagar.style.cursor = 'not-allowed';
                btnPagar.style.opacity = '1';
                btnPagar.style.boxShadow = 'none';
            }

            if (btnTexto) {
                btnTexto.innerText = `🔒 Pagar $ ${totalCarritoCheckout} MXN (No disponible)`;
            }

            if (alertaBox) {
                alertaBox.classList.remove('hidden');
            }

            if (mensajeElem) {
                mensajeElem.innerText = 'Para poder dar seguimiento a tu solicitud, haz clic en "Contactar agente de ventas" y nos comunicaremos contigo muy pronto.';
            }
        }

        function desbloquearPagoCP() {
            const btnPagar = document.getElementById('btn-procesar-stripe');
            const alertaBox = document.getElementById('cp-bloqueo-alerta');
            const btnTexto = document.getElementById('btn-stripe-texto');

            if (btnPagar) {
                btnPagar.disabled = false;
                btnPagar.style.backgroundColor = '#78350f';
                btnPagar.style.color = '#ffffff';
                btnPagar.style.border = 'none';
                btnPagar.style.cursor = 'pointer';
                btnPagar.style.opacity = '1';
                btnPagar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
            }

            if (btnTexto) {
                btnTexto.innerText = `Pagar $ ${totalCarritoCheckout} MXN`;
            }

            if (alertaBox) {
                alertaBox.classList.add('hidden');
            }
        }

        async function contactarAgenteVentas() {
            const cpInput = document.getElementById('codigo_postal')?.value || '';
            const nombreInput = document.getElementById('nombre_cliente')?.value || '';
            const telInput = document.getElementById('telefono_cliente');
            const btnAgente = document.getElementById('btn-contactar-agente');

            const telefonoVal = telInput ? telInput.value.trim() : '';

            if (!telefonoVal || telefonoVal.length < 10) {
                alert('Por favor ingresa tu número de teléfono móvil en los Datos de Contacto (Paso 1) para que nuestro agente pueda contactarte por WhatsApp.');
                if (telInput) {
                    telInput.focus();
                    telInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    telInput.classList.add('ring-2', 'ring-rose-500');
                    setTimeout(() => telInput.classList.remove('ring-2', 'ring-rose-500'), 3000);
                }
                return;
            }

            if (btnAgente) {
                btnAgente.disabled = true;
                btnAgente.classList.add('opacity-75', 'cursor-wait');
                btnAgente.innerText = '⏳ Enviando solicitud a ventas...';
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch("{{ route('carrito.contactar_agente') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nombre_cliente: nombreInput,
                        telefono_cliente: telefonoVal,
                        codigo_postal: cpInput
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('¡Solicitud enviada con éxito! En breve nuestro agente de ventas te contactará a tu número de WhatsApp (' + telefonoVal + ').');
                } else {
                    alert(data.message || 'Ocurrió un error al enviar la solicitud. Por favor inténtalo nuevamente.');
                }
            } catch (err) {
                console.error(err);
                alert('En breve nuestro agente de ventas te contactará a tu número de WhatsApp (' + telefonoVal + ').');
            } finally {
                if (btnAgente) {
                    btnAgente.disabled = false;
                    btnAgente.classList.remove('opacity-75', 'cursor-wait');
                    btnAgente.innerHTML = `<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg><span>Contactar agente de ventas</span>`;
                }
            }
        }

        async function iniciarPagoStripe(e) {
            if (e) e.preventDefault();

            if (!cpValidadoEnBD) {
                alert('El Código Postal ingresado no cuenta con cobertura de envío directa para pago en línea. Por favor contacta a un agente de ventas.');
                return;
            }

            const checkTerminos = document.getElementById('aceptar_terminos');
            const errorTerminos = document.getElementById('error-terminos');

            if (checkTerminos && !checkTerminos.checked) {
                if (errorTerminos) errorTerminos.classList.remove('hidden');
                checkTerminos.focus();
                checkTerminos.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            } else if (errorTerminos) {
                errorTerminos.classList.add('hidden');
            }

            const form = document.getElementById('checkout-form') || document.querySelector('form');
            const btn = document.getElementById('btn-procesar-stripe');
            const btnTexto = document.getElementById('btn-stripe-texto');

            if (!form) return;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75');
            }
            if (btnTexto) {
                btnTexto.innerText = 'Conectando con pasarela segura...';
            }

            try {
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => data[key] = value);

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const response = await fetch("{{ route('checkout.stripe.session') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const resData = await response.json();

                if (resData.success && resData.checkout_url) {
                    window.location.href = resData.checkout_url;
                } else {
                    alert(resData.message || 'Ocurrió un error al preparar la sesión de pago de Stripe.');
                    if (btn) btn.disabled = false;
                    if (btnTexto) btnTexto.innerText = 'Reintentar Pago con Stripe';
                }
            } catch (err) {
                console.error(err);
                alert("Error al conectar con la pasarela de pagos: " + (err.message || 'Inténtalo de nuevo.'));
                if (btn) btn.disabled = false;
                if (btnTexto) btnTexto.innerText = 'Reintentar Pago con Stripe';
            }
        }

        function abrirModalTerminos(e) {
            if (e) e.preventDefault();
            const modal = document.getElementById('modal-terminos');
            if (modal) modal.classList.remove('hidden');
        }

        function cerrarModalTerminos() {
            const modal = document.getElementById('modal-terminos');
            if (modal) modal.classList.add('hidden');
        }

        function aceptarTerminosDesdeModal() {
            const checkTerminos = document.getElementById('aceptar_terminos');
            const errorTerminos = document.getElementById('error-terminos');
            if (checkTerminos) {
                checkTerminos.checked = true;
            }
            if (errorTerminos) {
                errorTerminos.classList.add('hidden');
            }
            cerrarModalTerminos();
        }
    </script>

    <!-- Modal Términos y Condiciones -->
    <div id="modal-terminos" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white w-full max-w-3xl max-h-[85vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-zinc-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between bg-zinc-50">
                <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#88674B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Términos y Condiciones - Sector Mueble
                </h3>
                <button type="button" onclick="cerrarModalTerminos()" class="text-zinc-400 hover:text-zinc-700 p-1 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto text-xs sm:text-sm text-zinc-700 space-y-4 leading-relaxed font-sans">
                {!! nl2br(e(\App\Models\TerminoCondicion::obtenerContenido())) !!}
            </div>
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-zinc-100 bg-zinc-50 flex justify-end gap-3">
                <button type="button" onclick="cerrarModalTerminos()" class="px-5 py-2.5 bg-zinc-200 hover:bg-zinc-300 text-zinc-800 text-xs font-bold uppercase rounded-xl transition-all">
                    Cerrar
                </button>
                <button type="button" onclick="aceptarTerminosDesdeModal()" style="background-color: #88674B;" class="px-5 py-2.5 text-white text-xs font-bold uppercase rounded-xl transition-all shadow hover:brightness-110">
                    Aceptar Términos
                </button>
            </div>
        </div>
    </div>
@endsection
