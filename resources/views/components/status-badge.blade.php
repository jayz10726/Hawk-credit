@props(['status'])
@php
$classes = match($status) {
    'approved', 'active', 'confirmed', 'paid', 'exceptional', 'excellent'
        => 'bg-green-100 text-green-700',
    'submitted', 'under_review', 'pending'
        => 'bg-blue-100 text-blue-700',
    'draft'     => 'bg-gray-100 text-gray-600',
    'rejected', 'defaulted', 'failed', 'overdue', 'very_poor'
        => 'bg-red-100 text-red-700',
    'partial', 'fair'  => 'bg-yellow-100 text-yellow-700',
    'good'    => 'bg-blue-100 text-blue-700',
    'poor'    => 'bg-orange-100 text-orange-700',
    default   => 'bg-gray-100 text-gray-500',
};
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ ucwords(str_replace(['_', '-'], ' ', $status)) }}
</span>
