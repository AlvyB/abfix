@props(['columns'])

<thead class="bg-gray-300 text-gray-900 dark:bg-gray-800 dark:text-gray-100">
    <tr class="border-b border-gray-700 text-left uppercase text-xs">
        @foreach($columns as $column)
            <th class="px-3 py-3 {{ $column['class'] ?? '' }}">
                {{ $column['label'] }}
            </th>
        @endforeach
    </tr>
</thead>
