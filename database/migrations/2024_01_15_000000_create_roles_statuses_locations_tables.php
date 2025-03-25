<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create statuses table
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('icon_class')->nullable(); // For FontAwesome icons
            $table->string('color_class')->nullable(); // For text color classes
            $table->timestamps();
        });

        // Create locations table
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Modify users table to use foreign keys
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing role column
            $table->dropColumn('role');
            
            // Add foreign key references
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('location_id')->nullable()->constrained('locations');
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'Administrador', 'description' => 'System Administrator'],
            ['name' => 'Usuario', 'description' => 'Regular User'],
        ]);

        // Insert default statuses
        DB::table('statuses')->insert([
            [
                'name' => 'Activo',
                'description' => 'Active User',
                'icon_class' => 'fa-circle',
                'color_class' => 'text-success'
            ],
            [
                'name' => 'Pendiente',
                'description' => 'Pending User',
                'icon_class' => 'fa-circle',
                'color_class' => 'text-warning'
            ],
            [
                'name' => 'Inactivo',
                'description' => 'Inactive User',
                'icon_class' => 'fa-circle',
                'color_class' => 'text-danger'
            ],
        ]);

        // Insert default locations
        DB::table('locations')->insert([
            ['name' => 'Madrid', 'description' => 'Madrid Location'],
            ['name' => 'Barcelona', 'description' => 'Barcelona Location'],
        ]);

        // Update existing users to use the new role system
        DB::table('users')->update([
            'role_id' => DB::table('roles')->where('name', 'Usuario')->first()->id,
            'status_id' => DB::table('statuses')->where('name', 'Activo')->first()->id,
        ]);

        // Update admin user
        DB::table('users')
            ->where('id', 1)
            ->update([
                'role_id' => DB::table('roles')->where('name', 'Administrador')->first()->id,
                'status_id' => DB::table('statuses')->where('name', 'Activo')->first()->id,
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn(['role_id', 'status_id', 'location_id']);
            $table->string('role')->default('Usuario');
        });

        Schema::dropIfExists('locations');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('roles');
    }
};