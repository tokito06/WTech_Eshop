@props([
    'modalId' => 'popOutModal',
    'title' => 'Confirm action',
    'message' => null,
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'action' => null,
    'confirmClass' => 'btn btn-primary',
    'cancelClass' => 'btn btn-secondary',
    'footerClass' => 'd-flex justify-content-end gap-2',
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4">
                <h2 class="h5 mb-3" id="{{ $modalId }}Label">{{ $title }}</h2>
                @if(trim((string) $slot) !== '')
                    <div class="text-muted mb-4">{{ $slot }}</div>
                @elseif($message)
                    <p class="text-muted mb-4">{{ $message }}</p>
                @endif

                <div class="{{ $footerClass }}">
                    @isset($buttons)
                        {{ $buttons }}
                    @else
                        <button type="button" class="{{ $cancelClass }}" data-bs-dismiss="modal">{{ $cancelText }}</button>

                        @if($action)
                            <form method="POST" action="{{ $action }}">
                                @csrf
                                <button type="submit" class="{{ $confirmClass }}">{{ $confirmText }}</button>
                            </form>
                        @else
                            <button type="button" class="{{ $confirmClass }}" data-bs-dismiss="modal">{{ $confirmText }}</button>                        @endif
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>


