<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecordatorioReservasPendientesMail;

class RemindPendingReservationsCommand extends Command
{
    /**
     * Firma del comando.
     *
     * Ejemplos:
     *  php artisan reservations:remind-pending
     *  php artisan reservations:remind-pending 45
     */
    protected $signature = 'reservations:remind-pending {minutes=30}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Envía correos de recordatorio a choferes con reservas pendientes por más de X minutos.';

    /**
     * Lógica principal del comando.
     */
    public function handle(): int
    {
        $minutes = (int) $this->argument('minutes');

        $this->info("Buscando reservas PENDIENTES con más de {$minutes} minutos sin respuesta...");

        $cutoff = now()->subMinutes($minutes);

        // 1) Buscar reservas en estado PENDIENTE, viejas, con ride y chofer cargados
        $reservas = Reservation::with(['ride.chofer'])
            ->where('estado', 'PENDIENTE')
            ->where('created_at', '<=', $cutoff)
            ->get();

        if ($reservas->isEmpty()) {
            $this->info('No se encontraron reservas pendientes para notificar.');
            return Command::SUCCESS;
        }

        // 2) Agrupar reservas por chofer para mandar 1 correo por chofer
        $reservasPorChofer = $reservas
            ->filter(function ($reserva) {
                return $reserva->ride && $reserva->ride->chofer;
            })
            ->groupBy(function ($reserva) {
                return $reserva->ride->chofer->id;
            });

        $totalCorreos = 0;

        foreach ($reservasPorChofer as $choferId => $reservasChofer) {
            $chofer = $reservasChofer->first()->ride->chofer;

            Mail::to($chofer->email)->send(
                new RecordatorioReservasPendientesMail($chofer, $reservasChofer)
            );

            $this->info("Se envió recordatorio a {$chofer->email} ({$reservasChofer->count()} reservas).");
            $totalCorreos++;
        }

        $this->info("Comando finalizado. Correos enviados: {$totalCorreos}.");

        return Command::SUCCESS;
    }
}
