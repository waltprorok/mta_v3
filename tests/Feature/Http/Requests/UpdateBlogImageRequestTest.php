<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\UpdateBlogImageRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateBlogImageRequestTest extends TestCase
{
    use WithFaker;

    public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateBlogImageRequest();
    }

    public function test_verifies_authorized()
    {
        $user = factory(User::class)->make(["teacher" => true]);

        $this->actingAs($user)->assertTrue($this->request->authorize());
    }

    public function test_request_pass()
    {
        $validator = Validator::make([
            'image' => null,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'Image fail extension doc' =>
                ['image', 'test.doc'],
            'Image fail extension docx' =>
                ['image', 'test.docx'],
            'Image fail extension odt' =>
                ['image', 'test.odt'],
            'Image fail extension raw' =>
                ['image', 'test.raw'],
            'Image fail extension tif' =>
                ['image', 'test.tif'],
            'Image fail extension bmp' =>
                ['image', 'test.bmp'],
            'Image fail extension ico' =>
                ['image', 'test.ico'],
            'Image fail extension mp3' =>
                ['image', 'test.mp3'],
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
