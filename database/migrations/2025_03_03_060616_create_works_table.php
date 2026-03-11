<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            // created by
            $table->unsignedBigInteger('created_by')->nullable();// IT WILL BE THE ID OF THE USER WHO IS LOGGED IN AND FILLING THE FORM`S ID. 
            
            
            // Applicant Information
            $table->string('name_of_applicant');
            $table->decimal('number_of_applicants',12);

            // Bank Details
            $table->string('bank_name')->nullable(); 
            $table->unsignedBigInteger('bank_branch')->nullable(); // select feald having all user name whose roles is "Bank Branch"

            // Address Details
            $table->string('source')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('post_office')->nullable();
            $table->string('police_station')->nullable();

            // Loan Details
            $table->string('project_name')->nullable();
            $table->string('loan_amount_requested')->nullable();
            $table->string('loan_type')->nullable();

            // File Uploads
            $table->string('pdf_1')->nullable(); // Store file path

            // Assigned Users
            $table->unsignedBigInteger('assignee_surveyor')->nullable();// select feald having all user name whose roles is "Surveyor" 
            $table->unsignedBigInteger('assignee_reporter')->nullable();// select feald having all user name whose roles is "Reporter" 
            $table->unsignedBigInteger('assignee_checker')->nullable();// select feald having all user name whose roles is "Checker" 
            $table->unsignedBigInteger('assignee_delivery')->nullable();// select feald having all user name whose roles is "Delivery Person" 

            // Status & Payment
            $table->enum('status', ['New File', 'Surveying', 'Reporting', 'Checking', 'Completed', 'Hold', 'Canceled', ])->default('New File');
            $table->string('work_type')->default('valuation');
            $table->enum('payment_status', ['Payment Due', 'Paid'])->default('Payment Due');
            $table->enum('delivery_status', ['Delivery Due', 'Delivery Done'])->default('Delivery Due');
            $table->string('final_report_pdf')->nullable();
            $table->string('final_report_word')->nullable();

            // Foreign Key Constraints
            $table->foreign('assignee_surveyor')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('assignee_reporter')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('assignee_checker')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assignee_delivery')->references('id')->on('users')->onDelete('set null');
            $table->foreign('bank_branch')->references('id')->on('users')->onDelete('set null'); 
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('works');
    }
};
