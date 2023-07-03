<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draftnews', function (Blueprint $table) {
            $table->uuid("idDraft")->primary();
            $table->text("title")->nullable();
            $table->text("content")->nullable();
            $table->date("savedDate")->default(date('Y-m-d'));
            $table->text("image")->nullable();
            $table->foreignUuid("user_id")->references('idUser')->on('user')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draftnews');
    }
};
