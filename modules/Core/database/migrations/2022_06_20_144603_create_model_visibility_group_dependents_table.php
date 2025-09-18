<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.6.0
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2025 KONKORD DIGITAL
 */

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
        Schema::create('model_visibility_group_dependents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_visibility_group_id');
            $table->foreign('model_visibility_group_id', 'visibility_group_id_foreign')
                ->references('id')
                ->on('model_visibility_groups')
                ->cascadeOnDelete();
            $table->morphs('dependable', 'dependable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @codeCoverageIgnore
     */
    public function down(): void
    {
        Schema::dropIfExists('model_visibility_group_dependents');
    }
};
