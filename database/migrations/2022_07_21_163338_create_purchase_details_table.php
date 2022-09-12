<?php

use App\Models\ChilliPrice;
use App\Models\Purchase;
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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Purchase::class)->constrained();
            $table->foreignIdFor(ChilliPrice::class)->constrained();
            $table->float('healthy_amount_of_chilies', 8, 2, true);
            $table->float('number_of_damaged_chilies', 8, 2, true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
};
