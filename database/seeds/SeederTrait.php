<?php

namespace Database\seeds;

trait SeederTrait
{
    protected function seedData($table, $fields, $data, $key = 'id', $seqName = null, $seqValue = 1000)
    {

        if ($seqName === null) {
            $seqName = "${table}_${key}_seq";
        }

        $insertData = array_map(function ($item) use ($fields) {
            return array_combine($fields, $item);
        }, $data);

        foreach ($insertData as $row) {

            if (app('db')->table($table)->where([$key => $row[$key]])->count()) {
                app('db')->table($table)->where([$key => $row[$key]])->update($row);
            } else {
                app('db')->table($table)->insert($row);
            }
        }

        app('db')->statement("SELECT setval('$seqName', $seqValue, false);");
    }
}
