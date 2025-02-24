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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
use Closure;

class RecordsModalContent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public string $resource ;
    public ?string $specailQuery ;
    public array $selected ;

    public function mount(string $resource , ?string $query , array $selected): void
    {
        $this->resource  = $resource;
        $this->selected  = $selected;
        $this->specailQuery  = $query;
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
        $primryKey = $this->getPrimaryKey();
        if ( $this->specailQuery){
            $query = $resourceName::getRecordSelectorQuery($this->specailQuery);
        } else {
            $query = $resourceName::getEloquentQuery();
        }
        $tableObject =  $resourceName::table($table)
            ->selectable()
            ->actions([])
            ->view('filament-records-finder::Table')
            ->deselectAllRecordsWhenFiltered(false)
            ->bulkActions([
                BulkAction::make('applySelected')
                    ->label( 'انتخاب '. $resourceName::getPluralModelLabel())
//                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $selectedRecords) use ($table,$primryKey) {
                        $table->getLivewire()->dispatch('apply-selected-rows', $selectedRecords->pluck($primryKey));
                    }),
            ])
            ->query($query);
        return $tableObject;
    }

    public function render(): View|Factory|Application
    {
        return view('filament-records-finder::modal-content');
    }
}
