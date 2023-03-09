<?php

namespace Tests\Feature;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\PostResource\Pages\ManagePosts;
use App\Models\Post;
use Filament\Pages\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
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
            'max length' => [
                'input' => [
                    'title' => Str::random(101),
                ],
                'errors' => [
                    'title' => 'max',
                ],
            ],
        ];
    }

    #[Test]
    public function canRenderList(): void
    {
        $this->get(PostResource::getUrl())->assertSuccessful();
    }

    #[Test]
    public function canRenderColumns(): void
    {
        $data = Post::factory(10)->create();

        Livewire::test(ManagePosts::class)
            ->assertCanSeeTableRecords($data)
            ->assertCanRenderTableColumn('title')
            ->assertCanRenderTableColumn('user.name')
            ->assertCanRenderTableColumn('draft')
            ->assertCanRenderTableColumn('created_at')
            ->assertCanRenderTableColumn('updated_at');
    }

    #[Test]
    public function canCreate(): void
    {
        $data = Post::factory()->make();

        Livewire::test(ManagePosts::class)
            ->callPageAction(CreateAction::class, [
                'title' => $data->title,
                'content' => $data->content,
                'image' => [$data->image],
                'draft' => $data->draft,
            ])
            ->assertHasNoPageActionErrors();

        self::assertDatabaseHas(Post::class, [
            'title' => $data->title,
            'content' => $data->content,
            'image' => $data->image,
            'draft' => $data->draft,
        ]);
    }

    #[Test]
    public function canEdit(): void
    {
        $record = Post::factory()->create();
        $data = Post::factory()->make();

        Livewire::test(ManagePosts::class)
            ->callTableAction(EditAction::class, $record, [
                'title' => $data->title,
                'content' => $data->content,
                'image' => [$data->image],
                'draft' => $data->draft,
            ])
            ->assertHasNoTableActionErrors();

        $record->refresh();

        self::assertEquals($data->title, $record->title);
        self::assertEquals($data->content, $record->content);
        self::assertEquals($data->image, $record->image);
        self::assertEquals($data->draft, $record->draft);
    }

    #[Test]
    public function canDelete(): void
    {
        $record = Post::factory()->create();

        Livewire::test(ManagePosts::class)
            ->callTableAction(DeleteAction::class, $record)
            ->assertHasNoTableActionErrors();

        self::assertModelMissing($record);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateCreate(array $input, array $errors): void
    {
        $data = Post::factory()->make(['image' => [placekitten(1280, 720)]]);

        Livewire::test(ManagePosts::class)
            ->callPageAction(CreateAction::class, array_merge($data->toArray(), $input))
            ->assertHasPageActionErrors($errors);
    }

    #[Test, DataProvider(methodName: 'provideValidation')]
    public function canValidateEdit(array $input, array $errors): void
    {
        $data = Post::factory()->make(['image' => [placekitten(1280, 720)]]);
        $record = Post::factory()->create();

        Livewire::test(ManagePosts::class)
            ->callTableAction(EditAction::class, $record, array_merge($data->toArray(), $input))
            ->assertHasTableActionErrors($errors);
    }
}
