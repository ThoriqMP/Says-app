<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = ['name', 'budget_limit', 'created_by'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
