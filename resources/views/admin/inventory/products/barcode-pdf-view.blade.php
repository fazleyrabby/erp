<!DOCTYPE html>
<html>

<head>
    <style>
        .barcode {
            width: 100%;
        }

        .barcodeNo {
            display: inline-block;
            width: 25%;
        }

    </style>
</head>

<body>




    <div class="barcode">
        @for ($i = 1; $i <= $quantity; $i++)
            <div class="barcodeNo">{!! $barcode . $barcodeNo !!}</div>
            @if ($i % 4 == 0)
                <div style="height:1px;"></div>
            @endif
        @endfor
    </div>

</body>

</html>
