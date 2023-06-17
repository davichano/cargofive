<?php

namespace Http\Controllers\Api;

use App\Structure\Abstract\Services\SurchargesServiceInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery;
use Tests\TestCase;

class SurchargesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test getting all fathers.
     */
    public function testGetAllFathers(): void
    {
        // Make the GET request.
        $response = $this->get('/api/surcharges/getAllFathers');
        // Assert that the response is successful.
        $response->assertStatus(200);
    }

    /**
     * Test getting all fathers and checking the JSON answer.
     */
    public function testGetAllFathersAndCheckJsonAnswer(): void
    {
        // Make the GET request.
        $response = $this->getJson('/api/surcharges/getAllFathers');
        // Assert that the first Surcharge model in the response body has the following properties.
        $response->assertJson(fn(AssertableJson $json) => $json->first(fn(AssertableJson $json) => $json->where('id', 1)
            ->whereAllType([
                'id' => 'integer',
                'name' => 'string|null',
                'apply_to' => 'string',
                'calculation_type_id' => 'integer',
                'isGrouped' => 'integer',
                'idFather' => 'integer|null',
            ])
            ->etc()
        )
        );
    }

    /**
     * Test failing to update an empty Excel file.
     */
    public function testFailUpdateEmptyExcel()
    {

        // Create an empty Excel file
        $uploadedFile = UploadedFile::fake()->create('ChallengeRates.xlsx');

        // Make the post call
        $response = $this->post('/api/surcharges/updateExcel', [
            'excel' => $uploadedFile,
        ]);

        //Validate the answer
        $response->assertJson(fn(AssertableJson $json) => $json->has('answer'));
        $response->assertJson(fn(AssertableJson $json) => $json->whereType('answer', 'boolean'));
        $response->assertJson(fn(AssertableJson $json) => $json->where('answer', false));
    }

    /**
     * Test updating with a correct Excel file.
     */
    public function testUpdateWhitCorrectExcelFile()
    {
        // Get the Excel file.
        $file = new UploadedFile(
            base_path('tests/files/ChallengeRates.xlsx'),
            'ChallengeRates.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
        // Make the POST call.
        $response = $this->post('/api/surcharges/updateExcel', [
            'excel' => $file,
        ]);
        //Validate the answer
        $response->assertJson(fn(AssertableJson $json) => $json->has('answer'));
        $response->assertJson(fn(AssertableJson $json) => $json->whereType('answer', 'boolean'));
        $response->assertJson(fn(AssertableJson $json) => $json->where('answer', true));
    }

    /**
     * Test getting all surcharges.
     */
    public function testGetAll()
    {
        $response = $this->get('/api/surcharges/getAll');
        $response->assertStatus(200);
    }

    /**
     * Test the answer code when calling joinGroups by GET.
     */
    public function testAnswerCodeWhenCallJoinGroupsByGet()
    {
        $response = $this->get('/api/surcharges/joinGroups');
        $response->assertStatus(405);
    }

    /**
     * Test the answer when calling joinGroups with an empty POST.
     * The answer will be false
     */
    public function testAnswerWhenCallJoinGroupsWithEmptyPost()
    {
        $response = $this->post('/api/surcharges/joinGroups');
        $response->assertJson(fn(AssertableJson $json) => $json->has('answer'));
        $response->assertJson(fn(AssertableJson $json) => $json->whereType('answer', 'boolean'));
        $response->assertJson(fn(AssertableJson $json) => $json->where('answer', false));
        $response->assertStatus(200);
    }
}
