<?php

class AccountEmailsSeeder extends \Illuminate\Database\Seeder
{

    use \Database\seeds\SeederTrait;

    public function run()
    {
        $fields = ['id', 'email', 'account_id'];
        $data = [
            [1, 'jefersonparanaense@gmail.com', 1],
            [2, 'vlad.kobilansky@gmail.com', 2],
        ];

        $this->seedData('account_emails', $fields, $data);
    }
}
