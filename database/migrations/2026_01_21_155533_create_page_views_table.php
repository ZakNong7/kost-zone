<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('url');
            $table->timestamp('viewed_at');
            $table->index(['ip_address', 'viewed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_views');
    }
};