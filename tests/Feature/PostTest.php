<?php

namespace Tests\Feature;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PostTest extends TestCase
{
    public static function provideValidation(): array
    {
        return [
            'required fields' => [
                'input' => [
                    'title' => null,
                    'content' => null,
                    'image' => null,
                ],
                'errors' => [
                    'title' => 'required',
                    'content' => 'required',
                    'image' => 'required',
                ],
            ],
            'max length title' => [
                'input' => [
                    'title' => Str::random(101),
                    'image' => [placekitten(1280, 720)],
                ],
                'errors' => [
                    'title' => 'max',
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function canRenderPage(): void
    {
        $this->get(PostResource::getUrl())->assertSuccessful();
    }

    /**
     * @test
     */
    public function canRenderColumns(): void
    {
        $data = Post::factory(10)->create();

        Livewire::test(PostResource\Pages\ManagePosts::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('title')
            ->assertCanRenderTableColumn('user.name')
            ->assertCanRenderTableColumn('draft')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    /**
     * @test
     */
    public function create(): void
    {
        $data = Post::factory()->makeOne();

        Livewire::test(PostResource\Pages\ManagePosts::class)
            ->callPageAction(CreateAction::class, [
                'title' => $data->title,
                'content' => $data->content,
                'image' => [$data->image],
                'draft' => $data->draft,
            ]);

        self::assertDatabaseHas(Post::class, [
            'title' => $data->title,
            'content' => $data->content,
            'image' => $data->image,
            'draft' => $data->draft,
        ]);
    }

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function createValidation(array $input, array $errors): void
    {
        $data = Post::factory()->makeOne();

        Livewire::test(PostResource\Pages\ManagePosts::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    /**
     * @test
     */
    public function edit()
    {
        $record = Post::factory()->createOne();
        $data = Post::factory()->makeOne();

        Livewire::test(PostResource\Pages\ManagePosts::class)
            ->callTableAction(EditAction::class, $record, [
                'title' => $data->title,
                'content' => $data->content,
                'image' => [$data->image],
                'draft' => $data->draft,
            ]);

        $record->refresh();
        self::assertEquals($data->title, $record->title);
        self::assertEquals($data->content, $record->content);
        self::assertEquals($data->image, $record->image);
        self::assertEquals($data->draft, $record->draft);
    }

    #[DataProvider(methodName: 'provideValidation')]
    /**
     * @test
     */
    public function editValidation(array $input, array $errors)
    {
        $data = Post::factory()->makeOne();
        $record = Post::factory()->createOne();

        Livewire::test(PostResource\Pages\ManagePosts::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }

    /**
     * @test
     */
    public function delete()
    {
        $record = Post::factory()->createOne();

        Livewire::test(PostResource\Pages\ManagePosts::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }
}
