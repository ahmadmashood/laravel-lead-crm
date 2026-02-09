<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    //
	use HasFactory;
	protected $fillable = [
  'name','phone','source','description','status','assigned_to','created_by','assigned_at'
];

public function assignee() {
  return $this->belongsTo(\App\Models\User::class, 'assigned_to');
}

}
