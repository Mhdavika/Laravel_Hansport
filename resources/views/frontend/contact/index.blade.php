@extends('layouts.frontend')

@section('title', 'Contact')

@push('styles')
<!-- contact -->
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/bootstrap4/bootstrap.min.css') }}">
<link href="{{ asset('frontend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/animate.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/plugins/themify-icons/themify-icons.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/jquery-ui-1.12.1.custom/jquery-ui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/contact_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/contact_responsive.css') }}">
@endpush

@section('content')

<div class="container contact_container">
	<div class="row">
		<div class="col">

			<!-- Breadcrumbs -->
			@include('layouts.breadcrumbs')

		</div>
	</div>

	<!-- Map Container -->

	<div class="row">
		<div class="col">
			<div id="google_map">
				<div class="map_container">
					<div id="map"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Contact Us -->

	<div class="row">

		<div class="col-lg-6 contact_col">
			<div class="contact_contents">
				<h1>Hubungi Kami</h1>
				<p>Ada banyak cara untuk menghubungi kami. Anda dapat mengirim pesan, menghubungi kami melalui telepon, atau mengirim email — pilih cara yang paling nyaman untuk Anda.
				</p>
				<div>
					<p>+62 856 9872 6923</p>
					<p>unpak@unpak.ac.id</p>
				</div>
				<div>
					<p>Jl. Pakuan No No.38, RT.02/RW.06, Tegallega, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16129</p>
				</div>
				<div>
					<p>Jam Operasional: </p>
					<p>Senin – Jumat: 08.00 – 18.00 </p>
					<p>Sabtu – Minggu: Libur</p>
				</div>
			</div>

			<!-- Follow Us -->

			<div class="follow_us_contents">
				<h1>Follow Us</h1>
				<ul class="social d-flex flex-row">
					<li><a href="https://www.facebook.com/Fauzan.Iqbl1743" target="_blank" style="background-color: #3a61c9"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
					<li><a href="#" style="background-color: #41a1f6"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
					<li><a href="#" style="background-color: #fb4343"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
					<li><a href="https://www.instagram.com/fauzaniqq/" target="_blank" style="background-color: #8f6247"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
				</ul>
			</div>

		</div>

		<div class="col-lg-6 get_in_touch_col">
			<div class="get_in_touch_contents">
				<h1>Hubungi Kami Langsung!</h1>
				<p>Isi formulir di bawah ini dan kami akan segera menghubungi Anda secara pribadi dan rahasia.</p>
				@if (session('success'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					{{ session('success') }}
					<button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				<form action="{{ route('contact.store') }}" method="POST">
					@csrf
					<div>
						<input class="form_input input_ph" type="text" name="name" placeholder="Nama" required>
						<input class="form_input input_ph" type="email" name="email" placeholder="Email" required>
						<input class="form_input input_ph" type="text" name="whatsapp" placeholder="Nomor WhatsApp" required>
						<textarea class="input_ph input_message" name="message" placeholder="Pesan Anda" rows="3" required></textarea>
					</div>
					<div>
						<button type="submit" class="red_button message_submit_btn trans_300">Kirim Pesan</button>
					</div>
				</form>

			</div>
		</div>

	</div>
</div>

@endsection

@push('scripts')
<!-- contact -->
<script src="{{ asset('frontend/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/popper.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/Isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('frontend/plugins/easing/easing.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCIwF204lFZg1y4kPSIhKaHEXMLYxxuMhA"></script>
<script src="{{ asset('frontend/plugins/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/js/contact_custom.js') }}"></script>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) alert.style.display = 'none';
    }, 4000); // 4 detik
</script>

@endpush