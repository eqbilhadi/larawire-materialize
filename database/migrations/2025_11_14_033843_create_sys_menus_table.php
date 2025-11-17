<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sys_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_num')->nullable();
            $table->string('icon')->nullable();
            $table->string('label_name_en');
            $table->string('label_name_pt');
            $table->string('label_name_tl');
            $table->string('controller_name')->nullable();
            $table->string('route_name')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_divider')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_menus');
    }
};
