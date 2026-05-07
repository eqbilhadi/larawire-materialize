<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_menu_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('sys_menus')->cascadeOnDelete();
            $table->string('action');
            $table->string('label');
            $table->string('permission_name');
            $table->string('route_name')->nullable()->after('permission_name');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['menu_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_menu_actions');
    }
};
