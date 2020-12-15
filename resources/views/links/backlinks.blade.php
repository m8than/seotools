@extends('layouts.app')

@section('title', 'Backlink Tool')

@section('content')
<table id="inbox" class="seo-table seo-table--striped">
    <thead>
        <th width="18px"></th>
        <th>Last Sender</th>
        <th>Subject</th>
        <th width="180px">Last Received</th>
        <th width="20px">Messages</th>
    </thead>
    <tr class="seo-table__row seo-table__row--noresults" style="{{count($message_chains) == 0 ? 'display:table-row' : ''}}">
        <td colspan="5">No results found</td>
    </tr>
    <tbody class="searchable seo-table__body seo-table__body--highlight seo-table__body--clickable">
        @foreach ($rootlinks as $backlink)

        @endforeach
    </tbody>
</table>
@endsection