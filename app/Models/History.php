<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';
    protected $fillable = ['title','action','desc','history_category_id','card_code'];

    public function getHistoryCategoryAttribute()
    {
        return HistoryCategory::find($this->history_category_id);
    }
}
