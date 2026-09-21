{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
  <channel>
    <title><![CDATA[{{ config('app.name', 'Sector Mueble') }} - Catálogo de Productos]]></title>
    <link>{{ url('/') }}</link>
    <description><![CDATA[Catálogo dinámico de muebles y productos de {{ config('app.name', 'Sector Mueble') }} para Meta Facebook Catalog, Google Merchant Center y Redes Sociales.]]></description>

    @foreach($productos as $producto)
      @php
        $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags($producto->descripcion ?? '')));
        $cleanDesc = str_replace(']]>', ']]&gt;', $cleanDesc);
        $cleanNombre = str_replace(']]>', ']]&gt;', $producto->nombre ?? '');
        $brand = str_replace(']]>', ']]&gt;', $producto->proveedor ?: 'Sector Mueble');
        $category = str_replace(']]>', ']]&gt;', $producto->categoria ?: '');
        $productUrl = route('productos.detalle', $producto->id);
      @endphp

      @if($producto->detalles->isNotEmpty())
        @foreach($producto->detalles as $detalle)
          @php
            $varNombre = str_replace(']]>', ']]&gt;', $detalle->nombre ?? '');
            $fullTitle = $cleanNombre . ($varNombre ? ' - ' . $varNombre : '');
            $imgUrl = $detalle->imagen_url ?: $producto->imagen_url;
            $stock = (int) $detalle->stock;
            $precio = (float) ($detalle->precio ?? $producto->precio);
            $hasDiscount = $detalle->tieneDescuento() || $producto->tieneDescuento();
            $precioEfectivo = $detalle->tieneDescuento() ? $detalle->precioEfectivo() : ($producto->tieneDescuento() ? $producto->precioEfectivo() : $precio);
          @endphp
          <item>
            <g:id>prod_{{ $producto->id }}_var_{{ $detalle->id }}</g:id>
            <g:item_group_id>prod_{{ $producto->id }}</g:item_group_id>
            <g:title><![CDATA[{{ $fullTitle }}]]></g:title>
            <g:description><![CDATA[{{ $cleanDesc ?: $fullTitle }}]]></g:description>
            <g:link>{{ $productUrl }}</g:link>
            <g:image_link>{{ $imgUrl }}</g:image_link>
            @if($producto->imagen_secundaria_url)
              <g:additional_image_link>{{ $producto->imagen_secundaria_url }}</g:additional_image_link>
            @endif
            <g:brand><![CDATA[{{ $brand }}]]></g:brand>
            <g:condition>new</g:condition>
            <g:availability>{{ $stock > 0 ? 'in stock' : 'out of stock' }}</g:availability>
            <g:price>{{ number_format($precio, 2, '.', '') }} MXN</g:price>
            @if($hasDiscount && $precioEfectivo < $precio)
              <g:sale_price>{{ number_format($precioEfectivo, 2, '.', '') }} MXN</g:sale_price>
            @endif
            @if($category)
              <g:product_type><![CDATA[{{ $category }}]]></g:product_type>
            @endif
            <g:google_product_category>Furniture</g:google_product_category>
          </item>
        @endforeach
      @else
        @php
          $stock = (int) $producto->stock;
          $precio = (float) $producto->precio;
          $hasDiscount = $producto->tieneDescuento();
          $precioEfectivo = (float) $producto->precioEfectivo();
        @endphp
        <item>
          <g:id>prod_{{ $producto->id }}</g:id>
          <g:title><![CDATA[{{ $cleanNombre }}]]></g:title>
          <g:description><![CDATA[{{ $cleanDesc ?: $cleanNombre }}]]></g:description>
          <g:link>{{ $productUrl }}</g:link>
          <g:image_link>{{ $producto->imagen_url }}</g:image_link>
          @if($producto->imagen_secundaria_url)
            <g:additional_image_link>{{ $producto->imagen_secundaria_url }}</g:additional_image_link>
          @endif
          <g:brand><![CDATA[{{ $brand }}]]></g:brand>
          <g:condition>new</g:condition>
          <g:availability>{{ $stock > 0 ? 'in stock' : 'out of stock' }}</g:availability>
          <g:price>{{ number_format($precio, 2, '.', '') }} MXN</g:price>
          @if($hasDiscount && $precioEfectivo < $precio)
            <g:sale_price>{{ number_format($precioEfectivo, 2, '.', '') }} MXN</g:sale_price>
          @endif
          @if($category)
            <g:product_type><![CDATA[{{ $category }}]]></g:product_type>
          @endif
          <g:google_product_category>Furniture</g:google_product_category>
        </item>
      @endif
    @endforeach
  </channel>
</rss>
