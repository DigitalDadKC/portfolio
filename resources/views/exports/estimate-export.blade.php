<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Sample Estimate Sheet</title>

		<style>
            body {
				font-family: 'Cambria';
            }

            .terms {
                text-align: center;
            }

            .page-break {
            page-break-after: always;
            }

			.invoice-box {
				padding: 10px;
				border: 1px solid #eee;
				box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
				line-height: 16px;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
                /* border: 1px solid black; */
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 24px;
				line-height: 45px;
				color: #333;
				font-family: 'Lucida Console';
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.scope td {
				background: #eee;
				border-bottom: 1px solid black;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		</style>
	</head>
	<body>
        @php
            $projectTotal = 0;
            $link = asset('/storage/' . (optional($company)->logo))
        @endphp

		<div class="invoice-box page-break">
			<table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td class="title">
                            <img
                                src="{{$link}}"
                                alt="company logo"
                                style="width: 200px;"
                            />
                        </td>

                        <td colspan="4" style="text-align: right;">
                            Job #{{$proposal->job->number}}<br />
                            Created: {{date_format($proposal->job->created_at, 'M d, Y')}}<br />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{optional($company)->name}}<br />
                            {{optional($company)->address}}<br />
                            {{optional($company)->city}} {{optional($company)->state->state ?? ''}} {{optional($company)->zip_code}}
                        </td>

                        <td colspan="4" style="text-align: right;">
                            Site Address:<br />
                            {{$proposal->job->address}}<br />
                            {{$proposal->job->city}}, {{$proposal->job->state->abbr }}
                        </td>
                    </tr>

                @foreach($scopes as $scope)
                @php
                    $scopeTotal = 0;
                @endphp
                    <tr class="scope" style="margin-top: 10px;">
                        <td style="margin-top: 10px;">{{optional($scope)->name}}</td>
                        <td colspan="4" style="text-align: right;">@if($scope->area)Area: {{$scope->area}} sf @endif</td>
                    </tr>
                    @foreach(optional($scope)->lines as $line)
                        <tr style="font-size: 14px;">
                            <td>{{$line->description}}</td>
                            <td>{{$line->unit_of_measurement->UOM}}</td>
                            <td>${{number_format($line->price, 2)}}</td>
                            <td>{{number_format($line->quantity, 2)}}</td>
                            <td style="text-align: right;">${{number_format($line->price * $line->quantity, 2)}}</td>
                        </tr>
                        @php
                            $scopeTotal += ($line->price)*($line->quantity);
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;">SCOPE TOTAL:</td>
                        <td style="font-weight: bold; text-align: right;">${{number_format($scopeTotal, 2)}}</td>
                    </tr>
                    @php
                        $projectTotal += $scopeTotal;
                    @endphp
                @endforeach

                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">PROJECT TOTAL:</td>
                    <td style="text-align: right; font-weight: bold">${{number_format($projectTotal, 2)}}</td>
                </tr>
                <tr>
                    <td>EXCLUSIONS:</td>
                </tr>
                <tr>
                    <td colspan="5">{{$proposal->exclusions}}</td>
                </tr>
			</table>
		</div>
        <div class="terms">
            <h1>Terms & Conditions</h1>
            {{ optional($company)->terms}}
        </div>
	</body>
</html>
