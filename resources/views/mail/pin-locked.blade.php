<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<meta charset="utf8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="x-apple-disable-message-reformatting">
		<title>{{ $brand }} - Pin anda terblokir</title>
		<link href="http://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet" type="text/css">
		<style>body{box-sizing:border-box;margin:0;padding:0;width:100%;color:#333;word-break:break-word;-webkit-font-smoothing:antialiased;font-family:Ubuntu}a{color:#0072bc;text-decoration:none}img{border:0;line-height:100%;vertical-align:middle}.wrapper{padding:0 30px;width:100%!important;padding-bottom:40px}.main-head{margin-bottom:40px}.center-icon{padding:33px 0 43px 0}.align-center{text-align:center}.align-right{text-align:right}.align-top{vertical-align:top!important}.align-middle{vertical-align:middle!important}.w-600{width:600px!important}.img-heading{width:180px}.pt-5{padding-top:5px}.pt-10{padding-top:10px}.pt-20{padding-top:20px}.pb-20{padding-bottom:20px}.pt-40{padding-top:40px}.pb-40{padding-bottom:40px}.text-12{font-size:12px;line-height:16px;margin:0}.text-14{font-size:14px;line-height:19px;margin:0}.text-16{font-size:16px;line-height:21px;margin:0}.text-20{font-size:20px;line-height:27px;margin:0}.text-25{font-size:25px;line-height:33px;margin:0}.text-40{font-size:40px;line-height:53px;margin:0}.color-blue{color:#0060af!important}.color-grey{color:#888!important}.bold{font-weight:700}.footer-logo img{width:110px}.footer-message{border-top:1px solid #efefef;padding-top:27px}.icon{display:inline-block;vertical-align:middle;margin-right:5px}</style>
	</head>
	<body style="background-color: #CCCCCC;">
		<table cellpadding="0" cellspacing="0" role="presentation" style="margin: auto;">
			<tr>
				<td align="center">
					<table cellpadding="0" cellspacing="0" role="presentation" style="background: -moz-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(29,60,113,1)), color-stop(100%, rgba(0,96,175,1))); background: -webkit-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: -o-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: -ms-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: linear-gradient(to bottom, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1d3c71', endColorstr='#0060af', GradientType=0 ); padding: 36px;-webkit-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);">
						<tr>
							<td align="center" class="align-middle">
								<table cellpadding="0" cellspacing="0" role="presentation" class="w-600 sm-w-full" style="background-color:#fff; border-radius: 10px; -webkit-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);">
							        <tr>
							          <td class="align-center pt-40" colspan="2">
							            <h1 class="main-head"><img class="img-heading align-center" src="{{ $assets }}logo.png" title="{{ $brand }}"></h1>
							          </td>
							        </tr>

							        <tr>
										<td class="align-center" colspan="2">
							              	<p class="text-16 align-center"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d M Y, H.i'); ?></p>
											<strong class="text-25 color-blue bold pt-5">Pin Anda terblokir</strong>
										</td>
									</tr>

									<tr>
										<td class="align-center center-icon pt-40" colspan="2">
											<img style="border:0;line-height:100%;vertical-align:middle;width: 100px;" src="{{ $assets }}pin-locked.png" alt="">
										</td>
									</tr>

									<tr>
										<td colspan="2">
											<p class="text-16 align-center pt-20">Anda telah melewati batas percobaan, pin anda telah kami blokir!</p>
											<p class="text-16 align-center pt-20">Silahkan menuju FAQ tentang <strong>Pin Terblokir</strong> untuk memulihkan akun anda.	</p>
										</td>
									</tr>

									<tr>
										<td colspan="2">
											<p class="text-16 pt-40 align-center"></p>
											<p class="text-16 pt-5 pb-40 align-center"></p>
										</td>
									</tr>

									<tr>
										<td colspan="2" class="footer-message pb-20">
										</td>
									</tr>
									
									<tr class="color-grey">
										<td class="align-top pb-40" style="padding-left: 30px;">
											<p class="text-12">Butuh bantuan ?</p>
											<p class="text-12">hubungi <a target="_blank" href="https://{{ $domain }}/page/faq">Layanan Pelanggan</a>.</p>
											<p class="text-12 pt-10">&copy <?= date('Y') ?> copyright {{ $brand }}</p>
											<p class="text-12">{{ $company }}</p>
										</td>
										<td class="align-top align-right" style="padding-right: 30px;">
											<span class="footer-logo"><img src="{{ $assets }}logo.png"></span>
											<p class="text-12 pt-10"><a target="_blank" href="https://{{ $domain }}/">{{ $domain }}</a>	</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
