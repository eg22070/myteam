@props(['disabled' => false, 'options' => [], 'selected' => null, 'default' => null])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    <option value="">Select role</option>
    <option value="Owner">Owner</option>
    <option value="Coach">Coach</option>
    <option value="Player">Player</option>
</select>