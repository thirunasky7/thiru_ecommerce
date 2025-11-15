<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductModeToProducts extends Migration {
    public function up(){
        Schema::table('products', function(Blueprint $table){
            $table->enum('product_mode', ['preorder','regular'])->default('regular');
        });
    }
    public function down(){
        Schema::table('products', function(Blueprint $table){
            $table->dropColumn('product_mode');
        });
    }
}