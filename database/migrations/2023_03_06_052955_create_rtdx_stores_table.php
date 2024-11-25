<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRtdxStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rtdx_stores', function (Blueprint $table) {
            $table->id();
						$table->integer('SOURCE_SYS_ID')->nullable();
						$table->string('FACT_TYPE_CODE')->nullable();
						$table->string('SBMSN_TYPE_CODE')->nullable();
						$table->date('DUE_PERD');
						$table->string('TIME_PERD_TYPE_CODE')->nullable();
						$table->date('TIME_PERD_START_DATE')->nullable();
						$table->string('TRADE_CHANNEL_ID')->nullable();
						$table->string('LGCY_STORE_ID')->nullable();
						$table->string('DIST_BRANCH_ID')->nullable();
						$table->integer('SUBDISTR_STORE_FLAG')->nullable();
						$table->integer('IN_COVERAGE_FLAG')->nullable();
						$table->integer('STORE_STATUS_FLAG')->nullable();
						$table->integer('GOLDEN_STORE_FLAG')->nullable();
						$table->string('STORE_NAME')->nullable();
						$table->string('Street')->nullable();
						$table->string('Block')->nullable();
						$table->string('County')->nullable();
						$table->string('ZIP_CODE')->nullable();
						$table->string('GENERIC_TEXT_FIELD_1')->nullable();
						$table->string('GENERIC_TEXT_FIELD_2')->nullable();
						$table->string('GENERIC_TEXT_FIELD_3')->nullable();
						$table->string('GENERIC_NUM_FIELD_1')->nullable();
						$table->string('GENERIC_NUM_FIELD_2')->nullable();
						$table->string('CHAIN_DESC')->nullable();
						$table->string('CITY_GEO')->nullable();
						$table->string('SELLER_ID')->nullable();
						$table->string('LATITUDE')->nullable();
						$table->string('LONGITUDE')->nullable();
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
        Schema::dropIfExists('rtdx_stores');
    }
}
