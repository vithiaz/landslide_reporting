@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/telegram-user-page.css') }}">
@endpush

<div class="app telegram-user-page">
    <div class="container">
        
        <section class="report-list">
            <h1 class="page-title">Daftar User Telegram</h1>
            <div class="table-card-container">
                <div class="button-wrapper">
                    <button wire:click.prevent='update_user' class="btn btn-update">Update User</button>
                </div>
                <livewire:telegram-user-table />
            </div>
        </section>
    </div>
</div>