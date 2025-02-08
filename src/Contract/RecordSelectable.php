<?php

namespace Yeganehha\FilamentRecordsFinder\Contract;

interface RecordSelectable
{
    public static function getRecordSelectorQuery(string $type = null): \Illuminate\Database\Eloquent\Builder;
}
