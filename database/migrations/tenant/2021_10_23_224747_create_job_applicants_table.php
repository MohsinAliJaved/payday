<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_post_id')->nullable()->constrained('job_posts')->cascadeOnDelete();
            $table->foreignId('current_stage_id')->constrained('job_stages')->cascadeOnDelete();
            $table->foreignId('status_id')->nullable()->constrained()->nullOnDelete();            
            $table->mediumText('apply_form_setting')->nullable();
            $table->string('slug','120')->unique();
            $table->enum('review',['0','1','2','3','4','5'])->default('0');
            $table->string('disqualification_reason')->nullable();
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
        Schema::dropIfExists('job_applicants');
    }
}
