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
        Schema::create('admin_setting_logs', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');           // konsi table change hui
            $table->string('field_name');           // konsa field change hua
            $table->text('old_value')->nullable();  // pehle kya tha
            $table->text('new_value')->nullable();  // ab kya hua
            $table->string('changed_by')->default('admin'); // kisne change kiya
            $table->string('ip_address', 45)->nullable();   // admin ka IP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_setting_logs');
    }
};
