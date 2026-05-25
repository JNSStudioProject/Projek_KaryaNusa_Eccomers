<?

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Menjadwalkan perintah untuk menghapus pesanan lama
        $schedule->command('app:delete-old-orders')->daily();
    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
