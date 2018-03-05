<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Feature\Batch\DefaultBatchDetails;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
    }


    public function refreshDataBase()
    {
        $this->artisan('migrate:fresh');
    }

    public function createBatch($overrides = [])
    {
        try {
            return $this->json("POST", '/api/batch', array_merge(DefaultBatchDetails::getBatch(), $overrides))
                ->decodeResponseJson("data");
        } catch (\Exception $e) {
            return ["_id" => "NOT_CREATED"];
        }
    }

    public function createBatchRequest()
    {
        try {
            return $this->json("POST", '/api/batch', DefaultBatchDetails::getBatch());
        } catch (\Exception $e) {
            return ["_id" => "NOT_CREATED"];
        }
    }
}
