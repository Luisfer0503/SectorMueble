@extends('layouts.app')

@section('titulo', 'Finalizar Compra | Sector Mueble')

@section('contenido')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-16">
        <h1 class="serif-title text-2xl sm:text-3xl font-bold text-zinc-950 mb-6 sm:mb-8 font-sans">Finalizar Compra</h1>

        <form action="{{ route('checkout.procesar') }}" method="POST">
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
                                <input type="tel" name="telefono_cliente" id="telefono_cliente" required value="{{ old('telefono_cliente') }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
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
                                <input type="text" name="codigo_postal" id="codigo_postal" required value="{{ old('codigo_postal', auth()->user()->codigo_postal ?? session('codigo_postal', '')) }}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl text-base sm:text-sm px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-amber-700">
                                @error('codigo_postal')
                                    <span class="text-xs text-rose-600 font-medium mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Datos de Pago (Stripe / Pasarela Segura) -->
                    <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <span class="bg-amber-800 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center text-xs">3</span>
                                <h2 class="text-sm sm:text-base font-bold text-zinc-950 uppercase tracking-wider">Método de Pago Seguro</h2>
                            </div>
                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Encriptación SSL 256-bit</span>
                            </span>
                        </div>

                        <!-- Selector de Tarjeta / Stripe -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-amber-50/80 via-white to-amber-50/40 border border-amber-200/90 rounded-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2.5 bg-amber-900 text-white rounded-xl shadow-sm">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-extrabold text-amber-950">Tarjeta de Crédito / Débito (Stripe)</h3>
                                        <p class="text-xs text-zinc-600 mt-0.5">Procesamiento seguro directo en Stripe Sandbox o Producción</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1.5 opacity-80">
                                    <span class="text-[10px] font-bold bg-zinc-100 text-zinc-700 px-2 py-1 rounded">VISA</span>
                                    <span class="text-[10px] font-bold bg-zinc-100 text-zinc-700 px-2 py-1 rounded">MC</span>
                                    <span class="text-[10px] font-bold bg-zinc-100 text-zinc-700 px-2 py-1 rounded">AMEX</span>
                                </div>
                            </div>

                            <!-- Explicación Clara del Proceso de Pago en Stripe -->
                            <div class="mt-4 pt-3 border-t border-amber-200/60 text-xs text-amber-900 space-y-2">
                                <div class="flex items-start space-x-2">
                                    <span class="text-base leading-none">🔐</span>
                                    <div>
                                        <strong class="text-amber-950 block">¿Cómo ingresar tu tarjeta?</strong>
                                        <p class="mt-0.5 text-zinc-600 leading-relaxed">
                                            Al hacer clic en el botón <strong>"Pagar $ {{ number_format($total, 2, '.', ',') }} MXN"</strong>, serás redirigido en 1 segundo a la pasarela bancaria cifrada de <strong>Stripe Checkout</strong> donde podrás ingresar tu tarjeta de forma 100% protegida.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-2 bg-amber-100/70 p-2.5 rounded-xl border border-amber-200">
                                    <span class="text-base leading-none">💡</span>
                                    <div>
                                        <strong class="text-amber-950">Tarjeta de prueba para este ambiente:</strong>
                                        <p class="text-[11px] text-amber-900 mt-0.5">
                                            Usa la tarjeta <code class="bg-white px-1.5 py-0.5 rounded font-mono font-bold text-amber-950 border border-amber-300">4242 4242 4242 4242</code> con cualquier fecha futura (ej. <code class="bg-white px-1 py-0.5 rounded font-mono">12/28</code>) y CVC <code class="bg-white px-1 py-0.5 rounded font-mono">123</code>.
                                        </p>
                                    </div>
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
                        </div>

                        <div class="flex justify-between items-center py-6 text-zinc-950">
                            <span class="text-sm font-semibold">Total a pagar</span>
                            <span class="text-lg font-bold font-sans">$ {{ number_format($total, 2, '.', ',') }}</span>
                        </div>

                        <button type="button" 
                                id="btn-procesar-stripe"
                                onclick="iniciarPagoStripe()"
                                class="w-full bg-gradient-to-r from-amber-800 to-amber-900 hover:from-amber-700 hover:to-amber-800 text-white text-xs sm:text-sm font-bold uppercase tracking-wider py-4 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center space-x-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span id="btn-stripe-texto">Pagar $ {{ number_format($total, 2, '.', ',') }} MXN</span>
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
        async function iniciarPagoStripe() {
            const form = document.getElementById('checkout-form');
            const btn = document.getElementById('btn-procesar-stripe');
            const btnTexto = document.getElementById('btn-stripe-texto');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            btn.disabled = true;
            btn.classList.add('opacity-75');
            btnTexto.innerText = 'Conectando con pasarela segura...';

            try {
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => data[key] = value);

                const response = await fetch("{{ route('checkout.stripe.session') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const resData = await response.json();

                if (resData.success) {
                    if (resData.checkout_url) {
                        window.location.href = resData.checkout_url;
                    } else if (resData.modo_demo) {
                        form.submit();
                    }
                } else {
                    alert(resData.message || 'Ocurrió un error al preparar la sesión de pago.');
                    btn.disabled = false;
                    btn.classList.remove('opacity-75');
                    btnTexto.innerText = 'Reintentar Pago';
                }
            } catch (err) {
                console.error(err);
                form.submit();
            }
        }
    </script>
@endsection
