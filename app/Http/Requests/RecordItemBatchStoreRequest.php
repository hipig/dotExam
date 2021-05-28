<?php

namespace App\Http\Requests;

class RecordItemBatchStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'items' => 'required|array',
            'done_time' => 'required|integer',
            'items.*.paper_item_id' => 'required',
        ];
    }
}
