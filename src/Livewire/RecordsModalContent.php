<?php

namespace Yeganehha\FilamentRecordsFinder\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
class RecordsModalContent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public string $resource ;

    public function mount(string $resource): void
    {
        $this->resource  = $resource;
    }

    public function table(Table $table): Table
    {
        /** @var Resource $resourceName */
        $resourceName = $this->resource;
        return $resourceName::table($table)
            ->selectable()
            ->actions([])
            ->bulkActions([
                BulkAction::make('applySelected')
                    ->label( 'انتخاب '. $resourceName::getPluralModelLabel())
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $selectedRecords) use ($table) {
                        $table->getLivewire()->dispatch('apply-selected-rows', $selectedRecords);
                    }),
            ])
            ->query($resourceName::getEloquentQuery());
    }

    public function render(): View|Factory|Application
    {
        return view('filament-records-finder::modal-content');
    }
}
