@props(['value'])

<td class="text-orange-800">
    <small>
        {{ isset($value) ? $value : '' }}
    </small>
</td>