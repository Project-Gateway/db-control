<?php

class AccountsSeeder extends \Illuminate\Database\Seeder
{
    use \Database\seeds\SeederTrait;

    public function run()
    {

        $databaseName = config('database.connections.default.database');
        $userRole = "${databaseName}_user";

        $fields = ['id', 'password', 'name', 'role'];
        $data = [
            [1, '$2a$06$D00TvTY30uARj5Rc6PDlFeyQrYI7KlbQgkXD9gYMkUef.QsOuCUNu', 'Jeff', $userRole],
            [2, '$2a$06$D00TvTY30uARj5Rc6PDlFeyQrYI7KlbQgkXD9gYMkUef.QsOuCUNu', 'Vlad', $userRole],
        ];

        $this->seedData('accounts', $fields, $data);
    }

}
