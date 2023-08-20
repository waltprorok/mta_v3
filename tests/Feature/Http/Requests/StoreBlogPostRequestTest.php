<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\StoreBlogPostRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreBlogPostRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreBlogPostRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);
        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'title' => $this->faker->word,
            'slug' => $this->faker->word,
            'body' => $this->faker->paragraphs,
            'released_on' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'Slug null fail' =>
                ['slug', null],
            'Body null fail' =>
                ['body', null],
            'Released on null fail' =>
                ['released_on', null],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     */
    public function test_request_fail($key, $value)
    {
        $validator = Validator::make([
            $key => $value,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains($key, $validator->errors()->keys());
    }
}
