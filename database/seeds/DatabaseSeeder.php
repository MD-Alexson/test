<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        DB::statement("SELECT setval('users_id_seq', 10000);");
        DB::statement("SELECT setval('posts_id_seq', 10000);");
        DB::statement("SELECT setval('categories_id_seq', 10000);");
        DB::statement("SELECT setval('comments_id_seq', 10000);");
        DB::statement("SELECT setval('files_id_seq', 10000);");
        DB::statement("SELECT setval('homeworks_id_seq', 10000);");
        DB::statement("SELECT setval('levels_id_seq', 10000);");
        DB::statement("SELECT setval('notifications_id_seq', 10000);");
        DB::statement("SELECT setval('payments_id_seq', 10000);");
        DB::statement("SELECT setval('susers_id_seq', 10000);");
        DB::statement("SELECT setval('videos_id_seq', 10000);");
        DB::statement("SELECT setval('webinars_id_seq', 10000);");

        $this->call(PlansTableSeeder::class);
        $this->call(InvoicesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}