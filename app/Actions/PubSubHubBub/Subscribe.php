<?php

namespace App\Actions\PubSubHubBub;

use App\Http\YouTube\Channels;

use \Illuminate\Support\Carbon;

use App\Http\Clients\PubSubHubBub;

class Subscribe extends \App\Actions\AbstractAction
{
    public function run($params = [])
    {
        if (isset($params['id'])) {
            $Channel = Channels::byId($params['id']);
            $this->log("Subscribing to " . $Channel->name());
        } else {
            $this->log("Subscribing to all channels");
            foreach (Channels::$list as $class) {
                $this->subscribe($class::$id);
            }
        }

        $this->log("Done at " . Carbon::now());
    }

    public function subscribe($id)
    {
        $subscribed = PubSubHubBub::subscribe($id);
        if ($subscribed === true) {
            $this->log("Subscribed to " . $id);
        } else {
            $this->log("Failed to subscribe to " . $id);
        }
    }
}
