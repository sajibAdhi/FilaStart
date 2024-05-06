
namespace {!! $namespace !!};

use {!! $resource !!};
use Filament\Actions;
use {!! $baseResourcePage !!};

class {!! $resourcePageClass !!} extends {!! $baseResourcePageClass !!}
{
    protected static string $resource = {!! $resourceClass !!}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
