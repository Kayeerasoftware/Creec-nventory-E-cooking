@if($user->image ?? auth()->user()->image ?? null)
    <img src="{{ asset('storage/' . ($user->image ?? auth()->user()->image)) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
@else
    <i class="fas fa-user {{ $class ?? 'text-white' }}" style="{{ $style ?? 'font-size: 0.75rem;' }}"></i>
@endif
