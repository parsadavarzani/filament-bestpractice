<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique()->nullable();

            $table->boolean('is_visible')->default(0);

            $table->timestamps();
        });

        Schema::create('categoryables', function (Blueprint $table) {
            $table->foreignIdFor(Category::class);
            $table->integer('categoryable_id');
            $table->string('categoryable_type');

            $table->primary(['category_id', 'categoryable_id', 'categoryable_type']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
