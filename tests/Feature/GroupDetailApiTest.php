<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Group;
use App\Photo;
use App\User;
use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupDetailApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        factory(Profile::class)->create(['user_id' => $this->user->id]);
        $this->group = $this->user->groupUser()->save(factory(Group::class)->make());
    }

    /**
     * @test
     * 正常系テスト
     */
    public function should_正しい構造のJSONの返却(): void
    {
        factory(User::class, 5)
            ->create()
            ->each(function ($user): void {
                $user->groupUser()->save($this->group);
                $profile = $user->profiles()->save(factory(Profile::class)->make());
                $profile->photos()->save(factory(Photo::class)->make());
            });

        $response = $this->actingAs($this->user)
            ->json('GET', route('show.group', [
                      'user' => $this->user->id,
                      'group' => $this->group->id,
                    ]));

        //以下から検証
        $response
            ->assertStatus(200);
    }
}
