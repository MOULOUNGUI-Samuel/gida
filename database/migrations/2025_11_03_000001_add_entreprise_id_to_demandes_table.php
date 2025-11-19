<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('demandes', function (Blueprint $table) {
            if (!Schema::hasColumn('demandes', 'entreprise_id')) {
                $table->unsignedBigInteger('entreprise_id')->nullable()->after('assignee_user_id');
                $table->index('entreprise_id');
                // FK to entreprise table (table name is 'entreprise')
                $table->foreign('entreprise_id')->references('id')->on('entreprise')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('demandes', function (Blueprint $table) {
            if (Schema::hasColumn('demandes', 'entreprise_id')) {
                $table->dropForeign(['entreprise_id']);
                $table->dropIndex(['entreprise_id']);
                $table->dropColumn('entreprise_id');
            }
        });
    }
};
