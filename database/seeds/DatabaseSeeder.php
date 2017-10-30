<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
                'name' => 'role-list',
                'label' => 'Liste des roles',

            ],
            [
                'name' => 'role-create',
                'label' => 'Créer un role',

            ],
            [
                'name' => 'role-edit',
                'label' => 'Modifier un role',

            ],
            [
                'name' => 'role-delete',
                'label' => 'Supprimer un role',

            ],
            [
                'name' => 'projet-list',
                'label' => 'liste des projets',

            ],
            [
                'name' => 'projet-create',
                'label' => 'Créer un projet',

            ],
            [
                'name' => 'projet-edit',
                'label' => 'Modifier un projet',

            ],
            [
                'name' => 'projet-delete',
                'label' => 'Supprimer un projet',

            ]
        ];
        foreach ($permission as $key => $value) {
            \App\Permission::create($value);
        }

        \App\Role::create(array(
            'name' => 'admin',
            'label' => 'Administrateur'
        ));

        \App\User::create(array(
            'name' => 'admin',
            'password'=>bcrypt('password'),
            'email'=>'admin@intelcia.com'));

        \App\RoleUser::create(array(
            'role_id'=>1,
            'user_id'=>1
        ));

        /*$this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);*/


    }
}
