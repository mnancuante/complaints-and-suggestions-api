<?php
// esta clase es un enum que contiene los estados de una queja para consumirlos desde el servicio para evitar hardcodearlos
class ComplaintStatus {
    const OPEN = 'open';
    const IN_PROGRESS = 'in_progress';
    const RESOLVED = 'resolved';
    const CLOSED = 'closed';


    public static function isValid(string $status): bool {
        $valid_statuses = [
            self::OPEN,
            self::IN_PROGRESS,
            self::RESOLVED,
            self::CLOSED
        ];
    
        return in_array($status, $valid_statuses);
    }
}