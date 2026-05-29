<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
