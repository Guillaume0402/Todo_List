<?php
namespace App\Services;

use MongoDB\Client;
use MongoDB\Collection;
use App\Core\Auth;

final class ActivityLogger
{
    private ?Collection $col = null;

    public function __construct()
    {
        try {
            // Si l’extension n’est pas chargée côté SAPI Web,
            // new Client() lèvera une Error -> on catch et on désactive le logger.
            $client   = new Client(\AppConfig::MONGO_DSN);
            $this->col = $client->selectCollection(\AppConfig::MONGO_DB, 'activity_logs');
        } catch (\Throwable $e) {
            $this->col = null; // pas de crash si Mongo/ext absents
        }
    }

    /**
     * Log d’une action simple.
     * $extra: entity, entity_id, status, message, details (facultatifs)
     */
    public function log(string $action, array $extra = []): void
    {
        if (!$this->col) return;

        // On évite toute référence directe à des classes de l’extension.
        // -> pas de "use MongoDB\BSON\UTCDateTime" ni "UTCDateTime::class" ici.
        $utcClass  = 'MongoDB\\BSON\\UTCDateTime';
        $createdAt = \class_exists($utcClass)
            ? new $utcClass((int) (\microtime(true) * 1000)) // vrai BSON date si ext chargée
            : \gmdate('c');                                   // sinon ISO 8601 (string)

        $doc = [
            'action'     => $action,
            'user_id'    => Auth::isLoggedIn() ? (int) Auth::getUserId() : null,
            'entity'     => $extra['entity']    ?? null,
            'entity_id'  => $extra['entity_id'] ?? null,
            'status'     => $extra['status']    ?? 'ok',
            'message'    => $extra['message']   ?? null,
            'details'    => $extra['details']   ?? null,
            'route'      => $_GET['r'] ?? null,
            'method'     => $_SERVER['REQUEST_METHOD'] ?? null,
            'ip'         => $_SERVER['REMOTE_ADDR'] ?? null,
            'ua'         => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => $createdAt,
        ];

        try {
            $this->col->insertOne($doc);
        } catch (\Throwable $e) {
            // on ignore : le logger ne doit jamais casser l’app
        }
    }
}
