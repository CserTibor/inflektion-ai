<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('successful_emails')) {

            Schema::create('successful_emails', function (Blueprint $table) {
                $table->integer('id', false, true)->autoIncrement();
                $table->mediumInteger('affiliate_id')->unsigned();
                $table->text('envelope');
                $table->string('from', 255);
                $table->text('subject');
                $table->string('dkim', 255)->nullable();
                $table->string('SPF', 255)->nullable();
                $table->float('spam_score')->nullable();
                $table->longText('email');
                $table->longText('raw_text');
                $table->string('sender_ip', 50)->nullable();
                $table->text('to');
                $table->integer('timestamp');

                $table->primary('id');
                $table->index('affiliate_id', 'affiliate_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('successful_emails');
    }
};
