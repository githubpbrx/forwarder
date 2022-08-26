<style>
	#header {
		background-color: #2b2b2b;
		height: 60px;
		position: relative;
		border-radius: 5px;
	}

	#headeri {
		background-image: url("../public/img/900x900.png");
		background-position: center;
		background-repeat: no-repeat;
		position: absolute;
		top: 20px;
		left: 20px;
		width: 100%;
	}

	#headerimg {
		height: 100px;
		width: 100px;
		border-radius: 50px;
		background-color: white;
		/*background-image: url("../public/img/45x45.png");*/
		position: fixed;
		top: 68px;
		left: 50%;
		/* bring your own prefixes */
		transform: translate(-50%, -50%);


	}

	.header-centerimage {
		background-image: url("../public/img/900x900.png");
		background-position: center;
		background-repeat: no-repeat;
		background-size: 80px;
		position: fixed;

	}

	.header-left {
		background-image: url("../public/img/dashboard/Asset 1.png");
		position: fixed;
		height: 20%;
		width: 100px;
		top: 40px;
		left: 12%;
	}

	.header-right {
		background-image: url("../public/img/dashboard/Asset 2.png");
		background-position: center;
		background-repeat: no-repeat;
		background-size: 80px;
		position: fixed;
		height: 80px;
	}

	.footers {
		clear: both;
		position: relative;
		height: 3.3%;
		width: 100%;
		background-color: blue;
		margin-top: -200px;
	}

	.kolom1 {
		display: flex;
		width: 49.75%;
		height: 43.6%;
		background: #f5f5f7;
		float: left;
		border-right: 0.15vw solid black;
		border-bottom: 0.15vw solid black;
	}

	.kolom2 {
		display: flex;
		align-items: center;
		width: 49.75%;
		height: 43.6%;
		background: #f5f5f7;
		float: left;
		border-left: 0.15vw solid black;
		border-bottom: 0.15vw solid black;
	}

	.kolom3 {
		display: flex;
		width: 49.75%;
		height: 43.6%;
		background: #f5f5f7;
		float: left;
		border-right: 0.15vw solid black;
		border-top: 0.15vw solid black;

	}

	.kolom4 {
		display: flex;
		align-items: center;
		width: 49.75%;
		height: 43.6%;
		background: #f5f5f7;
		float: left;
		border-left: 0.15vw solid black;
		border-top: 0.15vw solid black;
	}

	.lingkaran {
		background: #e51e63;
		border-radius: 50%;
		box-shadow: 0px 5px 10px -2px #e51e63;
		height: 50px;
		margin: 0 auto;
		width: 50px;
	}

	#tablekolom1 table,
	#tablekolom1 tr,
	#tablekolom1 td {
		border: 4px solid white;
		font-size: 0.7vw;
		font-family: Arial;
		padding: 3px;
		font-weight: bold;
		text-align: center;
	}

	#tablekolom3 table,
	#tablekolom3 tr,
	#tablekolom3 td {
		font-size: 0.7vw;
		font-family: Arial;
		padding: 3px;
		text-align: center;
	}

	.tablekolom3_1 th,
	.tablekolom3_1 td {
		border-bottom: 1px solid black;
	}

	.tablekolom3_1 tr:hover {
		background-color: #90EE90;
	}

	.font-sm {
		font-size: 0.8vw;
	}
	.font-card {
		font-size: 0.7vw;
	}
	.font-card-request {
		font-size: 0.4vw;
	}
	.width-card{
		width: 90px;
	}
</style>
<link href="./assets/fonts/myriad/MYRIADPRO-REGULAR.OTF" rel="stylesheet">
<link href="./assets/fonts/myriad/MYRIADPRO-BOLD.OTF" rel="stylesheet">
<!-- Add the slick-theme.css if you want default styling -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<!-- Add the slick-theme.css if you want default styling -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>

<script>
	tailwind.config = {
		theme: {
			extend: {
				colors: {
					ungu: '#242044',
					card_header: '#94DDCF',
					card_body_active_1: '#FCF8E3',
					card_body_2: '#11B697',
					card_body_active_2: '#FF840C',
				},
				fontFamily: {
					'myriad': ['MYRIADPRO-REGULAR'],
					'myriad-bold': ['MYRIADPRO-BOLD'],
				}
			}
		}
	}
</script>
{{-- <div id="header">
	<div id="headerimg" class="header-centerimage"></div>
	<div class="header-left"></div>
</div> --}}

