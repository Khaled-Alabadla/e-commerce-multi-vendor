<x-front-layout title="Success Payment">
    <!-- Start Error Area -->
    <div class="maill-success">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="container">
                    <div class="success-content">
                        <i class="lni lni-envelope"></i>
                        <h2>Your Payment Done Successfully</h2>
                        <p>Thanks you</p>
                        <div class="button">
                            <a href="{{ route('home') }}" class="btn">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Error Area -->

    @push('scripts')
        <script src="assets/js/bootstrap.min.js"></script>
        <script>
            window.onload = function() {
                window.setTimeout(fadeout, 500);
            }

            function fadeout() {
                document.querySelector('.preloader').style.opacity = '0';
                document.querySelector('.preloader').style.display = 'none';
            }
        </script>
    @endpush
</x-front-layout>
