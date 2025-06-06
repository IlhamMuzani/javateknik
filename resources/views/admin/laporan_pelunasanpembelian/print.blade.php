<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pelunasan Pembelian</title>
    <style>
        html,
        body {
            font-family: 'Arial', sans-serif;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .td {
            text-align: center;
            padding: 5px;
            font-size: 12px;
            /* border: 1px solid black; */
        }

        .container {
            position: relative;
            margin-top: 7rem;
        }

        .info-container {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            margin: 5px 0;
        }

        .info-text {
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .info-catatan2 {
            font-weight: bold;
            margin-right: 5px;
            min-width: 120px;
            /* Menetapkan lebar minimum untuk kolom pertama */
        }

        .alamat,
        .nama-pt {
            color: black;
        }

        .separator {
            padding-top: 12px;
            text-align: center;
        }

        .separator span {
            display: inline-block;
            border-top: 1px solid black;
            width: 100%;
            position: relative;
            top: -8px;
        }

        @page {
            /* size: A4; */
            margin: 1cm;
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <div id="logo-container">
        <img src="{{ public_path('storage/uploads/gambar_logo/login.png') }}" width="80" height="40"
            alt="Logo JavaTeknik">
    </div>
    <div style="font-weight: bold; text-align: center">
        <span style="font-weight: bold; font-size: 18px;">LAPORAN PELUNASAN FAKTUR PEMBELIAN - RANGKUMAN</span>
        <br>
        <div class="text">
            @php
                $startDate = request()->query('created_at');
                $endDate = request()->query('tanggal_akhir');
            @endphp
            @if ($startDate && $endDate)
                <p>Periode:{{ $startDate }} s/d {{ $endDate }}</p>
            @else
                <p>Periode: Tidak ada tanggal awal dan akhir yang diteruskan.</p>
            @endif
        </div>
    </div>
    {{-- <hr style="border-top: 0.1px solid black; margin: 1px 0;"> --}}

    </div>
    {{-- <hr style="border-top: 0.1px solid black; margin: 1px 0;"> --}}
    <table style="width: 100%; border-top: 1px solid black;" cellpadding="2" cellspacing="0">
        <!-- Header row -->
        <tr>
            <td class="td" style="text-align: left; padding: 5px; font-weight:bold; font-size: 12px;">No</td>
            <td class="td" style="text-align: left; padding: 5px; font-weight:bold; font-size: 12px;">No. Faktur
            </td>
            <td class="td" style="text-align: left; padding: 5px; font-weight:bold; font-size: 12px;width: 12%;">
                Tanggal
            </td>
            {{-- <td class="td" style="text-align: left; padding: 5px; font-weight:bold; font-size: 12px;">Type Memo</td> --}}
            <td class="td" style="text-align: left; padding: 5px; font-weight:bold; font-size: 12px;">Supplier</td>
            <td class="td" style="text-align: right; padding: 5px; font-weight:bold; font-size: 12px;">Total</td>
        </tr>
        <!-- Separator row -->
        <tr style="border-bottom: 1px solid black;">
            <td colspan="4" style="padding: 0px;"></td>
        </tr>
        <!-- Data rows -->
        @foreach ($inquery as $faktur)
            <tr>
                <td class="td" style="text-align: left; padding: 5px; font-size: 12px;">{{ $loop->iteration }}</td>
                <td class="td" style="text-align: left; padding: 5px; font-size: 12px;">
                    @if ($faktur->pembelian)
                        {{ $faktur->pembelian->kode_pembelian }}
                    @else
                        tidak ada
                    @endif
                </td>
                <td class="td" style="text-align: left; padding: 5px; font-size: 12px;">
                    @if ($faktur->pembelian)
                        {{ $faktur->pembelian->tanggal_awal }}
                    @else
                        tidak ada
                    @endif
                </td>
                <td class="td" style="text-align: left; padding: 5px; font-size: 12px;">
                    @if ($faktur->pembelian)
                        {{ $faktur->pembelian->supplier->nama_supp }}
                    @else
                        tidak ada
                    @endif
                </td>
                <td class="td" style="text-align: right; padding: 5px; font-size: 12px;">
                    {{ number_format($faktur->total, 0, ',', '.') }}
                </td>

            </tr>
        @endforeach
        <!-- Separator row -->
        <tr style="border-bottom: 1px solid black;">
            <td colspan="" style="padding: 0px;"></td>
        </tr>
        <!-- Subtotal row -->
        @php
            $total = 0;
        @endphp
        @foreach ($inquery as $item)
            @php
                $total += $item->total;
            @endphp
        @endforeach
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold; padding: 5px; font-size: 12px;">Sub Total
            </td>
            <td style="text-align: right; font-weight: bold; padding: 5px; font-size: 12px;">
                {{ number_format($total, 0, ',', '.') }}
            </td>
        </tr>
    </table>




    <br>

    <!-- Tampilkan sub-total di bawah tabel -->
    {{-- <div style="text-align: right;">
        <strong>Sub Total: Rp. {{ number_format($total, 0, ',', '.') }}</strong>
    </div> --}}


    {{-- <br> --}}

    <br>
    <br>

</body>

</html>
