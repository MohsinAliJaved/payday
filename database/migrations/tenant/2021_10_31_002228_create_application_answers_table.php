<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_applicant_id')->constrained('job_applicants')->onDelete('cascade');
            $table->string('question');
            $table->longText('answer')->nullable();
            $table->longText('attachment')->nullable();
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
        Schema::dropIfExists('application_answers');
    }
}
