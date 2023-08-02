<?php

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Merchant::class)->constrained()->cascadeOnDelete();
            // TODO: Replace me with a brief explanation of why floats aren't the correct data type, and replace with the correct data type.
            // should use the decimal data type instead of float because decimal provides precise representation
            // for monetary values and percentages, while float may have inaccuracies in precise calculations.
            // $table->float('commission_rate');
            $table->decimal('commission_rate', 8, 2);
            $table->string('discount_code');
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
        Schema::dropIfExists('affiliates');
    }
};
