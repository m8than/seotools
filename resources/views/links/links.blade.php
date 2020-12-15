@extends('layouts.app')

@section('title', 'Links Tracked')

@section('content')
<h1>Link Tracker</h1>

@include('links.table-filters', ['url' => route('links.index') ])
<table id="inbox" class="seo-table seo-table--striped">
    <thead>
        <th>Link</th>
        <th width="20px">Views</th>
        <th width="20px">Backlinks</th>
        <th width="20px">Views/Day</th>
        <th width="80px">Crawled?</th>
        <th width="80px">Indexed?</th>
        <th width="80px">Our Rating</th>
    </thead>
    <tr class="seo-table__row seo-table__row--noresults" style="{{count($tableOutput) == 0 ? 'display:table-row' : ''}}">
        <td colspan="7">No results found</td>
    </tr>
    <tbody class="searchable seo-table__body seo-table__body--highlight seo-table__body--clickable">
        @foreach ($tableOutput as $rootlink)
            <tr data-id={{ $rootlink->id }} onclick="window.open('{{ $rootlink->url }}', '_blank');">
                <td>{{ $rootlink->url }}</td>
                <td>{{ $rootlink->views_count }}</td>
                <td>{{ $rootlink->backlinks_count }}</td>
                <td>{{ round($rootlink->views_per_day, 2) }}</td>
                <td>{!! $rootlink->crawled_date ? '<span style="color:green">' . $rootlink->crawled_date->diffForHumans() . '</span>' : '<span style="color:red">no</span>' !!}</td>
                <td>{!! $rootlink->indexed_date ? '<span style="color:green">' . $rootlink->indexed_date->diffForHumans() . '</span>' : '<span style="color:red">no</span>' !!}</td>
                <td>{{ round($rootlink->rating, 3) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@include('links.table-filters', ['url' => route('links.index') ])
@endsection