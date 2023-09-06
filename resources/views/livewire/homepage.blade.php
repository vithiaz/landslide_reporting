@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
@endpush

<div class="app homepage">
    <div class="container">
        
        <section class="report-list">
            <h1 class="page-title">Daftar Laporan</h1>
            <div class="table-card-container">
                <livewire:report-table/>
            </div>
        </section>
    </div>
</div>