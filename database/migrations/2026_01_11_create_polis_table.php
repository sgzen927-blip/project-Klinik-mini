<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// File: xxxx_create_polis_table.php
public function up()
{
Schema::create('polis', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // [cite: 49]
    $table->timestamps();
});
}};