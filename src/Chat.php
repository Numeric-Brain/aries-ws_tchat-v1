<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    private $tab_clients, $administrateur;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->administrateur = null;
        echo "#####| SERVEUR ON\n";
    }

    public function __destruct() {
        echo "#####| SERVEUR OFF\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
    }


    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        // $this->espace = ($conn->resourceId<100) ? '  ' : ' ';
        // echo $this->espace.$conn->resourceId." | _________________________________\n";
        // echo $this->espace.$conn->resourceId." | ########## CONNEXION ###########\n";
        // echo $conn->remoteAddress;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
      // FORMATAGE
      $user_id = $from->resourceId;
      $espace = ($user_id<100) ? '  ' : ' ';

      // DECODE du MESSAGE
      $obj_mess = json_decode($msg);

      // IDENTIFICATION
      if (isset($obj_mess->ident)) {
        // ADMINISTRATEUR
        if (($obj_mess->ident=='ADMIN') && ($obj_mess->msg=='zF?pg5*X62')) {
          // IDENTIFICATION de l'ADMIN
          $this->administrateur = $from;
        }
        // Affichage CONSOLE
        $this->tab_clients[$user_id] = $obj_mess->ident;
        echo "#####| CONNEXION ".$obj_mess->ident." (".$user_id.")\n";
        // BROADCAST Général
        foreach ($this->clients as $client) {
          if ($from !== $client) {
            // The sender is not the receiver, send to each client connected
            $client->send('{"co":"'.$obj_mess->ident.'", "id":"'.$user_id.'"}');
          }
        }
        // MAJ SOLO des CONNECTIONS
        foreach ($this->tab_clients as $cle => $valeur) {
          if ($user_id !== $cle) {
            $from->send('{"co":"'.$valeur.'", "id":"'.$cle.'"}');
          }
        }

      // MESSAGE STANDARD
      } else {

        foreach ($this->clients as $client) {
          if ($from !== $client) {
              // The sender is not the receiver, send to each client connected
              $client->send('{"ident":"'.$this->tab_clients[$user_id].'", "msg":"'.$obj_mess->msg.'"}');
          }
        }

        // AFFICHAGE CONSOLE ADMIN
        echo ($user_id<100) ? '  ' : ' ';
        echo $user_id." | ".$this->tab_clients[$user_id]." : ".$obj_mess->msg."\n";

      }
    }

    public function onClose(ConnectionInterface $conn) {
        // FORMATAGE
        $user_id = $conn->resourceId;
        echo "#####| DÉCONNEXION ".$this->tab_clients[$user_id]." (".$user_id.")\n";
        // BROADCAST
        foreach ($this->clients as $client) {
          if ($conn !== $client) {
            // The sender is not the receiver, send to each client connected
            $client->send('{"deco":"'.$this->tab_clients[$user_id].'", "id":"'.$user_id.'"}');
          }
        }
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        // Nettoyage
        unset($this->tab_clients[$user_id]);
        // TEST
        if (count($this->tab_clients)==0) {
          // $this->__destruct();
          echo "#####| Salle vide...\n";
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo " ! Une erreur est survenue : {$e->getMessage()}\n";
        $conn->close();
    }
}
