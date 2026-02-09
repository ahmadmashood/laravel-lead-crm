<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->string('role')->default('salesman'); // admin|operation|salesman
      $table->timestamp('last_seen_at')->nullable()->index();
    });
  }

  public function down(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['role','last_seen_at']);
    });
  }
};