<div id="header">
	<div id="headerimg" class="header-centerimage"></div>
	<img src="{{asset('public/img/dashboard/Asset 1.png')}}" style="width:20vw;height: 42px; position:fixed; top:50px;left:52.4vw;transform: scaleX(-1);">

	<img src="{{asset('public/img/dashboard/Asset 2.png')}}" style="width:20vw;height: 42px; position:fixed; top:50px;left:26.6vw;transform: scaleX(-1);">

	<p style="position:fixed; top:30px; left:28.5vw; font-size: 1.4vw; color: white; font-family: arial; font-weight: bold;">General Affair Dashboard</p>
	<?php

	function Bulan($str)
	{
		if ($str == '1' or $str == '01') $str = 'Januari';
		elseif ($str == '2' or $str == '02') $str = 'Februari';
		elseif ($str == '3' or $str == '03') $str = 'Maret';
		elseif ($str == '4' or $str == '04') $str = 'April';
		elseif ($str == '5' or $str == '05') $str = 'Mei';
		elseif ($str == '6' or $str == '06') $str = 'Juni';
		elseif ($str == '7' or $str == '07') $str = 'Juli';
		elseif ($str == '8' or $str == '08') $str = 'Agustus';
		elseif ($str == '9' or $str == '09') $str = 'September';
		elseif ($str == '10') $str = 'Oktober';
		elseif ($str == '11') $str = 'November';
		elseif ($str == '12') $str = 'Desember';
		return $str;
	}

	function Hari($str)
	{
		if ($str == 'Sun') $str = 'Minggu';
		if ($str == 'Mon') $str = 'Senin';
		if ($str == 'Tue') $str = 'Selasa';
		if ($str == 'Wed') $str = 'Rabu';
		if ($str == 'Thu') $str = 'Kamis';
		if ($str == 'Fri') $str = 'Jumat';
		if ($str == 'Sat') $str = 'Sabtu';
		return $str;
	}
	$hari = Hari(date("D"));
	$tgl = date('d');
	$bulan = Bulan(date('m'));
	$tahun = date('Y');
	?>
	<p style="position:fixed; top:30px; left:54vw; font-size: 1.4vw; color: white; font-family: arial; font-weight:bold">{{$hari}}, {{$tgl}} {{$bulan}} {{$tahun}}</p>
</div>

