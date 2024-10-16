<?php
//
//namespace Tests\Feature\Http\Requests;
//
//use App\Http\Requests\SendMessageRequest;
//use App\Models\User;
//use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Support\Facades\Validator;
//use Tests\TestCase;
//
//class SendMessageRequestTest extends TestCase
//{
//    use WithFaker;
//
//    public $request;
//
//    public function setUp(): void
//    {
//        parent::setUp();
//        $this->request = new SendMessageRequest();
//    }
//
//    public function test_verifies_authorized()
//    {
//        $user = factory(User::class)->make(["teacher" => true]);
//
//        $this->actingAs($user)->assertTrue($this->request->authorize());
//    }
//
//    public function test_request_pass()
//    {
//        $validator = Validator::make([
//            'to' => 1,
//            'subject' => $this->faker->text,
//            'body' => $this->faker->sentences,
//        ], $this->request->rules());
//
//        $this->assertTrue($validator->passes());
//    }
//
//    /**
//     * @return array[]
//     */
//    public function requestDataProvider(): array
//    {
//        return [
//            'To fail' =>
//                ['to', null],
//            'Subject fail' =>
//                ['subject', null],
//            'Message fail' =>
//                ['body', null],
//        ];
//    }
//
//    /**
//     * @dataProvider requestDataProvider
//     */
//    public function test_request_fail($key, $value)
//    {
//        $validator = Validator::make([
//            $key => $value,
//        ], $this->request->rules());
//
//        $this->assertFalse($validator->passes());
//        $this->assertContains($key, $validator->errors()->keys());
//    }
//}
