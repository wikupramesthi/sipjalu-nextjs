<link rel="stylesheet" href="{{ asset('dist/assets/extensions/toastify-js/src/toastify.css') }}">
<script src="{{ asset('dist/assets/extensions/toastify-js/src/toastify.js') }}"></script>

@if ($errors->any())
  <!-- Toast with Placements -->
  <script>
    @foreach ($errors->all() as $error)
      Toastify({
        text: "{{ $error }}", // Display each error message
        duration: 4000, // Duration of the toast
        close: true, // Option to close the toast manually
        gravity: "top", // Toast appears at the top
        position: "right", // Align toast to the right
        style: { background: '#dc3545' }, // Error color (Bootstrap danger)
      }).showToast();
    @endforeach
  </script>
@endif

@if (session()->has('success'))
  <script>
    Toastify({
      text: "{{ session('success') }}", // Display success message from session
      duration: 4000, // Duration of the toast
      close: true, // Option to close the toast manually
      gravity: "top", // Toast appears at the top
      position: "right", // Align toast to the right
        style: { background: '#28a745' }, // Success color
    }).showToast();
  </script>
@endif
