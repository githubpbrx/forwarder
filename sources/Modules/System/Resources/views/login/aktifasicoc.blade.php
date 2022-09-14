<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PB Assets | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('public/pbrx.ico') }}" type="image/x-icon">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('public/adminlte/dist/css/adminlte.min.css') }}">
</head>
<style>
    input {
        border-top-style: hidden;
        border-right-style: hidden;
        border-left-style: hidden;
        border-bottom-style: groove;
        background-color: transparent;
    }

    .no-outline:focus {
        outline: none;
    }
</style>

<body class="hold-transition login-page">
    <div class="col-md-12">
        <center>
            <div class="card text-justify" style="width: 75%; position: center;">
                <div class="card-header text-center bg-primary">
                    COMMITMENT Code of Conduct (CODE OF CONDUCT)
                    <br>
                    Associate of PT Pan Brothers Tbk and Group
                </div>
                <div class="card-body" style="height: 500px; overflow-y: auto; background-color: #A5F1E9">
                    @if ($datacoc == '0')
                        <div>
                            <p class="text-center">
                                <b>COMMITMENT Code of Conduct (CODE OF CONDUCT)</b>
                                <br>
                                <b>Associate of PT Pan Brothers Tbk and Group</b>
                            </p>
                            <p>
                                On this day, <input type="text" class="no-outline" id="day">, dated <input
                                    type="date" id="date"> (__ - ___ -
                                2016), we, the undersigned:
                            </p>
                            <p>
                            <ol>
                                <li>
                                    Mrs. Fitri R. Hartono in this matter acting as a Director of PT Pan Brothers Tbk
                                    including
                                    subsidiaries of PT Pancaprima Ekabrothers, PT Ocean Asia Industry, PT Teodore Pan
                                    Garmindo,
                                    PT
                                    Hollit International, PT Victory Pan Multitex, PT Prima Sejati Sejahtera, PT Berkah
                                    Indo
                                    Garment, PT Eco Smart Garment Indonesia, PT Eco Laundry Hijau Indonesia, PT
                                    Apparelindo
                                    Mitra
                                    Andalan, PT Apparelindo Prima Sentosa, PT Mitra Busana Sentosa, PT Prima Cosmic
                                    Screen
                                    Graphics,
                                    PT Prima Kreasi Gemilang.
                                    - hereinafter referred to as the “First Party” or “the Company”.
                                </li>
                                <li>
                                    <br>
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="">Name</label>
                                            </td>
                                            <td>
                                                :<input type="text" class="no-outline" id="name">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="">Position</label>
                                            </td>
                                            <td>
                                                :<input type="text" class="no-outline" id="position">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="">Company</label>
                                            </td>
                                            <td>
                                                :<input type="text" class="no-outline" id="company">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="">Address</label>
                                            </td>
                                            <td>
                                                :<input type="text" class="no-outline" id="address">
                                            </td>
                                        </tr>
                                    </table>
                                    Therein acting for and on behalf of PT/CV <input type="text" class="no-outline"
                                        id="ptcv">,
                                    domiciled
                                    in <input type="text" class="no-outline" id="domicili">, hereinafter
                                    is referred to as the “Second Party” or “Partner”.
                                    (each a “Party” and, together, the “Parties”)
                                </li>
                            </ol>
                            </p>
                            <p>
                                The Parties hereby explain as follows :
                            </p>
                            <p>
                            <ol>
                                <li> That the Second Party is a Partner or candidate Partner of the First Party, who is
                                    willing to
                                    bind him/herself/themselves to support the First Party in the intention of achieving
                                    one
                                    of
                                    the
                                    Principles of success.
                                </li>
                                <li>
                                    That PT Pan Brothers Tbk domicile at Tangerang, PT Pancaprima Ekabrothers domicile
                                    at
                                    Jakarta, PT Ocean Asia Industry domicile at Serang, PT Teodore Pan Garmindo domicile
                                    at
                                    Jakarta,
                                    PT Hollit International domicile at Jakarta, PT Victory Pan Moltitex domicile at
                                    Jakarta, PT
                                    Prima Sejati Sejahtera domicile at Boyolali, PT Berkah Indo Garment domicile at
                                    Tangerang,
                                    PT
                                    Eco Smart Garment Indonesia domicile at Tangerang, PT Eco Laundry Hijau Indonesia
                                    domicile
                                    at
                                    Sragen, PT Apparelindo Mitra Andalan domicile at Jakarta, PT Apparelindo Prima
                                    Sentosa
                                    domicile
                                    at Jakarta, PT Mitra Busana Sentosa domicile at Jakarta, PT Prima Cosmic Screen
                                    Graphics
                                    domicile at Boyolali, PT Prima Kreasi Gemilang domicile at Boyolali.
                                </li>
                                <li>
                                    That First Party in its course of achieving its mission to maintain leadership in
                                    the
                                    apparel
                                    industry (garment) and other related fields, in a global and competitive business
                                    environment,
                                    the primary critical value in achieving Company’s success is to maintain integrity
                                    professionalism, transparency, and responsibility of the Partners as Suppliers,
                                    Vendors,
                                    logistics, etc.
                                </li>
                            </ol>
                            </p>
                            <p>
                                The Second Party will further hereby agrees and undertakes to comply with all provisions
                                in
                                this
                                Code of Conduct as the basis of ground rules and guidelines for the Partners of First
                                Party
                                in
                                the form of the following conditions:
                            </p>
                            <p class="text-center">
                                <b> ARTICLE 1 </b>
                            </p>
                            <p>
                                That the Second Party as the Partner agrees to abide by the rules in the Company’s
                                workplace
                                or
                                industry that in such a way does not cause adverse events including but not limited to:
                            <ol>
                                <li>
                                    Partner is not allowed to conduct personal transactions with Company’s employee(s)
                                    and
                                    where
                                    the Company’s employee(s) has equity investments, relationships and blood relatives
                                    by
                                    marriage.
                                </li>
                                <li>
                                    Partner is not allowed to conduct outside business interest or activities within
                                    working
                                    hours in the Company.
                                </li>
                                <li>
                                    Partner is prohibited from providing gifts, meals or commission or other items which
                                    constitute as bribes to Company’s employee(s).
                                </li>
                                <li>
                                    In order to maintain a healthy business relationship with the Company, Partners must
                                    abide
                                    by the rules set by the Company. A formal prior written notice and transparency must
                                    be
                                    maintained in the Company.
                                </li>
                                <li>
                                    For partners who have conducted transactions with the Company, they bear obligation
                                    and
                                    responsibility for maintaining the continuity of good relationship with the Company,
                                    and
                                    must not misuse this relationship in an illegal manner for the interest of Partner
                                    and
                                    Partner’s Employee.
                                </li>
                                <li>
                                    As the Company has Customers or Buyers (“Client”), Partners are therefore required
                                    to
                                    provide the best service and quality products to the Company, to subsequently
                                    provide
                                    excellent service to the Clients.
                                </li>
                            </ol>
                            </p>
                            <p class="text-center">
                                <b> ARTICLE 2 </b>
                            </p>
                            <p>
                                The Partner hereby declares in the event of disobedience, disrespect, and failure to
                                comply
                                with
                                the Commitment Code of Conduct as defined in Article 1, either intentionally or
                                unintentionally,
                                the Partner is willing to take responsibilities for any consequences arising therein.
                            </p>
                            <p class="text-center">
                                <b> ARTICLE 3 </b>
                            </p>
                            <p>
                                In the event of a violation or violations of commitments defined in Article 1 either
                                intentionally or unintentionally, the Second Party is willing to bear the risk,
                                including
                                but
                                not limited to being disqualified as Partner from the First Party’s list and any costs
                                or
                                risks
                                arising from the Second Party’s activities as a Partner of the First Party list will be
                                borne
                                entirely by the Second Party
                            </p>
                            <p>
                                This Commitment Code of Conduct is signed knowingly and without coercion of any party.
                            </p>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            <b>First Party/Company</b>
                                            <br>
                                            <b>PT Pan Brothers Tbk and Subsidiaries</b>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            <b>Second Party/Partner</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <br><br><br><br><br>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table>
                                            <tr>
                                                <td>
                                                    Name
                                                </td>
                                                <td>
                                                    : Mrs. Fitri R. Hartono
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Position Title
                                                </td>
                                                <td>
                                                    : Director
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table>
                                            <tr>
                                                <td>
                                                    Name
                                                </td>
                                                <td>
                                                    :<input type="text" class="no-outline" id="namepartner">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Position Title
                                                </td>
                                                <td>
                                                    :<input type="text" class="no-outline" id="positionpartner">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($datacoc == '1')
                        <center>
                            <h3>
                                PLEASE WAIT!
                                <br>
                                YOUR DATA IS CURRENTLY PROCESSING
                            </h3>
                        </center>
                    @else
                        <center>
                            <h3>
                                YOUR DATA IS REJECTED
                                <br>
                                PLEASE CHECK AGAIN
                            </h3>
                            <br>
                            <h5>
                                DESCRIPTION : <input type="text" class="no-outline" id="deskripsi" readonly>
                            </h5>
                            <br>
                            <a href="{{ route('validasicocreject') }}" class="btn btn-success">Try Again</a>

                        </center>
                    @endif
                </div>
                @if ($datacoc == '0')
                    <div class="card-footer bg-primary">
                        <input type="checkbox" id="cekbok"> I Have read and agree/accept to the terms and conditions
                        of
                        the
                        COMMITMENT Code
                        Of Conduct (CoC)
                        <br>
                        <button class="btn btn-success" id="btnagree"> You Have Agreed</button>
                    </div>
                @else
                    <div class="card-footer bg-primary">
                        <a href="{{ url('logout') }}" class="btn btn-danger">Exit</a>
                    </div>
                @endif
            </div>
        </center>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('public/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('public/adminlte/dist/js/adminlte.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#btnagree').prop('disabled', true);

            var datafwd = @JSON($datafwd);
            var datacoc = @JSON($coc);

            $('#name').val(datafwd.name);
            $('#position').val(datafwd.position);
            $('#company').val(datafwd.company);
            $('#address').val(datafwd.address);
            $('#ptcv').val(datafwd.company);
            $('#domicili').val(datafwd.address);
            $('#namepartner').val(datafwd.name);
            $('#positionpartner').val(datafwd.position);
            $('#deskripsi').val(datacoc.ket_tolak);

            $('#cekbok').change(function(e) {
                if (this.checked) {
                    if ($('#day').val() == '') {
                        notifalert('Day');
                    } else if ($('#date').val() == '') {
                        notifalert('Date');
                    } else {
                        console.log('objectsijine :>> ', 'isChecked');
                        $('#btnagree').prop('disabled', false);
                    }
                } else {
                    console.log('objectsijine :>> ', 'notChecked');
                    $('#btnagree').prop('disabled', true);
                }
            });

            $('#btnagree').click(function(e) {
                $('#btnagree').html('<i class="fas fa-hourglass"></i> Please Wait')
                $('#savebtn').prop('disabled', true)
                let dayku = $('#day').val();
                let dateku = $('#date').val();
                let name = $('#name').val();
                let position = $('#position').val();
                let company = $('#company').val();
                let address = $('#address').val();

                $.ajax({
                    type: "post",
                    url: "{!! route('validasicocaction') !!}",
                    data: {
                        // _token: $('meta[name=csrf-token]').attr('content'),
                        _token: "{{ csrf_token() }}",
                        day: dayku,
                        date: dateku,
                        name: name,
                        position: position,
                        company: company,
                        address: address
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ? 'success' : 'error'
                        }).then((result) => {
                            $('#btnagree').html(
                                'You Have Agreed')

                            $('#btnagree').prop('disabled', false);
                            (response.status == 'success') ? window.location
                                .replace("{{ route('dashcam') }}"):
                                ''
                        });
                        return;
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Unsuccessfully Saved Data',
                            text: 'Check Your Data',
                            type: 'error'
                        });
                        return;
                    }
                });
            });

            function notifalert(params) {
                Swal.fire({
                    title: 'Information',
                    text: params + ' Can not be empty',
                    type: 'warning'
                }).then((result) => {
                    $('#cekbok').prop('checked', false)
                });
                return;
            }

        });
    </script>
</body>

</html>
