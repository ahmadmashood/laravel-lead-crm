<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lead;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $admin = User::updateOrCreate(
      ['email' => 'admin@demo.com'],
      ['name'=>'Admin', 'password'=>Hash::make('password'), 'role'=>'admin']
    );

    $op = User::updateOrCreate(
      ['email' => 'op@demo.com'],
      ['name'=>'Operation Head', 'password'=>Hash::make('password'), 'role'=>'operation']
    );

    $s1 = User::updateOrCreate(['email'=>'sales1@demo.com'], [
      'name'=>'Salesman 1', 'password'=>Hash::make('password'), 'role'=>'salesman'
    ]);
    $s2 = User::updateOrCreate(['email'=>'sales2@demo.com'], [
      'name'=>'Salesman 2', 'password'=>Hash::make('password'), 'role'=>'salesman'
    ]);
    $s3 = User::updateOrCreate(['email'=>'sales3@demo.com'], [
      'name'=>'Salesman 3', 'password'=>Hash::make('password'), 'role'=>'salesman'
    ]);

    Lead::factory()->count(20)->create();
  }
}
