<?php

namespace App\Listeners;

use App\Models\PaperRecordItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordScoreAndError
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $recordItem = $event->getRecordItem();

        if ($recordItem->is_correct === PaperRecordItem::CORRECT_TYPE_ALL_RIGHT) {
            $recordItem->score = $recordItem->paperItem->score;
            $recordItem->save();
        }

        // TODO::记录错题
    }
}
