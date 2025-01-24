<?php

namespace Yeganehha\FilamentRecordsFinder\Filament\Forms\Component;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
class RecordsSelector extends Select
{

    protected string $view = 'filament-records-finder::records-selector';

    protected string $resource ;

    protected string|Closure $title = 'name';
    public function recordLabelAttribute(string|Closure $title = 'name'): static
    {
        $this->title = $title;
        return $this;
    }


    public function getRecordTitle(Model|array|string|int $record): string
    {
        if (  is_string($record) or  is_int($record)){
            /** @var Resource $resourceName */
            $resourceName = $this->resource;
            /** @var Model $ModelName */
            $ModelName = $resourceName::getModel();
            $record = $ModelName::query()->find($record);
        }
        if ( is_array($record) ) {
            /** @var Resource $resourceName */
            $resourceName = $this->resource;
            $ModelName = $resourceName::getModel();
            $record = new $ModelName($record);
        }
        if ( $this->title instanceof Closure) {
            $title = $this->title;
            return $title($record);
        }
        return is_string($this->title) ? $record->{$this->title} : $record->getKey();
    }

    public function resource(string $resource): RecordsSelector
    {
        $this->resource = $resource;
        return $this;
    }

    public function getPluralLabel(): ?string
    {
        /** @var Resource $resourceName */
        $resourceName = $this->resource;
        return $resourceName::getPluralModelLabel();
    }

    public function getLabel(): ?string
    {
        /** @var Resource $resourceName */
        $resourceName = $this->resource;
        return $resourceName::getModelLabel();
    }

    public function getResource(): string
    {
        return $this->resource;
    }
}
