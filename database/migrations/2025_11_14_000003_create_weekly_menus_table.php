<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyMenusTable extends Migration {
    public function up(){
        Schema::create('weekly_menus', function(Blueprint $table){
            $table->id();
            $table->string('day'); // monday, tuesday ...
            $table->enum('meal_type',['breakfast','lunch','dinner','snack'])->default('lunch');
            $table->json('product_ids')->nullable(); // array of product ids
            $table->boolean('status')->default(true); // enabled/disabled
            $table->timestamps();
        });
    }
    public function down(){
        Schema::dropIfExists('weekly_menus');
    }
}