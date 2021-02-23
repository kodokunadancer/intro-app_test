<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;


class GetUserApiTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
      parent::setUp();
      $this->user = factory(User::class)->create();
  }

  /**
   * @test
   * 正常系テスト
   */
  public function should_ログイン中のユーザーを返却する(): void
  {
      $response = $this->actingAs($this->user)->json('GET', route('get.user'));

      $response
          ->assertStatus(200)
          ->assertJson([
              'name' => $this->user->name,
          ]);
  }

  /**
   * @test
   * 異常系テスト
   */

  //HTTPレスポンスのため、nullではなく空文字を返す
  public function should_ログインされていない場合は空文字を返却する(): void
  {
      $response = $this->json('GET', route('get.user'));

      $response->assertStatus(200);
      //非ログイン状態の場合、予定通り空文字が返って来ているか確認
      $this->assertEquals('', $response->content());
  }
}
