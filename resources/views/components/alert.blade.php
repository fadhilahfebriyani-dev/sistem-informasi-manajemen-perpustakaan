@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if ($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '{{ $errors->first() }}',
        confirmButtonColor: '#2563eb',
        confirmButtonText: 'Coba Lagi'
    });
    @endif

    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
    @endif

    @if (session('error'))
    Swal.fire({
        icon: 'error',
        title: '{{ session('error') }}',
        confirmButtonColor: '#2563eb'
    });
    @endif

    @if (session('warning'))
    Swal.fire({
        icon: 'warning',
        title: '{{ session('warning') }}',
        confirmButtonColor: '#f59e0b'
    });
    @endif

});
</script>
@endpush