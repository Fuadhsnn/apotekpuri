<?php

namespace App\Filament\Widgets;

use App\Models\Obat;
use App\Models\StockNotification;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;

class StockNotificationWidget extends Widget
{
    protected static string $view = 'filament.widgets.stock-notification-widget';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    // Polling interval in seconds
    protected static ?int $pollingInterval = 10;

    protected function getViewData(): array
    {
        return [
            'lowStockObats' => $this->getLowStockObatsProperty(),
            'stockNotifications' => $this->getStockNotificationsProperty(),
        ];
    }

    public function getLowStockObatsProperty(): Collection
    {
        // Get unread stock notifications
        return $this->getStockNotificationsProperty()->map(function ($notification) {
            return $notification->obat;
        })->filter();
    }

    public function getStockNotificationsProperty(): Collection
    {
        // Get unread stock notifications with their related obat
        return StockNotification::where('is_read', false)
            ->with('obat')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Method to mark notification as read
    public function markAsRead(int $notificationId): void
    {
        $notification = StockNotification::find($notificationId);

        if ($notification) {
            $notification->update(['is_read' => true]);

            // Send a success notification to the user
            Notification::make()
                ->title('Notifikasi ditandai sebagai dibaca')
                ->success()
                ->send();

            // Dispatch browser event to refresh the component
            // Memicu event yang akan didengarkan oleh metode refreshNotifications() di atas
            $this->dispatch('stock-notifications-updated');
        }
    }
}
