<?php

namespace App\Utils;

enum BroadcastNoteStateEnum: string{
    case DETACHED = 'DETACHED';
    case ASSIGNED = 'ASSIGNED';
    case UPDATED = 'UPDATED';
}