<div class="r" style="padding-top: 25px;">
	<div class="kolom1">

		<div style="width:100%">
			{{-- <div style="text-align: right; padding-right: 10px; font-size: 9pt">sort by</div> --}}
			<table id="tablekolom1" width="100%" style="width:100%;">
				<tr style="background-color: #FFBC00;line-height: 3 ">
					<td>
						<p>Mobil</p>
					</td>
					<td>
						<p>Status</p>
					</td>
					<td>
						<p>Driver</p>
					</td>
					<td>
						<p>User</p>
					</td>
					<td>
						<p>Destination</p>
					</td>
					<td>
						<p>Time</p>
					</td>
				</tr>
				<tr style="background-color: #F5FFFA; line-height: 1.5">
					<td>
						<p style="padding-left: 10px; text-align: left;">Toyota Avanza<br>B 1234 CKK</p>
					</td>
					<td>
						<p><img src="{{asset('public/img/dashboard/Asset 7.png')}}" style="width:30%"></p>
					</td>
					<td>
						<p>Igor</p>
					</td>
					<td>
						<p>Ari, IT GO</p>
					</td>
					<td>
						<p>ESGI SAMBI</p>
					</td>
					<td>
						<p>08:00 - 20:00 WIB</p>
					</td>
				</tr>
				<tr style="background-color: #F5FFFA; line-height: 1.5">
					<td>
						<p style="padding-left: 10px; text-align: left;">Toyota Avanza<br>B 1234 CKK</p>
					</td>
					<td>
						<p><img src="{{asset('public/img/dashboard/Asset 9.png')}}" style="width:30%"></p>
					</td>
					<td>
						<p>Igor</p>
					</td>
					<td>
						<p>Ari, IT GO</p>
					</td>
					<td>
						<p>ESGI SAMBI</p>
					</td>
					<td>
						<p>08:00 - 20:00 WIB</p>
					</td>
				</tr>
				<tr style="background-color: #F5FFFA; line-height: 1.5">
					<td>
						<p style="padding-left: 10px; text-align: left;">Toyota Avanza<br>B 1234 CKK</p>
					</td>
					<td>
						<p><img src="{{asset('public/img/dashboard/Asset 7.png')}}" style="width:30%"></p>
					</td>
					<td>
						<p>Igor</p>
					</td>
					<td>
						<p>Ari, IT GO</p>
					</td>
					<td>
						<p>ESGI SAMBI</p>
					</td>
					<td>
						<p>08:00 - 20:00 WIB</p>
					</td>
				</tr>
				<tr style="background-color: #F5FFFA; line-height: 1.5">
					<td>
						<p style="padding-left: 10px; text-align: left;">Toyota Avanza<br>B 1234 CKK</p>
					</td>
					<td>
						<p><img src="{{asset('public/img/dashboard/Asset 9.png')}}" style="width:30%"></p>
					</td>
					<td>
						<p>Igor</p>
					</td>
					<td>
						<p>Ari, IT GO</p>
					</td>
					<td>
						<p>ESGI SAMBI</p>
					</td>
					<td>
						<p>08:00 - 20:00 WIB</p>
					</td>
				</tr>
				<tr style="background-color: #F5FFFA; line-height: 1.5">
					<td>
						<p style="padding-left: 10px; text-align: left;">Toyota Avanza<br>B 1234 CKK</p>
					</td>
					<td>
						<p><img src="{{asset('public/img/dashboard/Asset 7.png')}}" style="width:30%"></p>
					</td>
					<td>
						<p>Igor</p>
					</td>
					<td>
						<p>Ari, IT GO</p>
					</td>
					<td>
						<p>ESGI SAMBI</p>
					</td>
					<td>
						<p>08:00 - 20:00 WIB</p>
					</td>
				</tr>
				<tr style="background-color: #F5FFFA; line-height: 1.5">
					<td>
						<p style="padding-left: 10px; text-align: left;">Toyota Avanza<br>B 1234 CKK</p>
					</td>
					<td>
						<p><img src="{{asset('public/img/dashboard/Asset 9.png')}}" style="width:30%"></p>
					</td>
					<td>
						<p>Igor</p>
					</td>
					<td>
						<p>Ari, IT GO</p>
					</td>
					<td>
						<p>ESGI SAMBI</p>
					</td>
					<td>
						<p>08:00 - 20:00 WIB</p>
					</td>
				</tr>
			</table>
			<a href="#" style="position:absolute; left: 48%; top:49%"><img src="{{asset('public/img/dashboard/Asset 15.png')}}"></a>
		</div>
	</div>
	<div class="kolom2">
		<span class="lingkaran"></span>
	</div>
	<div class="kolom3">
		<div style="width:100%;">
			<table id="tablekolom3" style="width:100%; padding-top:10px">
				<tr style="line-height: 2.5; font-weight: bold ">
					<td style="background-color: #242044; border-radius:14px; color:white;">Mess Panorama</td>
					<td style="background-color: #242044; border-radius:14px; color:white;">Mess Yayasan</td>
				</tr>
				<tr>
					<td style="text-align: right; font-size:8pt; padding-right: 15px">sort by</td>
					<td style="text-align: right; font-size:8pt; padding-right: 15px">sort by</td>
				</tr>
				<tr style="font-size: 9pt">
					<td>
						<table class="tablekolom3_1" style="width: 100%;line-height: 1.4">
							<tr style="background-color: #94DDCF; line-height: 1.4;font-weight: bold">
								<td>Kamar</td>
								<td>User</td>
								<td>Periode Tanggal</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A1</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A2</td>
								<td style="font-size: 8pt; text-align: left">-</td>
								<td style="font-size: 8pt; text-align: left">-</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A3</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A4</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A5</td>
								<td style="font-size: 8pt; text-align: left">-</td>
								<td style="font-size: 8pt; text-align: left">-</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A6</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A7</td>
								<td style="font-size: 8pt; text-align: left">-</td>
								<td style="font-size: 8pt; text-align: left">-</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A8</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="tablekolom3_1" style="width: 100%;line-height: 1.4">
							<tr style="background-color: #94DDCF; line-height: 1.4;font-weight: bold">
								<td>Kamar</td>
								<td>User</td>
								<td>Periode Tanggal</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A1</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A2</td>
								<td style="font-size: 8pt; text-align: left">-</td>
								<td style="font-size: 8pt; text-align: left">-</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A3</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A4</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A5</td>
								<td style="font-size: 8pt; text-align: left">-</td>
								<td style="font-size: 8pt; text-align: left">-</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A6</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A7</td>
								<td style="font-size: 8pt; text-align: left">-</td>
								<td style="font-size: 8pt; text-align: left">-</td>
							</tr>
							<tr style="background-color: #FFFAFA">
								<td>A8</td>
								<td style="font-size: 8pt; text-align: left">ARI | IT GO<br> Bose | IT GO</td>
								<td style="font-size: 8pt; text-align: left">05 Juli 2022 - 06 Juli 2022<br> 05 Juli 2022 - 06 Juli 2022</td>
							</tr>
						</table>
					</td>
				</tr>

			</table>
			<a href="#" style="position:absolute; left: 48%; top:93%"><img src="{{asset('public/img/dashboard/Asset 15.png')}}"></a>
		</div>
	</div>
	<div class="kolom4">
		<section class="menu pt-2">
			<div class="grid justify-items-end mr-4">
				<select class="select2 h-8 lg:w-20 sm:w-full">
					<option value="2022">2022</option>
					<option value="2021">2021</option>
				</select>
			</div>
			<!-- <div class=" ml-2 h-5 w-60   bg-ungu rounded-md">
				<h1 class=" text-white font-bold font-sm text-center font-myriad-bold font-bold">Menu Makan Siang Green Office</h1>
			</div>

			<div class="carousel m-2 mt-2 flex flex-row  justify-content-center">
				<div class="container mx-auto mr-2">
					<div class="h-8   bg-card_header width-card" >
						<div class=" text-center font-sm font-myriad-bold font-bold">Senin</div>
						<div class=" text-center font-sm font-myriad-bold font-bold"> 1 Juli 2022</div>
					</div>
					<div class="h-24  p-2 mt-2 bg-card_body_active_1 width-card">
						<ul class="text-center font-card font-myriad">
							<li>Nasi Putih</li>
							<li>Sayur Lodeh</li>
							<li>Tahu Bacem + Gereh Layur</li>
							<li>Kerupuk Sambal</li>
						</ul>
					</div>
				</div>
				<?php
				for ($i = 0; $i < 7; $i++) { ?>
					<div class="container mx-auto mr-2">
						<div class="h-8   bg-card_header width-card">
							<div class=" text-center font-sm font-myriad-bold font-bold">Senin</div>
							<div class=" text-center font-sm font-myriad-bold font-bold"> <?php echo $i + 2; ?> Juli 2022</div>
						</div>
						<div class="h-24  p-2 mt-2 bg-white width-card">
							<ul class="text-center font-card font-myriad">
								<li>Nasi Putih</li>
								<li>Sayur Lodeh</li>
								<li>Tahu Bacem + Gereh Layur</li>
								<li>Kerupuk Sambal</li>
							</ul>
						</div>

					</div>
				<?php } ?>
			</div> -->


			<div class="h-8 w-60  ml-2  bg-ungu rounded-md ">
			<h1 class=" text-white font-bold font-sm text-center p-2 font-myriad-bold font-bold">Menu Makanan By Request</h1>
			</div>
			<div class="carousel m-2 mt-2 flex flex-row  justify-content-center">
				<div class="container mx-auto mr-2">
					<div class="h-8   bg-card_header width-card" >
						<div class=" text-center font-sm font-myriad-bold font-bold">Senin</div>
						<div class=" text-center font-sm font-myriad-bold font-bold"> 1 Juli 2022</div>
					</div>
					<div class="h-24  p-2 mt-2 bg-card_body_active_2 width-card">
						<ul class="text-center font-card font-myriad text-white">
							<li>Nasi Putih</li>
							<li>Sayur Lodeh</li>
							<li>Tahu Bacem + Gereh Layur</li>
							<li>Kerupuk Sambal</li>
						</ul>
					</div>
				</div>
				<?php
				for ($i = 0; $i < 7; $i++) { ?>
					<div class="container mx-auto mr-2">
						<div class="h-8   bg-card_header width-card">
							<div class=" text-center font-sm font-myriad-bold font-bold">Senin</div>
							<div class=" text-center font-sm font-myriad-bold font-bold"> <?php echo $i + 2; ?> Juli 2022</div>
						</div>
						<div class="h-24  p-2 mt-2 bg-white width-card bg-card_body_2">
							<ul class="text-center font-card font-myriad text-white">
								<!-- <li>Nasi Putih</li>
								<li>Sayur Lodeh</li>
								<li>Tahu Bacem + Gereh Layur</li>
								<li>Kerupuk Sambal</li> -->
							</ul>
						</div>

					</div>
				<?php } ?>
			</div>


		</section>

			
	
	</div>
</div>
<!-- <div class="footers">
	<img src="{{asset('public/img/dashboard/Asset 16.png')}}" style="position: fixed; text-align: center; width: 50px; left: 48.5%; padding-top: 2px">
</div> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
	$(document).ready(function () {
		$(".select2").select2();
	});
	
	
</script>