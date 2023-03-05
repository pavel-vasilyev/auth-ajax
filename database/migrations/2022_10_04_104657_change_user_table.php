<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('users', function (Blueprint $table) {
            // Делаем уникальным стандартное поле name - используем его для хранения логина:
            $table->unique('name', 'users_name_unique');
            // Создаем поле статуса пользователя (доступные статусы: -1 - удален, 0 - не активирован, 1 - активирован):
            $table->enum('status', ['-1', '0', '1'])->default(User::STATUS_INACTIVE)->after('remember_token');
            // Создаём поле для токена email-верификации:
            $table->string('verify_token')->after('status')->nullable()->unique();
        });

        // Активируем зарегистрированных ранее пользователей (если таковые имеются):
        DB::table('users')->update([
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_name_unique');
            $table->dropColumn('status');
            $table->dropColumn('verify_token');
        });
    }
};
