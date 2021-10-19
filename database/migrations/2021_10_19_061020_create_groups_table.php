<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
                $table->string('name')->nullable();
                $table->boolean('is_active')->nullable();
                $table->date('created_at')->nullable();
                $table->date('updated_at')->nullable();
                $table->integer('added_by')->nullable();
                $table->integer('updated_by')->nullable();
            });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
