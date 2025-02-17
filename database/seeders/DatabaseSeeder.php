<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Project;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Run Voyager's core seeder first
        $this->call([
            VoyagerDatabaseSeeder::class,
        ]);

        // Create core data types with proper display names
        $dataTypes = [
            [
                'slug' => 'roles',
                'name' => 'roles',
                'display_name_singular' => __('voyager::seeders.data_types.role.singular'),
                'display_name_splural' => __('voyager::seeders.data_types.role.plural'),
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
            ],
            [
                'slug' => 'users',
                'name' => 'users',
                'display_name_singular' => __('voyager::seeders.data_types.user.singular'),
                'display_name_plural' => __('voyager::seeders.data_types.user.plural'),
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
            ],
            // [
            //     'slug' => 'projects',
            //     'name' => 'projects',
            //     'display_name_singular' => __('voyager::seeders.data_types.project.singular'),
            //     'display_name_plural' => __('voyager::seeders.data_types.project.plural'),
            //     'icon' => 'voyager-folder',
            //     'model_name' => 'App\\Models\\Project',
            // ],
        ];

        foreach ($dataTypes as $dataType) {
            $dbDataType = DataType::firstOrNew(['slug' => $dataType['slug']]);
            if (!$dbDataType->exists) {
                $dbDataType->fill($dataType)->save();
            }
        }

        // Generate Project data type
        $projectDataType = DataType::firstOrNew(['slug' => 'projects']);
        if (!$projectDataType->exists) {
            $projectDataType->fill([
                'name' => 'projects',
                'display_name_singular' => 'Project',
                'display_name_plural' => 'Projects',
                'icon' => 'voyager-folder',
                'model_name' => 'App\\Models\\Project',
                'generate_permissions' => 1,
                'description' => '',
            ])->save();
        }

        // Now run the dummy data seeder
        $this->call([
            VoyagerDummyDatabaseSeeder::class,
        ]);

        // Create a test project if none exist
        if (\App\Models\Project::count() == 0) {
            \App\Models\Project::create([
                'name' => 'Test Project',
                'description' => 'This is a test project created by the seeder.',
            ]);
        }
    }
}
