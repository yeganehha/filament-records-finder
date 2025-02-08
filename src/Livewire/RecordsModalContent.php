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
    public string $specialQueryName ;

    public function mount(string $resource , string $query): void
    {
        $this->resource  = $resource;
        $this->specialQueryName  = $query;
    }


    public function getPrimaryKey(): ?string
    {
        /** @var Resource $resourceName */
        $resourceName = $this->resource;
        $model = $resourceName::getModel();
        return (new $model)->getKeyName();
    }


    public function table(Table $table): Table
    {
        /** @var Resource $resourceName */
        $resourceName = $this->resource;
        $primaryKey = $this->getPrimaryKey();
        if ( $this->specialQueryName == 'getEloquentQuery')
            $query = $resourceName::getEloquentQuery();
        else{
            $queryName =  $this->specialQueryName;
            $query = $resourceName::$$queryName;
        }
        return $resourceName::table($table)
            ->selectable()
            ->actions([])
            ->bulkActions([
                BulkAction::make('applySelected')
                    ->label( 'انتخاب '. $resourceName::getPluralModelLabel())
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $selectedRecords) use ($table,$primaryKey) {
                        $table->getLivewire()->dispatch('apply-selected-rows', $selectedRecords->pluck($primaryKey));
                    }),
            ])
            ->query($query);
    }

    public function render(): View|Factory|Application
    {
        return view('filament-records-finder::modal-content');
    }
}
