<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstadoPedidoNotificacion extends Notification
{
    use Queueable;

    public $pedido;
    public $nuevoEstado;

    /**
     * Crea una nueva instancia de notificación de estado de pedido.
     *
     * @param Pedido $pedido
     * @param string $nuevoEstado
     */
    public function __construct(Pedido $pedido, string $nuevoEstado)
    {
        $this->pedido = $pedido;
        $this->nuevoEstado = $nuevoEstado;
    }

    /**
     * Determina los canales de notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Construye el correo de notificación según el estado.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $numPedido = str_pad($this->pedido->id, 5, '0', STR_PAD_LEFT);
        
        switch ($this->nuevoEstado) {
            case 'enviado':
                $asunto = "🚚 ¡Tu pedido #{$numPedido} ha sido Enviado! - Sector Mueble";
                break;

            case 'entregado':
            case 'recibido':
                $asunto = "¡Tu pedido de Sector Mueble ya está en casa! 🛋️✨ / Entrega exitosa del pedido #{$numPedido}";
                break;

            case 'contacto_agente':
                $asunto = "Recibimos tu mensaje en Sector Mueble 🛋️ / Un asesor te contactará pronto";
                break;

            case 'pagado':
            case 'completado':
            case 'procesando':
            case 'recibido_pago':
            case 'confirmado':
            case 'pago_recibido':
            case 'pendiente':
                $asunto = "¡Confirmamos tu pedido en Sector Mueble! 🎉 / Pedido #{$numPedido} recibido";
                break;

            default:
                $asunto = "📦 Actualización sobre tu pedido #{$numPedido} - Sector Mueble";
                break;
        }

        return (new MailMessage)
            ->subject($asunto)
            ->view('emails.estado_pedido', [
                'pedido'      => $this->pedido,
                'nuevoEstado' => $this->nuevoEstado,
                'email'       => $notifiable->email ?? $this->pedido->correo_cliente,
            ]);
    }
}
